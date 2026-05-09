<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Services\CustomerBehaviorService;

class CartController extends Controller
{
    public function index()
    {
        $items = auth()->user()->cartItems()->with('product', 'flavor', 'batteryColor')->get();
        $subtotal = $items->sum(fn ($item) => (float) $item->product->price * $item->quantity);
        $deliveryFee = $subtotal >= 500 ? 0 : 50;
        $total = $subtotal + $deliveryFee;

        return view('cart', compact('items', 'subtotal', 'deliveryFee', 'total'));
    }

    public function add(AddToCartRequest $request, CustomerBehaviorService $behaviorService)
    {
        $product = Product::with('availableFlavorOptions', 'availableColorOptions')->findOrFail($request->product_id);
        $quantity = (int) $request->quantity;
        $productType = $product->product_type ?: Product::TYPE_OTHER;
        $isBattery = $productType === Product::TYPE_BATTERY;
        $isBundle = $productType === Product::TYPE_BUNDLE;

        $flavor = ProductFlavor::inStock()
            ->where('product_id', $product->id)
            ->whereKey($request->integer('product_flavor_id'))
            ->first();
        $batteryColor = null;

        if (!$product->is_active) {
            return back()->with('error', 'This product is no longer available.');
        }

        if ($isBattery) {
            if (!$flavor || $flavor->option_type !== ProductFlavor::TYPE_COLOR) {
                return back()->with('error', 'Please select an available battery color.');
            }
        } else {
            if (!$flavor || $flavor->option_type !== ProductFlavor::TYPE_FLAVOR) {
                return back()->with('error', 'Please select an available flavor.');
            }
        }

        if ($isBundle) {
            $batteryColor = ProductFlavor::inStock()
                ->where('product_id', $product->id)
                ->where('option_type', ProductFlavor::TYPE_COLOR)
                ->whereKey($request->integer('battery_color_id'))
                ->first();

            if (!$batteryColor) {
                return back()->with('error', 'Please select an available battery color.');
            }
        }

        $selectedFlavor = $isBattery ? null : $flavor->name;
        $selectedBatteryColor = $isBattery ? $flavor->name : $batteryColor?->name;

        $existingQuantity = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('product_flavor_id', $flavor->id)
            ->where('battery_color_id', $batteryColor?->id)
            ->value('quantity') ?? 0;
        $existingFlavorQuantity = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('product_flavor_id', $flavor->id)
            ->sum('quantity');
        $existingColorQuantity = $batteryColor
            ? CartItem::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->where('battery_color_id', $batteryColor->id)
                ->sum('quantity')
            : 0;

        if ($isBundle && $batteryColor && $batteryColor->stock < ($existingColorQuantity + $quantity)) {
            return back()->with('error', "{$product->name} ({$batteryColor->name}) only has {$batteryColor->stock} battery color item(s) left.");
        }

        $neededFlavorQuantity = $isBundle ? $existingFlavorQuantity + $quantity : $existingQuantity + $quantity;

        if ($flavor->stock < $neededFlavorQuantity) {
            $optionLabel = $isBattery ? 'battery color' : 'flavor';

            return back()->with('error', "{$product->name} ({$flavor->name}) only has {$flavor->stock} {$optionLabel} item(s) left.");
        }

        $cartItem = CartItem::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'product_flavor_id' => $flavor->id,
                'battery_color_id' => $batteryColor?->id,
                'product_type' => $productType,
            ],
            [
                'quantity' => 0,
                'selected_flavor' => $selectedFlavor,
                'selected_battery_color' => $selectedBatteryColor,
                'bundle_pods' => $product->bundle_pods,
                'bundle_battery' => $product->bundle_battery,
            ]
        );

        $cartItem->fill([
            'selected_flavor' => $selectedFlavor,
            'selected_battery_color' => $selectedBatteryColor,
            'bundle_pods' => $product->bundle_pods,
            'bundle_battery' => $product->bundle_battery,
        ])->save();

        $cartItem->increment('quantity', $quantity);
        $behaviorService->cartAdded($product, $quantity, $request);

        $messageDetail = $selectedBatteryColor && $selectedFlavor
            ? " ({$selectedFlavor}, {$selectedBatteryColor})"
            : ' (' . ($selectedBatteryColor ?: $selectedFlavor) . ')';

        if ($request->input('intent') === 'buy_now') {
            return redirect()->route('checkout')->with('success', "{$product->name}{$messageDetail} is ready for checkout.");
        }

        return back()->with('success', "{$product->name}{$messageDetail} added to cart!");
    }

    public function update(UpdateCartItemRequest $request, CartItem $item)
    {
        abort_unless($item->user_id === auth()->id(), 403);

        $quantity = (int) $request->quantity;

        $item->loadMissing('product', 'flavor', 'batteryColor');

        if (!$item->product?->is_active || !$item->flavor?->is_active || ($item->battery_color_id && !$item->batteryColor?->is_active)) {
            $productName = $item->product?->name ?? 'This item';

            return back()->with('error', "{$productName} is no longer available in the selected option.");
        }

        if ($item->product_type === Product::TYPE_BUNDLE) {
            $otherFlavorQuantity = CartItem::where('user_id', auth()->id())
                ->where('id', '!=', $item->id)
                ->where('product_id', $item->product_id)
                ->where('product_flavor_id', $item->product_flavor_id)
                ->sum('quantity');
            $otherColorQuantity = CartItem::where('user_id', auth()->id())
                ->where('id', '!=', $item->id)
                ->where('product_id', $item->product_id)
                ->where('battery_color_id', $item->battery_color_id)
                ->sum('quantity');

            if ($item->batteryColor->stock < ($otherColorQuantity + $quantity)) {
                return back()->with('error', "{$item->product->name} ({$item->batteryColor->name}) only has {$item->batteryColor->stock} battery color item(s) left.");
            }

            if ($item->flavor->stock < ($otherFlavorQuantity + $quantity)) {
                return back()->with('error', "{$item->product->name} ({$item->flavor->name}) only has {$item->flavor->stock} flavor item(s) left.");
            }
        } elseif ($item->battery_color_id && $item->batteryColor->stock < $quantity) {
            return back()->with('error', "{$item->product->name} ({$item->batteryColor->name}) only has {$item->batteryColor->stock} battery color item(s) left.");
        } elseif ($item->flavor->stock < $quantity) {
            $optionLabel = $item->product_type === Product::TYPE_BATTERY ? 'battery color' : 'flavor';

            return back()->with('error', "{$item->product->name} ({$item->flavor->name}) only has {$item->flavor->stock} {$optionLabel} item(s) left.");
        }

        $item->update(['quantity' => $quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $item)
    {
        abort_unless($item->user_id === auth()->id(), 403);

        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
