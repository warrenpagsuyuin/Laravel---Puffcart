<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\ProductReview;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private const ACCOUNT_PENDING_MESSAGE = 'Please wait for the admin to verify your account before placing an order.';

    public function checkout(Request $request, CheckoutService $checkoutService)
    {
        if (!auth()->user()->canPlaceOrders()) {
            return redirect()
                ->route('cart')
                ->with('error', self::ACCOUNT_PENDING_MESSAGE);
        }

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
        if (!auth()->user()->canPlaceOrders()) {
            return redirect()
                ->route('cart')
                ->with('error', self::ACCOUNT_PENDING_MESSAGE);
        }

        $order = $checkoutService->placeOrder(auth()->user(), $request->validated(), $request);

        if ($order->requiresOnlinePayment()) {
            return app(PaymentController::class)->initiateCheckout($request, $order);
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
        $reviewedProductIds = ProductReview::where('user_id', auth()->id())
            ->whereIn('product_id', $order->items->pluck('product_id')->filter()->unique())
            ->get()
            ->keyBy('product_id');

        return view('orders.show', compact('order', 'reviewedProductIds'));
    }

    public function storeReview(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless(in_array($order->status, ['completed', 'delivered'], true), 403);

        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $productIds = $order->items()->pluck('product_id')->filter()->unique();

        if (!$productIds->contains((int) $data['product_id'])) {
            return back()->with('error', 'You can only review products from this completed order.');
        }

        $alreadyReviewed = ProductReview::where('user_id', auth()->id())
            ->where('product_id', $data['product_id'])
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You already reviewed this product.');
        }

        ProductReview::create([
            'product_id' => $data['product_id'],
            'user_id' => auth()->id(),
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Thank you. Your review has been posted.');
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
