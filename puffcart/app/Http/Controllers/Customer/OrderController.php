<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, OrderTracking, Payment, CartItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $items = auth()->user()->cartItems()->with('product')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $subtotal    = $items->sum(fn($i) => $i->product->price * $i->quantity);
        $deliveryFee = $subtotal >= 500 ? 0 : 50;
        return view('customer.checkout', compact('items', 'subtotal', 'deliveryFee'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string',
            'delivery_phone'   => 'required|string',
            'payment_method'   => 'required|in:gcash,maya,cod,bank_transfer',
        ]);

        DB::transaction(function () use ($request) {
            $items       = auth()->user()->cartItems()->with('product')->get();
            $subtotal    = $items->sum(fn($i) => $i->product->price * $i->quantity);
            $deliveryFee = $subtotal >= 500 ? 0 : 50;
            $total       = $subtotal + $deliveryFee;

            $order = Order::create([
                'user_id'          => auth()->id(),
                'subtotal'         => $subtotal,
                'delivery_fee'     => $deliveryFee,
                'total'            => $total,
                'delivery_address' => $request->delivery_address,
                'delivery_phone'   => $request->delivery_phone,
                'payment_method'   => $request->payment_method,
                'notes'            => $request->notes,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->product->price * $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            Payment::create([
                'order_id' => $order->id,
                'method'   => $request->payment_method,
                'amount'   => $total,
                'status'   => $request->payment_method === 'cod' ? 'pending' : 'pending',
            ]);

            OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => 'pending',
                'message'     => 'Order placed successfully. Awaiting confirmation.',
                'occurred_at' => now(),
            ]);

            CartItem::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function index()
    {
        $orders = auth()->user()->orders()->with('items', 'payment')->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product', 'payment', 'tracking');
        return view('customer.order-detail', compact('order'));
    }

    public function track(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items', 'payment', 'tracking');
        return view('customer.order-track', compact('order'));
    }
}
