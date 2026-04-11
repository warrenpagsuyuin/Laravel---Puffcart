<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = auth()->user()->cartItems()->with('product')->get();
        $subtotal = $items->sum(fn($item) => $item->product->price * $item->quantity);
        $deliveryFee = $subtotal >= 500 ? 0 : 50;
        return view('customer.cart', compact('items', 'subtotal', 'deliveryFee'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock.');
        }

        $cartItem = CartItem::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $request->product_id],
            ['quantity' => 0]
        );
        $cartItem->increment('quantity', $request->quantity);

        return back()->with('success', "{$product->name} added to cart!");
    }

    public function update(Request $request, CartItem $item)
    {
        $this->authorize('update', $item);
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $item)
    {
        $this->authorize('delete', $item);
        $item->delete();
        return back()->with('success', 'Item removed from cart.');
    }
}
