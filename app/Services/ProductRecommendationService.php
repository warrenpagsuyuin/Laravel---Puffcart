<?php

namespace App\Services;

use App\Models\CustomerEvent;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductRecommendationService
{
    public function relatedProducts(Product $product, ?User $user = null, int $limit = 4): Collection
    {
        return $this->rankProducts(
            Product::active()
                ->where('id', '!=', $product->id)
                ->get(),
            $user,
            $product
        )->take($limit);
    }

    public function personalized(?User $user, int $limit = 8): Collection
    {
        if (!$user) {
            return $this->trending($limit);
        }

        $seenProductIds = $this->userProductIds($user);

        if ($seenProductIds->isEmpty()) {
            return $this->trending($limit);
        }

        $referenceProducts = Product::whereIn('id', $seenProductIds)->get();
        $candidateProducts = Product::active()
            ->whereNotIn('id', $seenProductIds)
            ->get();

        return $this->rankProducts($candidateProducts, $user, null, $referenceProducts)
            ->take($limit);
    }

    public function trending(int $limit = 8): Collection
    {
        return Product::active()
            ->orderByDesc(Schema::hasColumn('products', 'sales_count') ? 'sales_count' : 'stock')
            ->orderByDesc('rating')
            ->orderByDesc('is_featured')
            ->take($limit)
            ->get();
    }

    private function rankProducts(
        Collection $products,
        ?User $user = null,
        ?Product $referenceProduct = null,
        ?Collection $referenceProducts = null
    ): Collection {
        $referenceProducts ??= collect($referenceProduct ? [$referenceProduct] : []);
        $preferredCategoryIds = $referenceProducts->pluck('category_id')->filter()->unique();
        $preferredCategoryNames = $referenceProducts->pluck('category')->filter()->unique();
        $preferredTags = $referenceProducts
            ->flatMap(fn (Product $product) => $product->tags ?? [])
            ->filter()
            ->unique();

        return $products
            ->map(function (Product $product) use ($referenceProducts, $preferredCategoryIds, $preferredCategoryNames, $preferredTags, $user) {
                $score = 0.0;

                if ($preferredCategoryIds->contains($product->category_id) || $preferredCategoryNames->contains($product->category)) {
                    $score += 45;
                }

                if ($preferredTags->isNotEmpty()) {
                    $score += collect($product->tags ?? [])->intersect($preferredTags)->count() * 12;
                }

                $referenceProducts->each(function (Product $reference) use (&$score, $product) {
                    $priceDelta = abs((float) $product->price - (float) $reference->price);
                    $score += max(0, 25 - ($priceDelta / max(1, (float) $reference->price)) * 25);
                });

                $score += min(30, (int) ($product->sales_count ?? 0) * 2);
                $score += min(20, (float) ($product->rating ?? 0) * 4);
                $score += $product->stock > 0 ? 8 : -50;
                $score += $product->is_featured ? 5 : 0;

                if ($user && Schema::hasTable('cart_items')) {
                    $inCart = $user->cartItems()
                        ->where('product_id', $product->id)
                        ->exists();

                    $score += $inCart ? 20 : 0;
                }

                $product->recommendation_score = round($score, 2);

                return $product;
            })
            ->sortByDesc('recommendation_score')
            ->values();
    }

    private function userProductIds(User $user): Collection
    {
        $eventProductIds = Schema::hasTable('customer_events')
            ? CustomerEvent::where('user_id', $user->id)
                ->whereIn('event_type', [
                    CustomerEvent::PRODUCT_VIEWED,
                    CustomerEvent::CART_ADDED,
                    CustomerEvent::PURCHASED,
                ])
                ->whereNotNull('product_id')
                ->latest()
                ->limit(50)
                ->pluck('product_id')
            : collect();

        $cartProductIds = Schema::hasTable('cart_items')
            ? $user->cartItems()->pluck('product_id')
            : collect();

        $orderProductIds = Schema::hasTable('orders')
            ? $user->orders()
                ->with('items:id,order_id,product_id')
                ->latest()
                ->limit(10)
                ->get()
                ->flatMap(fn ($order) => $order->items->pluck('product_id'))
            : collect();

        return $eventProductIds
            ->merge($cartProductIds)
            ->merge($orderProductIds)
            ->filter()
            ->unique()
            ->values();
    }
}
