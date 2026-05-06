<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request, CheckoutService $checkoutService)
    {
        $summary = $checkoutService->preview(auth()->user(), $request->get('promo_code'));

        if ($summary['items']->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        return view('checkout', $summary);
    }

    public function placeOrder(CheckoutRequest $request, CheckoutService $checkoutService)
    {
        $order = $checkoutService->placeOrder(auth()->user(), $request->validated(), $request);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }

    public function index()
    {
        $orders = auth()->user()->orders()->with('items', 'payment')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product', 'payment', 'tracking');

        return view('orders.show', compact('order'));
    }

    public function track(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items', 'payment', 'tracking');

        return view('orders.track', compact('order'));
    }

    public function trackingIndex()
    {
        $orders = auth()->user()
            ->orders()
            ->with('payment')
            ->latest()
            ->paginate(10);

        return view('tracking', compact('orders'));
    }
}
