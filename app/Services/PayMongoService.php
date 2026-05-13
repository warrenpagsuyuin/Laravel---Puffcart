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
        $this->publicKey = (string) config('services.paymongo.public_key', '');
        $this->secretKey = (string) config('services.paymongo.secret_key', '');
        $this->webhookSecret = (string) config('services.paymongo.webhook_secret', '');
    }

    /**
     * Create a checkout session for customer payment
     */
    public function createCheckoutSession(array $data): array
    {
        try {
            $this->ensureSecretKeyConfigured();

            $response = Http::withBasicAuth($this->secretKey, '')
                ->acceptJson()
                ->post("{$this->baseUrl}/checkout_sessions", [
                    'data' => [
                        'attributes' => [
                            'line_items' => $data['line_items'] ?? [],
                            'payment_method_types' => $data['payment_methods'] ?? ['gcash', 'card'],
                            'success_url' => $data['success_url'],
                            'cancel_url' => $data['cancel_url'],
                            'description' => $data['description'] ?? 'Puffcart Order',
                            'reference_number' => $data['reference_number'] ?? null,
                            'send_email_receipt' => true,
                            'show_description' => true,
                            'show_line_items' => true,
                            'customer' => [
                                'email' => $data['customer_email'] ?? null,
                                'name' => $data['customer_name'] ?? null,
                                'phone' => $data['customer_phone'] ?? null,
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
            $this->ensureSecretKeyConfigured();

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
            $this->ensureSecretKeyConfigured();

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
            $this->ensureSecretKeyConfigured();

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

    public function getCheckoutSession(string $checkoutId): array
    {
        try {
            $this->ensureSecretKeyConfigured();

            $response = Http::withBasicAuth($this->secretKey, '')
                ->acceptJson()
                ->get("{$this->baseUrl}/checkout_sessions/{$checkoutId}");

            if ($response->failed()) {
                Log::error('PayMongo get checkout session failed', [
                    'checkout_id' => $checkoutId,
                    'response' => $response->json(),
                ]);

                throw new Exception('Failed to retrieve checkout session');
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
        if ($this->webhookSecret === '') {
            Log::warning('PayMongo webhook secret is not configured');

            return false;
        }

        $expected = hash_hmac('sha256', $payload, $this->webhookSecret);

        foreach ($this->signatureCandidates($signature) as $candidate) {
            if (hash_equals($expected, $candidate)) {
                return true;
            }
        }

        return false;
    }

    private function signatureCandidates(string $signature): array
    {
        $signature = trim($signature);

        if ($signature === '') {
            return [];
        }

        $candidates = [$signature];

        foreach (preg_split('/[,;]/', $signature) ?: [] as $part) {
            $part = trim($part);

            if ($part === '') {
                continue;
            }

            if (str_contains($part, '=')) {
                [, $value] = array_pad(explode('=', $part, 2), 2, '');
                $value = trim($value);

                if ($value !== '') {
                    $candidates[] = $value;
                }
            }
        }

        return array_values(array_unique($candidates));
    }

    protected function ensureSecretKeyConfigured(): void
    {
        if ($this->secretKey === '') {
            throw new Exception('PayMongo secret key is not configured. Set PAYMONGO_SECRET_KEY in your .env file.');
        }
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
