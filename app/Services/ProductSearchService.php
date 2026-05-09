<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductSearchService
{
    public function search(Request $request, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::query()
            ->active()
            ->with('availableFlavorOptions', 'availableColorOptions')
            ->when(Schema::hasColumn('products', 'category_id'), fn ($query) => $query->with('category'))
            ->when(Schema::hasTable('order_items'), fn ($query) => $query->withSum('orderItems as units_sold', 'quantity'));

        $this->applyFilters($query, $request);
        $this->applyRanking($query, $request);

        return $query->paginate($perPage)->withQueryString();
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('category')) {
            $category = $request->string('category')->toString();

            if (Schema::hasTable('categories') && Schema::hasColumn('products', 'category_id')) {
                $query->whereHas('category', fn ($query) => $query->where('slug', $category));
            } else {
                $query->where('category', $category);
            }
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->string('brand')->toString());
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }
    }

    private function applyRanking($query, Request $request): void
    {
        $search = trim((string) $request->get('search', ''));

        if ($search !== '') {
            $contains = "%{$search}%";
            $startsWith = "{$search}%";

            $query->where(function ($query) use ($contains) {
                $query->where('name', 'like', $contains)
                    ->orWhere('brand', 'like', $contains)
                    ->orWhere('category', 'like', $contains)
                    ->orWhere('description', 'like', $contains);

                foreach (['product_type', 'flavor', 'bundle_pods', 'bundle_battery'] as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $query->orWhere($column, 'like', $contains);
                    }
                }

                if (Schema::hasTable('product_flavors')) {
                    $query->orWhereHas('flavors', fn ($flavorQuery) => $flavorQuery->where('name', 'like', $contains));
                }
            });

            $scoreSql = '(CASE WHEN name = ? THEN 100 ELSE 0 END
                + CASE WHEN name LIKE ? THEN 70 ELSE 0 END
                + CASE WHEN brand LIKE ? THEN 30 ELSE 0 END
                + CASE WHEN category LIKE ? THEN 25 ELSE 0 END';
            $scoreBindings = [$search, $startsWith, $contains, $contains];

            if (Schema::hasColumn('products', 'flavor')) {
                $scoreSql .= ' + CASE WHEN flavor LIKE ? THEN 25 ELSE 0 END';
                $scoreBindings[] = $contains;
            }

            if (Schema::hasColumn('products', 'product_type')) {
                $scoreSql .= ' + CASE WHEN product_type LIKE ? THEN 20 ELSE 0 END';
                $scoreBindings[] = $contains;
            }

            $scoreSql .= ' + CASE WHEN description LIKE ? THEN 10 ELSE 0 END
                + CASE WHEN stock > 0 THEN 10 ELSE 0 END
                + COALESCE(sales_count, 0) * 2
                + COALESCE(rating, 0) * 5) AS search_score';
            $scoreBindings[] = $contains;

            $query->select('products.*')
                ->selectRaw($scoreSql, $scoreBindings)
                ->orderByDesc('search_score');
        } else {
            $sort = $request->get('sort', 'recommended');

            match ($sort) {
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest' => $query->latest(),
                'rating' => $query->orderByDesc('rating'),
                'popular' => $query->orderByDesc('sales_count'),
                default => $query
                    ->orderByDesc('is_featured')
                    ->orderByDesc('sales_count')
                    ->orderByDesc('rating'),
            };
        }

        $query
            ->orderByRaw('CASE WHEN stock > 0 THEN 1 ELSE 0 END DESC')
            ->orderByDesc('stock')
            ->orderBy('name');
    }
}
