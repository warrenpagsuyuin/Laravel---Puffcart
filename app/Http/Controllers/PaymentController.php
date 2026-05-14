<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessfulMail;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            $order->loadMissing('items.product', 'user', 'payment');
            $lineItems = $this->paymongoLineItems($order);

            // Create checkout session
            $checkout = $this->payMongoService->createCheckoutSession([
                'line_items' => $lineItems,
                'payment_methods' => $this->paymongoMethodsFor($order->payment_method),
                'success_url' => route('payment.success', ['order' => $order->id]),
                'cancel_url' => route('payment.cancel', ['order' => $order->id]),
                'description' => "Puffcart Order #{$order->order_number}",
                'reference_number' => $order->order_number,
                'customer_email' => $order->user?->email,
                'customer_name' => $order->user?->name,
                'customer_phone' => $order->delivery_phone,
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
                    'reference_number' => $order->order_number,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'amount' => $order->total,
                    'currency' => 'PHP',
                    'method' => $order->payment_method,
                    'payment_method' => $order->payment_method,
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
     * Payment success return page.
     *
     * This page does not mark the order as paid. PayMongo webhooks are the source of truth.
     */
    public function paymentSuccess(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Unauthorized');
        }

        $order->load('payment');
        $payment = $order->payment;
        if (!$payment || !$payment->paymongo_checkout_id) {
            return redirect()->route('home')->with('error', 'No payment found');
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

        // Keep status pending unless PayMongo has already confirmed a terminal state by webhook.
        $payment = $order->payment;
        if ($payment && !$payment->isPaid()) {
            $payment->update([
                'payment_status' => 'cancelled',
                'status' => 'failed',
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
        $signature = $request->header('Paymongo-Signature') ?: $request->header('X-Paymongo-Signature');

        if (!$signature) {
            Log::warning('PayMongo webhook received without signature');

            return response()->json(['error' => 'No signature'], 401);
        }

        // Verify webhook signature
        if (!$this->payMongoService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('PayMongo webhook signature verification failed');

            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = json_decode($payload, true);

        if (!is_array($event)) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        $this->handleWebhookEvent($event);

        return response()->json(['status' => 'received']);
    }

    /**
     * Handle different webhook event types
     */
    protected function handleWebhookEvent(array $event): void
    {
        $type = data_get($event, 'data.attributes.type') ?? data_get($event, 'type');
        $resource = data_get($event, 'data.attributes.data') ?? data_get($event, 'data');
        $resource = is_array($resource) ? $resource : [];

        Log::info('Processing PayMongo webhook event', ['type' => $type]);

        match ($type) {
            'checkout_session.payment.paid',
            'checkout_session.payment.success',
            'checkout_session.completed',
            'payment.paid',
            'payment_intent.succeeded' => $this->handlePayMongoPaid($resource, $event),

            'checkout_session.payment.failed',
            'payment.failed',
            'payment_intent.payment_failed',
            'payment_intent.payment_attempt.failed' => $this->handlePayMongoFailed($resource, $event, 'failed'),

            'checkout_session.expired',
            'source.expired' => $this->handlePayMongoFailed($resource, $event, 'expired'),

            'checkout_session.cancelled',
            'source.cancelled' => $this->handlePayMongoFailed($resource, $event, 'cancelled'),

            default => Log::warning('Unknown webhook type: ' . $type),
        };
    }

    protected function handlePayMongoPaid(array $resource, array $event): void
    {
        $payment = $this->paymentFromPayMongoResource($resource, $event);
        if (!$payment) {
            Log::warning('PayMongo paid webhook could not be matched to a payment', [
                'resource_id' => data_get($resource, 'id'),
                'event_id' => data_get($event, 'data.id'),
            ]);

            return;
        }

        $paidOrder = DB::transaction(function () use ($payment, $resource) {
            $payment->refresh();

            if ($payment->isPaid()) {
                return null;
            }

            $order = $payment->order()->lockForUpdate()->first();

            $payment->update([
                'status' => 'paid',
                'payment_status' => 'paid',
                'paymongo_payment_id' => data_get($resource, 'id', $payment->paymongo_payment_id),
                'paymongo_payment_intent_id' => data_get($resource, 'attributes.payment_intent_id', $payment->paymongo_payment_intent_id),
                'transaction_reference' => data_get($resource, 'attributes.reference_number', data_get($resource, 'id')),
                'paid_at' => now(),
            ]);

            if ($order && $order->status === 'pending') {
                $order->update(['status' => 'processing']);
            }

            if ($order) {
                OrderTracking::create([
                    'order_id' => $order->id,
                    'status' => 'processing',
                    'message' => 'Payment confirmed by PayMongo. Order is now being processed.',
                    'occurred_at' => now(),
                ]);

                AuditLog::log(
                    'payment_success',
                    "PayMongo payment successful for order #{$order->order_number}",
                    $order->user_id,
                    null,
                    'webhook'
                );
            }

            return $order;
        });

        if ($paidOrder?->user?->email) {
            try {
                $paidOrder->loadMissing('user', 'items.flavor', 'items.batteryColor', 'payment');
                Mail::to($paidOrder->user->email, $paidOrder->user->name)
                    ->send(new PaymentSuccessfulMail($paidOrder));
            } catch (\Throwable $e) {
                Log::warning('Payment success email could not be sent', [
                    'order_id' => $paidOrder->id,
                    'email' => $paidOrder->user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function handlePayMongoFailed(array $resource, array $event, string $status): void
    {
        $payment = $this->paymentFromPayMongoResource($resource, $event);
        if (!$payment) {
            Log::warning('PayMongo failed webhook could not be matched to a payment', [
                'resource_id' => data_get($resource, 'id'),
                'event_id' => data_get($event, 'data.id'),
                'status' => $status,
            ]);

            return;
        }

        if ($payment->isPaid()) {
            return;
        }

        $payment->update([
            'status' => 'failed',
            'payment_status' => $status,
            'paymongo_payment_id' => data_get($resource, 'id', $payment->paymongo_payment_id),
            'transaction_reference' => data_get($resource, 'id', $payment->transaction_reference),
        ]);

        $order = $payment->order;

        if ($order) {
            AuditLog::log(
                'payment_' . $status,
                "PayMongo payment {$status} for order #{$order->order_number}",
                $order->user_id,
                null,
                'webhook'
            );
        }
    }

    public function paymentFailed(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Unauthorized');
        }

        $order->load('payment');

        return view('payment-failed', ['order' => $order, 'payment' => $order->payment]);
    }

    private function paymentFromPayMongoResource(array $resource, array $event): ?Payment
    {
        $checkoutId = data_get($resource, 'id');

        if (is_string($checkoutId) && str_starts_with($checkoutId, 'cs_')) {
            return Payment::where('paymongo_checkout_id', $checkoutId)->first();
        }

        $paymentId = data_get($resource, 'id');
        if ($paymentId) {
            $payment = Payment::where('paymongo_payment_id', $paymentId)->first();
            if ($payment) {
                return $payment;
            }
        }

        $checkoutIds = collect([
            data_get($resource, 'attributes.checkout_session_id'),
            data_get($resource, 'attributes.checkout_session'),
            data_get($resource, 'attributes.metadata.checkout_session_id'),
            data_get($resource, 'attributes.payments.0.attributes.checkout_session_id'),
            data_get($resource, 'attributes.payments.0.id'),
            data_get($event, 'data.id'),
        ])->filter(fn ($id) => is_string($id) && str_starts_with($id, 'cs_'));

        foreach ($checkoutIds as $id) {
            $payment = Payment::where('paymongo_checkout_id', $id)->first();
            if ($payment) {
                return $payment;
            }
        }

        $reference = data_get($resource, 'attributes.reference_number')
            ?: data_get($resource, 'attributes.metadata.reference_number');

        if ($reference) {
            return Payment::where('reference_number', $reference)->first();
        }

        return null;
    }

    private function paymongoLineItems(Order $order): array
    {
        $totalCents = $this->toCentavos((float) $order->total);
        $deliveryCents = $this->toCentavos((float) $order->delivery_fee);
        $productTotalCents = max(0, $totalCents - $deliveryCents);
        $subtotalCents = max(1, $this->toCentavos((float) $order->subtotal));
        $allocatedProductCents = 0;
        $items = [];

        $orderItems = $order->items->values();

        foreach ($orderItems as $index => $item) {
            $lineSubtotalCents = $this->toCentavos((float) $item->subtotal);
            $amount = (int) floor($lineSubtotalCents * $productTotalCents / $subtotalCents);

            if ($index === $orderItems->count() - 1) {
                $amount = $productTotalCents - $allocatedProductCents;
            }

            $allocatedProductCents += $amount;

            if ($amount <= 0) {
                continue;
            }

            $items[] = [
                'currency' => 'PHP',
                'amount' => $amount,
                'description' => "Qty {$item->quantity} - {$item->product_name}",
                'quantity' => 1,
                'name' => $item->product_name,
            ];
        }

        if ($deliveryCents > 0) {
            $items[] = [
                'currency' => 'PHP',
                'amount' => $deliveryCents,
                'description' => 'Delivery fee',
                'quantity' => 1,
                'name' => 'Delivery Fee',
            ];
        }

        if (array_sum(array_column($items, 'amount')) !== $totalCents) {
            $items[] = [
                'currency' => 'PHP',
                'amount' => max(1, $totalCents - array_sum(array_column($items, 'amount'))),
                'description' => 'Order total adjustment',
                'quantity' => 1,
                'name' => 'Order Adjustment',
            ];
        }

        return $items;
    }

    private function toCentavos(float $amount): int
    {
        return (int) round($amount * 100);
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
