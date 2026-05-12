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
        $cartItemIds = $request->input('cart_item_ids');
        $cartItemIds = is_array($cartItemIds) ? $cartItemIds : null;
        $summary = $checkoutService->preview(auth()->user(), $request->get('promo_code'), $cartItemIds);

        if ($summary['items']->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Please select at least one item to checkout.');
        }

        if (!empty($summary['stock_errors'])) {
            return redirect()->route('cart')->with('error', $summary['stock_errors'][0]);
        }

        return view('checkout', $summary);
    }

    public function placeOrder(CheckoutRequest $request, CheckoutService $checkoutService)
    {
        $order = $checkoutService->placeOrder(auth()->user(), $request->validated(), $request);

        if ($order->requiresOnlinePayment()) {
            return redirect()
                ->route('payment.show', $order)
                ->with('success', 'Order created. Please complete payment before tracking can proceed.');
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }

    public function index()
    {
        $orders = auth()->user()->orders()->with('items.flavor', 'items.batteryColor', 'payment')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product', 'items.flavor', 'items.batteryColor', 'payment', 'tracking');

        return view('orders.show', compact('order'));
    }

    public function track(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.flavor', 'items.batteryColor', 'payment', 'tracking');

        if (!$order->isPaymentComplete()) {
            return redirect()
                ->route('payment.show', $order)
                ->with('error', 'Please complete payment before tracking this order.');
        }

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
