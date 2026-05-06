<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CustomerBehaviorService;

class CartController extends Controller
{
    public function index()
    {
        $items = auth()->user()->cartItems()->with('product')->get();
        $subtotal = $items->sum(fn ($item) => (float) $item->product->price * $item->quantity);
        $deliveryFee = $subtotal >= 500 ? 0 : 50;
        $total = $subtotal + $deliveryFee;

        return view('cart', compact('items', 'subtotal', 'deliveryFee', 'total'));
    }

    public function add(AddToCartRequest $request, CustomerBehaviorService $behaviorService)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;
        $existingQuantity = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->value('quantity') ?? 0;

        if (!$product->is_active || $product->stock < ($existingQuantity + $quantity)) {
            return back()->with('error', 'Insufficient stock.');
        }

        $cartItem = CartItem::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['quantity' => 0]
        );

        $cartItem->increment('quantity', $quantity);
        $behaviorService->cartAdded($product, $quantity, $request);

        return back()->with('success', "{$product->name} added to cart!");
    }

    public function update(UpdateCartItemRequest $request, CartItem $item)
    {
        abort_unless($item->user_id === auth()->id(), 403);

        $quantity = (int) $request->quantity;

        if ($item->product->stock < $quantity) {
            return back()->with('error', "{$item->product->name} only has {$item->product->stock} item(s) left.");
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
