<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        private CustomerBehaviorService $behaviorService,
        private InventoryLogService $inventoryLogs,
    )
    {
    }

    public function preview(User $user, ?string $promoCode = null, ?array $cartItemIds = null): array
    {
        $items = $this->cartItemsForCheckout($user, $cartItemIds)->get();
        $subtotal = $items->sum(fn (CartItem $item) => (float) $item->product->price * $item->quantity);
        $deliveryFee = $subtotal >= 500 || $subtotal <= 0 ? 0 : 50;
        $promo = $this->resolvePromoCode($promoCode);
        $discount = $promo ? $promo->discountFor($subtotal) : 0;

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'discount' => round($discount, 2),
            'total' => round(max(0, $subtotal + $deliveryFee - $discount), 2),
            'promo' => $promo,
            'stock_errors' => $this->stockErrors($items),
            'cart_item_ids' => $items->pluck('id')->all(),
        ];
    }

    public function placeOrder(User $user, array $data, Request $request): Order
    {
        return DB::transaction(function () use ($user, $data, $request) {
            $cartItems = $this->cartItemsForCheckout($user, $data['cart_item_ids'] ?? null)
                ->with('flavor', 'batteryColor')
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $products = Product::whereIn('id', $cartItems->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $optionIds = $cartItems->pluck('product_flavor_id')
                ->merge($cartItems->pluck('battery_color_id'))
                ->filter()
                ->unique();
            $flavors = ProductFlavor::whereIn('id', $optionIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $lines = collect();
            $requiredByFlavor = [];
            $requiredByProduct = [];

            foreach ($cartItems as $item) {
                $product = $products->get($item->product_id);

                if (!$product || !$product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => 'One of the products in your cart is no longer available.',
                    ]);
                }

                $flavor = $flavors->get($item->product_flavor_id);
                $isBattery = ($item->product_type ?: $product->product_type) === Product::TYPE_BATTERY;
                $isBundle = ($item->product_type ?: $product->product_type) === Product::TYPE_BUNDLE;

                if (!$flavor || $flavor->product_id !== $product->id || !$flavor->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => "{$product->name} is no longer available in the selected option.",
                    ]);
                }

                if ($isBattery && $flavor->option_type !== ProductFlavor::TYPE_COLOR) {
                    throw ValidationException::withMessages([
                        'cart' => "{$product->name} is no longer available in the selected battery color.",
                    ]);
                }

                if (!$isBattery && $flavor->option_type !== ProductFlavor::TYPE_FLAVOR) {
                    $optionLabel = $this->selectableOptionLabel($product);

                    throw ValidationException::withMessages([
                        'cart' => "{$product->name} is no longer available in the selected {$optionLabel}.",
                    ]);
                }

                $batteryColor = null;

                if ($isBundle) {
                    $batteryColor = $flavors->get($item->battery_color_id);

                    if (!$batteryColor || $batteryColor->product_id !== $product->id || !$batteryColor->is_active || $batteryColor->option_type !== ProductFlavor::TYPE_COLOR) {
                        throw ValidationException::withMessages([
                            'cart' => "{$product->name} is no longer available in the selected battery color.",
                        ]);
                    }

                    $requiredByFlavor[$batteryColor->id] = ($requiredByFlavor[$batteryColor->id] ?? 0) + $item->quantity;
                }

                $requiredByFlavor[$flavor->id] = ($requiredByFlavor[$flavor->id] ?? 0) + $item->quantity;
                $requiredByProduct[$product->id] = ($requiredByProduct[$product->id] ?? 0) + $item->quantity;
                $lineSubtotal = (float) $product->price * $item->quantity;
                $subtotal += $lineSubtotal;

                $lines->push([
                    'item' => $item,
                    'product' => $product,
                    'flavor' => $flavor,
                    'battery_color' => $batteryColor,
                    'subtotal' => $lineSubtotal,
                ]);
            }

            foreach ($requiredByFlavor as $flavorId => $requiredQuantity) {
                $flavor = $flavors->get($flavorId);

                if (!$flavor || $flavor->stock < $requiredQuantity) {
                    $product = $products->get($flavor?->product_id);
                    $name = $product?->name ?? 'One of your products';
                    $flavorName = $flavor?->name ?? 'selected option';
                    $optionLabel = $flavor?->option_type === ProductFlavor::TYPE_COLOR ? 'battery color' : $this->selectableOptionLabel($product);
                    $stock = $flavor?->stock ?? 0;

                    throw ValidationException::withMessages([
                        'cart' => "{$name} ({$flavorName}) only has {$stock} {$optionLabel} item(s) left.",
                    ]);
                }
            }

            $deliveryFee = $subtotal >= 500 ? 0 : 50;
            $promo = $this->resolvePromoCode($data['promo_code'] ?? null);

            if (!empty($data['promo_code']) && !$promo) {
                throw ValidationException::withMessages([
                    'promo_code' => 'The promo code is invalid, expired, or has reached its usage limit.',
                ]);
            }

            $discount = $promo ? $promo->discountFor($subtotal) : 0;
            $total = max(0, $subtotal + $deliveryFee - $discount);

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => round($subtotal, 2),
                'delivery_fee' => round($deliveryFee, 2),
                'discount' => round($discount, 2),
                'total' => round($total, 2),
                'promo_code' => $promo?->code,
                'delivery_address' => $data['delivery_address'],
                'delivery_phone' => $data['delivery_phone'],
                'notes' => $data['notes'] ?? null,
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($lines as $line) {
                /** @var \App\Models\CartItem $item */
                $item = $line['item'];
                /** @var \App\Models\Product $product */
                $product = $line['product'];
                /** @var \App\Models\ProductFlavor $flavor */
                $flavor = $line['flavor'];
                $batteryColor = $line['battery_color'];
                $lineSubtotal = $line['subtotal'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_flavor_id' => $flavor->id,
                    'battery_color_id' => $batteryColor?->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item->quantity,
                    'selected_flavor' => $flavor->option_type === ProductFlavor::TYPE_FLAVOR ? $flavor->name : null,
                    'selected_battery_color' => $batteryColor?->name ?: ($flavor->option_type === ProductFlavor::TYPE_COLOR ? $flavor->name : null),
                    'product_type' => $item->product_type ?: ($product->product_type ?? 'other'),
                    'bundle_pods' => $item->bundle_pods ?: $product->bundle_pods,
                    'bundle_battery' => $item->bundle_battery ?: $product->bundle_battery,
                    'subtotal' => round($lineSubtotal, 2),
                ]);

                $this->behaviorService->purchased($user, $product, $item->quantity, $lineSubtotal, $request);
            }

            foreach ($requiredByFlavor as $flavorId => $requiredQuantity) {
                $flavor = $flavors->get($flavorId);
                $before = (int) $flavor->stock;
                $flavor->decrement('stock', $requiredQuantity);
                $this->inventoryLogs->flavorStockChanged($flavor->fresh(), $before, $before - $requiredQuantity, 'stock_changed', [
                    'source' => 'checkout',
                    'order_id' => $order->id,
                ]);
            }

            foreach ($requiredByProduct as $productId => $quantitySold) {
                $product = $products->get($productId);
                $product->syncStockFromFlavors();

                if (Schema::hasColumn('products', 'sales_count')) {
                    $product->increment('sales_count', $quantitySold);
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'amount' => round($total, 2),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'currency' => 'PHP',
            ]);

            OrderTracking::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'message' => 'Order placed successfully and is awaiting confirmation.',
                'occurred_at' => now(),
            ]);

            if ($promo) {
                $promo->increment('used_count');
            }

            CartItem::where('user_id', $user->id)
                ->whereIn('id', $cartItems->pluck('id'))
                ->delete();

            return $order->load('items.product', 'items.flavor', 'items.batteryColor', 'payment', 'tracking');
        });
    }

    private function cartItemsForCheckout(User $user, ?array $cartItemIds = null)
    {
        $query = $user->cartItems()->with('product', 'flavor', 'batteryColor');
        $cartItemIds = collect($cartItemIds)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($cartItemIds->isNotEmpty()) {
            $query->whereIn('id', $cartItemIds);
        }

        return $query;
    }

    private function resolvePromoCode(?string $code): ?PromoCode
    {
        $code = trim((string) $code);

        if ($code === '' || !Schema::hasTable('promo_codes')) {
            return null;
        }

        return PromoCode::available()
            ->where('code', strtoupper($code))
            ->first();
    }

    private function stockErrors($items): array
    {
        return $items
            ->map(function (CartItem $item): ?string {
                if (!$item->product || !$item->product->is_active) {
                    return 'One of the products in your cart is no longer available.';
                }

                if (!$item->flavor || !$item->flavor->is_active) {
                    return "{$item->product->name} is no longer available in the selected option.";
                }

                if ($item->product_type === Product::TYPE_BUNDLE && (!$item->batteryColor || !$item->batteryColor->is_active)) {
                    return "{$item->product->name} is no longer available in the selected battery color.";
                }

                if ($item->product_type === Product::TYPE_BUNDLE && $item->batteryColor->stock < $item->quantity) {
                    return "{$item->product->name} ({$item->batteryColor->name}) only has {$item->batteryColor->stock} battery color item(s) left.";
                }

                if ($item->flavor->stock < $item->quantity) {
                    $optionLabel = $this->selectableOptionLabel($item->product);

                    return "{$item->product->name} ({$item->flavor->name}) only has {$item->flavor->stock} {$optionLabel} item(s) left.";
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function selectableOptionLabel(?Product $product): string
    {
        if (!$product) {
            return 'flavor';
        }

        if (($product->product_type ?: Product::TYPE_OTHER) === Product::TYPE_BATTERY) {
            return 'battery color';
        }

        $category = strtolower((string) $product->category_name);

        if (str_contains($category, 'coils') && str_contains($category, 'pods')) {
            return 'ohm';
        }

        if (str_contains($category, 'accessories') || str_contains($category, 'devices')) {
            return 'color';
        }

        return 'flavor';
    }
}
