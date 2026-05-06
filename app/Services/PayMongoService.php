<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected string $baseUrl = 'https://api.paymongo.com/v1';
    protected string $publicKey;
    protected string $secretKey;
    protected string $webhookSecret;

    public function __construct()
    {
        $this->publicKey = config('services.paymongo.public_key');
        $this->secretKey = config('services.paymongo.secret_key');
        $this->webhookSecret = config('services.paymongo.webhook_secret');
    }

    /**
     * Create a checkout session for customer payment
     */
    public function createCheckoutSession(array $data): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/checkout_sessions", [
                    'data' => [
                        'attributes' => [
                            'line_items' => $data['line_items'] ?? [],
                            'payment_method_types' => $data['payment_methods'] ?? ['gcash', 'card'],
                            'success_url' => $data['success_url'],
                            'cancel_url' => $data['cancel_url'],
                            'description' => $data['description'] ?? 'Puffcart Order',
                            'customer' => [
                                'email' => $data['customer_email'] ?? null,
                                'name' => $data['customer_name'] ?? null,
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('PayMongo checkout session creation failed', [
                    'response' => $response->json(),
                ]);

                throw new Exception('Failed to create checkout session');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('PayMongo service error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent(array $data): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payment_intents", [
                    'data' => [
                        'attributes' => [
                            'amount' => (int) ($data['amount'] * 100), // Convert to centavos
                            'currency' => $data['currency'] ?? 'PHP',
                            'payment_method_allowed' => $data['payment_methods'] ?? ['gcash', 'card'],
                            'statement_descriptor' => $data['description'] ?? 'Puffcart',
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('PayMongo payment intent creation failed', [
                    'response' => $response->json(),
                ]);

                throw new Exception('Failed to create payment intent');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('PayMongo service error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get payment intent details
     */
    public function getPaymentIntent(string $intentId): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/payment_intents/{$intentId}");

            if ($response->failed()) {
                Log::error('PayMongo get payment intent failed', [
                    'response' => $response->json(),
                ]);

                throw new Exception('Failed to retrieve payment intent');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('PayMongo service error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get payment details
     */
    public function getPayment(string $paymentId): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/payments/{$paymentId}");

            if ($response->failed()) {
                Log::error('PayMongo get payment failed', [
                    'response' => $response->json(),
                ]);

                throw new Exception('Failed to retrieve payment');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('PayMongo service error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $hash = hash_hmac('sha256', $payload, $this->webhookSecret);

        return hash_equals($hash, $signature);
    }

    /**
     * Handle webhook event
     */
    public function handleWebhookEvent(array $event): void
    {
        $type = $event['type'] ?? null;
        $data = $event['data'] ?? [];

        Log::info('PayMongo webhook event', ['type' => $type, 'data' => $data]);

        match ($type) {
            'payment_intent.success' => $this->handlePaymentIntentSuccess($data),
            'payment_intent.failed' => $this->handlePaymentIntentFailed($data),
            'payment_intent.payment_attempt.failed' => $this->handlePaymentAttemptFailed($data),
            'checkout_session.payment.success' => $this->handleCheckoutPaymentSuccess($data),
            'checkout_session.payment.failed' => $this->handleCheckoutPaymentFailed($data),
            default => Log::warning('Unknown PayMongo webhook type: ' . $type),
        };
    }

    protected function handlePaymentIntentSuccess(array $data): void
    {
        // Handle successful payment intent
        Log::info('Payment intent successful', $data);
    }

    protected function handlePaymentIntentFailed(array $data): void
    {
        // Handle failed payment intent
        Log::warning('Payment intent failed', $data);
    }

    protected function handlePaymentAttemptFailed(array $data): void
    {
        // Handle failed payment attempt
        Log::warning('Payment attempt failed', $data);
    }

    protected function handleCheckoutPaymentSuccess(array $data): void
    {
        // Handle checkout session payment success
        Log::info('Checkout payment successful', $data);
    }

    protected function handleCheckoutPaymentFailed(array $data): void
    {
        // Handle checkout session payment failure
        Log::warning('Checkout payment failed', $data);
    }
}
