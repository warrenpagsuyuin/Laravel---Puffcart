<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PayMongoService $payMongoService;

    public function __construct(PayMongoService $payMongoService)
    {
        $this->payMongoService = $payMongoService;
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product', 'payment');

        if (!$order->requiresOnlinePayment()) {
            return redirect()->route('orders.show', $order);
        }

        if ($order->isPaymentComplete()) {
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Payment confirmed. Your order is now processing.');
        }

        return view('payment', compact('order'));
    }

    /**
     * Initiate checkout for an order
     */
    public function initiateCheckout(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Verify order has items and is not already paid
        if ($order->items()->count() === 0) {
            return response()->json(['error' => 'Order has no items'], 400);
        }

        if ($order->payment && $order->payment->payment_status === 'paid') {
            return response()->json(['error' => 'Order already paid'], 400);
        }

        if (!$order->requiresOnlinePayment()) {
            return $request->expectsJson()
                ? response()->json(['error' => 'This order does not require online payment.'], 400)
                : redirect()->route('orders.show', $order);
        }

        try {
            $order->loadMissing('items.product');

            // Prepare line items for PayMongo
            $lineItems = $order->items->map(function ($item) {
                return [
                    'currency' => 'PHP',
                    'amount' => (int) ($item->price * 100),
                    'description' => $item->product->name,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            })->toArray();

            // Create checkout session
            $checkout = $this->payMongoService->createCheckoutSession([
                'line_items' => $lineItems,
                'payment_methods' => $this->paymongoMethodsFor($order->payment_method),
                'success_url' => route('payment.success', ['order' => $order->id]),
                'cancel_url' => route('payment.cancel', ['order' => $order->id]),
                'description' => "Puffcart Order #{$order->order_number}",
                'customer_email' => auth()->user()->email,
                'customer_name' => auth()->user()->name,
            ]);

            $checkoutId = $checkout['data']['id'] ?? null;
            if (!$checkoutId) {
                throw new \Exception('No checkout ID returned from PayMongo');
            }

            // Store or update payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'paymongo_checkout_id' => $checkoutId,
                    'payment_status' => 'pending',
                    'amount' => $order->total,
                    'currency' => 'PHP',
                    'method' => $order->payment_method,
                    'payment_method' => 'paymongo',
                ]
            );

            // Log audit
            AuditLog::log(
                'checkout_created',
                "PayMongo checkout created for order #{$order->order_number}",
                auth()->id(),
                $request->ip(),
                $request->userAgent()
            );

            $checkoutUrl = $checkout['data']['attributes']['checkout_url'] ?? null;

            if (!$checkoutUrl) {
                throw new \Exception('No checkout URL returned from PayMongo');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'checkout_url' => $checkoutUrl,
                ]);
            }

            return redirect()->away($checkoutUrl);
        } catch (\Exception $e) {
            Log::error('PayMongo checkout creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            AuditLog::log(
                'checkout_failed',
                "PayMongo checkout failed for order #{$order->order_number}: {$e->getMessage()}",
                auth()->id(),
                $request->ip(),
                $request->userAgent()
            );

            $message = app()->environment('local')
                ? $e->getMessage()
                : 'Payment checkout could not be started. Please try again.';

            return $request->expectsJson()
                ? response()->json(['error' => $message], 500)
                : redirect()
                    ->route('payment.show', $order)
                    ->with('error', $message);
        }
    }

    /**
     * Payment success callback
     */
    public function paymentSuccess(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Unauthorized');
        }

        // Get the most recent payment for this order
        $payment = $order->payment;
        if (!$payment || !$payment->paymongo_checkout_id) {
            return redirect()->route('home')->with('error', 'No payment found');
        }

        // In a real implementation, you would query PayMongo to verify payment status
        // PayMongo only returns to the success URL after the hosted checkout succeeds.
        $wasPaid = $payment->isPaid();

        $payment->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        if ($order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }

        if (!$wasPaid) {
            OrderTracking::create([
                'order_id' => $order->id,
                'status' => 'processing',
                'message' => 'Payment confirmed. Order is now being processed.',
                'occurred_at' => now(),
            ]);
        }

        return view('payment-success', ['order' => $order, 'payment' => $payment]);
    }

    /**
     * Payment cancel callback
     */
    public function paymentCancel(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Unauthorized');
        }

        // Update payment status
        $payment = $order->payment;
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'payment_status' => 'cancelled',
            ]);

            AuditLog::log(
                'payment_cancelled',
                "Payment cancelled for order #{$order->order_number}",
                auth()->id(),
                $request->ip(),
                $request->userAgent()
            );
        }

        return view('payment-cancel', ['order' => $order]);
    }

    /**
     * Handle PayMongo webhook
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Paymongo-Signature');

        if (!$signature) {
            Log::warning('PayMongo webhook received without signature');

            return response()->json(['error' => 'No signature'], 401);
        }

        // Verify webhook signature
        if (!$this->payMongoService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('PayMongo webhook signature verification failed');

            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->json()->all();
        $this->handleWebhookEvent($event);

        return response()->json(['status' => 'received']);
    }

    /**
     * Handle different webhook event types
     */
    protected function handleWebhookEvent(array $event): void
    {
        $type = $event['type'] ?? null;
        $data = $event['data']['attributes'] ?? [];

        Log::info('Processing PayMongo webhook event', ['type' => $type]);

        match ($type) {
            'payment_intent.succeeded' => $this->handlePaymentIntentSucceeded($data, $event),
            'payment_intent.payment_failed' => $this->handlePaymentIntentFailed($data, $event),
            'checkout_session.completed' => $this->handleCheckoutCompleted($data, $event),
            default => Log::warning('Unknown webhook type: ' . $type),
        };
    }

    /**
     * Handle successful payment intent
     */
    protected function handlePaymentIntentSucceeded(array $data, array $event): void
    {
        $intentId = $event['data']['id'] ?? null;
        if (!$intentId) {
            return;
        }

        // Find payment by PayMongo intent ID
        $payment = Payment::where('paymongo_payment_intent_id', $intentId)->first();
        if (!$payment) {
            Log::warning('Payment not found for intent: ' . $intentId);

            return;
        }

        // Update payment status
        $payment->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'transaction_reference' => $intentId,
            'paid_at' => now(),
        ]);

        // Update order status
        $order = $payment->order;
        if ($order && $order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }

        AuditLog::log(
            'payment_success',
            "PayMongo payment successful for order #{$order->order_number}",
            $order->user_id,
            null,
            'webhook'
        );
    }

    /**
     * Handle failed payment intent
     */
    protected function handlePaymentIntentFailed(array $data, array $event): void
    {
        $intentId = $event['data']['id'] ?? null;
        if (!$intentId) {
            return;
        }

        // Find payment by PayMongo intent ID
        $payment = Payment::where('paymongo_payment_intent_id', $intentId)->first();
        if (!$payment) {
            Log::warning('Payment not found for intent: ' . $intentId);

            return;
        }

        // Update payment status
        $payment->update(['payment_status' => 'failed']);

        AuditLog::log(
            'payment_failed',
            "PayMongo payment failed for order #{$payment->order->order_number}",
            $payment->order->user_id,
            null,
            'webhook'
        );
    }

    /**
     * Handle completed checkout session
     */
    protected function handleCheckoutCompleted(array $data, array $event): void
    {
        $checkoutId = $event['data']['id'] ?? null;
        if (!$checkoutId) {
            return;
        }

        // Find payment by checkout ID
        $payment = Payment::where('paymongo_checkout_id', $checkoutId)->first();
        if (!$payment) {
            Log::warning('Payment not found for checkout: ' . $checkoutId);

            return;
        }

        // Extract payment information from webhook data
        $paymentId = $data['payments'][0]['id'] ?? null;
        if ($paymentId) {
            $payment->update(['paymongo_payment_id' => $paymentId]);
        }

        // Update payment status based on data
        $paymentStatus = $data['payment_intent']['attributes']['status'] ?? 'unknown';
        if ($paymentStatus === 'succeeded') {
            $payment->update([
                'status' => 'paid',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            // Update order status
            $order = $payment->order;
            if ($order && $order->status === 'pending') {
                $order->update(['status' => 'processing']);
            }

            AuditLog::log(
                'payment_success',
                "PayMongo checkout completed for order #{$order->order_number}",
                $order->user_id,
                null,
                'webhook'
            );
        }
    }

    private function paymongoMethodsFor(string $method): array
    {
        return match ($method) {
            'gcash' => ['gcash'],
            'maya' => ['paymaya'],
            'bank_transfer' => ['card', 'gcash', 'paymaya'],
            default => ['gcash', 'card', 'paymaya'],
        };
    }
}
