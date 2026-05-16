<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\WalkinOrder;
use App\Services\InventoryLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminWalkInController extends Controller
{
    public function index()
    {
        $products = Product::with('availableFlavorOptions', 'availableColorOptions')
            ->active()
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $recentOrders = WalkinOrder::with('items')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.walk-in', compact('products', 'recentOrders'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'payment_method' => 'required|in:cash,gcash,maya',
            'mobile_number'  => 'required_if:payment_method,gcash,maya|nullable|string|max:20',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.product_flavor_id' => 'nullable|integer|exists:product_flavors,id',
            'items.*.battery_color_id' => 'nullable|integer|exists:product_flavors,id',
        ]);

        $items    = $request->input('items');
        $products = Product::whereIn('id', array_column($items, 'id'))->get()->keyBy('id');
        $optionIds = collect($items)
            ->pluck('product_flavor_id')
            ->merge(collect($items)->pluck('battery_color_id'))
            ->filter()
            ->unique()
            ->values();
        $options = ProductFlavor::whereIn('id', $optionIds)->get()->keyBy('id');
        $lines    = [];
        $subtotal = 0;
        $requiredByOption = [];
        $requiredByProduct = [];

        foreach ($items as $item) {
            $product = $products[$item['id']] ?? null;
            if (! $product) continue;

            $qty       = (int) $item['qty'];
            $lineTotal = $product->price * $qty;
            $isBattery = $product->product_type === Product::TYPE_BATTERY;
            $isBundle = $product->product_type === Product::TYPE_BUNDLE;

            $flavor = null;
            $batteryColor = null;

            if ($isBattery) {
                $flavor = $this->validOption($options, $item['product_flavor_id'] ?? null, $product, ProductFlavor::TYPE_COLOR);
                if (!$flavor) {
                    return back()->withInput()->with('error', "Please select an available battery color for \"{$product->name}\".");
                }
            } else {
                $flavor = $this->validOption($options, $item['product_flavor_id'] ?? null, $product, ProductFlavor::TYPE_FLAVOR);
                if (!$flavor && $product->availableFlavorOptions()->exists()) {
                    return back()->withInput()->with('error', "Please select an available flavor for \"{$product->name}\".");
                }

                if ($isBundle) {
                    $batteryColor = $this->validOption($options, $item['battery_color_id'] ?? null, $product, ProductFlavor::TYPE_COLOR);
                    if (!$batteryColor) {
                        return back()->withInput()->with('error', "Please select an available battery color for \"{$product->name}\".");
                    }
                }
            }

            if ($flavor) {
                $requiredByOption[$flavor->id] = ($requiredByOption[$flavor->id] ?? 0) + $qty;
            }

            if ($batteryColor) {
                $requiredByOption[$batteryColor->id] = ($requiredByOption[$batteryColor->id] ?? 0) + $qty;
            }

            if (!$flavor && !$batteryColor) {
                $requiredByProduct[$product->id] = ($requiredByProduct[$product->id] ?? 0) + $qty;
            }

            $lines[]   = [
                'product' => $product,
                'flavor' => $flavor,
                'battery_color' => $batteryColor,
                'qty' => $qty,
                'subtotal' => $lineTotal,
            ];
            $subtotal += $lineTotal;
        }

        foreach ($requiredByOption as $optionId => $requiredQty) {
            $option = $options[$optionId] ?? null;
            if (!$option || $option->stock < $requiredQty) {
                $product = $products[$option?->product_id] ?? null;
                $name = $product?->name ?? 'Selected product';
                $optionName = $option?->name ?? 'selected option';
                return back()->withInput()->with('error', "{$name} ({$optionName}) only has " . ($option?->stock ?? 0) . ' item(s) left.');
            }
        }

        foreach ($requiredByProduct as $productId => $requiredQty) {
            $product = $products[$productId] ?? null;
            if (!$product || $product->stock < $requiredQty) {
                return back()
                    ->withInput()
                    ->with('error', "Insufficient stock for \"{$product?->name}\". Only " . ($product?->stock ?? 0) . ' left.');
            }
        }

        $order = DB::transaction(function () use ($request, $lines, $subtotal, $requiredByOption, $requiredByProduct, $options, $products) {
            foreach ($requiredByOption as $optionId => $requiredQty) {
                $before = (int) $options[$optionId]->stock;
                $options[$optionId]->decrement('stock', $requiredQty);
                app(InventoryLogService::class)->flavorStockChanged($options[$optionId]->fresh(), $before, $before - $requiredQty, 'stock_changed', [
                    'source' => 'walk_in',
                ]);
            }

            foreach ($requiredByProduct as $productId => $requiredQty) {
                $before = (int) $products[$productId]->stock;
                $products[$productId]->decrement('stock', $requiredQty);
                app(InventoryLogService::class)->productEvent('stock_changed', $products[$productId]->fresh(), [
                    'source' => 'walk_in',
                ], $before, $before - $requiredQty);
            }

            foreach ($lines as $line) {
                if ($line['flavor'] || $line['battery_color']) {
                    $line['product']->syncStockFromFlavors();
                }
            }

            $order = WalkinOrder::create([
                'order_number'   => 'WI-' . strtoupper(Str::random(8)),
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'payment_method' => $request->payment_method,
                'mobile_number'  => $request->mobile_number,
                'status'         => 'completed',
                'subtotal'       => $subtotal,
                'total'          => $subtotal,
                'notes'          => null,
            ]);

            foreach ($lines as $line) {
                $product = $line['product'];
                $flavor = $line['flavor'];
                $batteryColor = $line['battery_color'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_flavor_id' => $flavor?->id,
                    'battery_color_id' => $batteryColor?->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $line['qty'],
                    'selected_flavor' => $flavor?->option_type === ProductFlavor::TYPE_FLAVOR ? $flavor->name : null,
                    'selected_battery_color' => $batteryColor?->name ?: ($flavor?->option_type === ProductFlavor::TYPE_COLOR ? $flavor->name : null),
                    'product_type' => $product->product_type,
                    'subtotal' => $line['subtotal'],
                ]);
            }

            return $order;
        });

        // Send receipt email
        try {
            Mail::send('emails.walk-in-receipt', [
                'orderNumber'   => $order->order_number,
                'customerName'  => $request->customer_name,
                'lines'         => $lines,
                'total'         => $subtotal,
                'paymentMethod' => $request->payment_method,
            ], function ($mail) use ($request, $order) {
                $mail->to($request->customer_email, $request->customer_name)
                     ->subject("Your PuffCart Receipt — {$order->order_number}");
            });
        } catch (\Throwable $e) {
            // Email failure should not block the transaction
        }

        return back()->with('success', "Walk-in order {$order->order_number} completed! Receipt sent to {$request->customer_email}.");
    }

    private function validOption($options, mixed $id, Product $product, string $type): ?ProductFlavor
    {
        if (!$id) {
            return null;
        }

        $option = $options[(int) $id] ?? null;

        if (!$option || $option->product_id !== $product->id || !$option->is_active || $option->option_type !== $type) {
            return null;
        }

        return $option;
    }
}
