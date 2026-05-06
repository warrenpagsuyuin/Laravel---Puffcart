<?php

namespace App\Services;

use App\Models\CustomerEvent;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerBehaviorService
{
    public function productViewed(Product $product, Request $request): void
    {
        $this->record(CustomerEvent::PRODUCT_VIEWED, $request, $product);

        if (Schema::hasColumn('products', 'views_count')) {
            $product->increment('views_count');
        }
    }

    public function cartAdded(Product $product, int $quantity, Request $request): void
    {
        $this->record(CustomerEvent::CART_ADDED, $request, $product, [
            'quantity' => $quantity,
        ]);
    }

    public function purchased(User $user, Product $product, int $quantity, float $subtotal, Request $request): void
    {
        $this->record(CustomerEvent::PURCHASED, $request, $product, [
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ], $user);
    }

    public function searched(string $query, Request $request, int $resultCount = 0): void
    {
        $this->record(CustomerEvent::SEARCHED, $request, null, [
            'result_count' => $resultCount,
        ], null, $query);
    }

    private function record(
        string $eventType,
        Request $request,
        ?Product $product = null,
        array $metadata = [],
        ?User $user = null,
        ?string $searchQuery = null
    ): void {
        if (!Schema::hasTable('customer_events')) {
            return;
        }

        CustomerEvent::create([
            'user_id' => $user?->id ?? $request->user()?->id,
            'product_id' => $product?->id,
            'event_type' => $eventType,
            'search_query' => $searchQuery,
            'metadata' => $metadata ?: null,
            'session_id' => $request->session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
