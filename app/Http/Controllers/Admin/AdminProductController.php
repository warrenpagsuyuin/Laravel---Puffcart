<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFlavor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;

        $products = Product::query()
            ->with('flavors')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");

                    if (Schema::hasColumn('products', 'sku')) {
                        $query->orWhere('sku', 'like', "%{$search}%");
                    }

                    foreach (['product_type', 'flavor', 'bundle_pods', 'bundle_battery', 'nicotine_type', 'volume_ml'] as $column) {
                        if (Schema::hasColumn('products', $column)) {
                            $query->orWhere($column, 'like', "%{$search}%");
                        }
                    }

                    if (Schema::hasTable('product_flavors')) {
                        $query->orWhereHas('flavors', fn ($flavorQuery) => $flavorQuery->where('name', 'like', "%{$search}%"));
                    }
                });
            })
            ->when($request->filled('filter'), function ($query) use ($request) {
                $this->applyCategoryFilter($query, $request->string('filter')->toString());
            })
            ->when($request->filled('nicotine_type') && Schema::hasColumn('products', 'nicotine_type'), function ($query) use ($request) {
                $query->where('nicotine_type', $request->string('nicotine_type')->toString());
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $editingProduct = null;
        $categories = $this->categories();

        return view('admin.products', compact('products', 'editingProduct', 'categories'));
    }

    public function create()
    {
        $products = Product::with('flavors')->latest()->paginate(5);
        $editingProduct = null;
        $categories = $this->categories();
        $showProductForm = true;

        return view('admin.products', compact('products', 'editingProduct', 'categories', 'showProductForm'));
    }

    public function edit(Product $product)
    {
        $products = Product::with('flavors')->latest()->paginate(5);
        $editingProduct = $product->load('flavors');
        $categories = $this->categories();

        return view('admin.products', compact('products', 'editingProduct', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        DB::transaction(function () use ($request): void {
            [$data, $flavors, $batteryColors] = $this->prepareData($request);
            $data['reorder_level'] = $data['reorder_level'] ?? 5;
            $data['badge'] = $data['badge'] ?? 'none';
            $data['is_featured'] = $request->boolean('is_featured');
            $data['is_active'] = $request->boolean('is_active');
            $data['slug'] = $this->uniqueSlug($data['name']);
            $data['stock'] = 0;
            $data['flavor'] = $this->flavorSummary($flavors);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data);
            $this->syncOptions($product, $flavors, ProductFlavor::TYPE_FLAVOR);
            $this->syncOptions($product, $batteryColors, ProductFlavor::TYPE_COLOR);
            $product->syncStockFromFlavors();
        });

        return redirect()->route('admin.products.index')->with('success', 'Product added.');
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product): void {
            [$data, $flavors, $batteryColors] = $this->prepareData($request);
            $data['reorder_level'] = $data['reorder_level'] ?? 5;
            $data['badge'] = $data['badge'] ?? 'none';
            $data['is_featured'] = $request->boolean('is_featured');
            $data['is_active'] = $request->boolean('is_active');
            $data['slug'] = $this->uniqueSlug($data['name'], $product);
            $data['flavor'] = $this->flavorSummary($flavors);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);
            $this->syncOptions($product, $flavors, ProductFlavor::TYPE_FLAVOR);
            $this->syncOptions($product, $batteryColors, ProductFlavor::TYPE_COLOR);
            $product->syncStockFromFlavors();
        });

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists() && Schema::hasColumn('products', 'is_active')) {
            $product->update(['is_active' => false]);

            return back()->with('success', 'Product removed from the active catalog.');
        }

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function prepareData(ProductRequest $request): array
    {
        $data = $request->validated();
        $flavors = $data['flavors'] ?? [];
        $batteryColors = $data['battery_colors'] ?? [];
        unset($data['flavors'], $data['battery_colors']);

        $category = $this->resolveCategory($data);

        if ($category) {
            $data['category_id'] = $category->id;
            $data['category'] = $category->name;
        }

        if (array_key_exists('tags', $data)) {
            $data['tags'] = collect(explode(',', (string) $data['tags']))
                ->map(fn ($tag) => trim($tag))
                ->filter()
                ->values()
                ->all();
        }

        $data['product_type'] = $data['product_type'] ?? Product::TYPE_OTHER;

        if (Str::contains(Str::lower((string) ($data['category'] ?? '')), ['e-liquid', 'e liquid'])) {
            $data['product_type'] = Product::TYPE_E_LIQUID;
        }

        $isELiquid = $data['product_type'] === Product::TYPE_E_LIQUID
            || Str::contains(Str::lower((string) ($data['category'] ?? '')), ['e-liquid', 'e liquid']);

        if ($data['product_type'] !== Product::TYPE_BUNDLE) {
            $data['bundle_pods'] = null;
            $data['bundle_battery'] = null;
        }

        if ($data['product_type'] === Product::TYPE_BATTERY) {
            $flavors = [];
            $data['flavor'] = null;
        }

        if (!in_array($data['product_type'], [Product::TYPE_BATTERY, Product::TYPE_BUNDLE], true)) {
            $batteryColors = [];
        }

        if (Schema::hasColumn('products', 'nicotine_type') && Schema::hasColumn('products', 'nicotine_strengths')) {
            $nicType = $request->input('nicotine_type');
            $nicStr = $request->input('nicotine_strengths');

            if ($isELiquid && $nicType) {
                $data['nicotine_type'] = in_array($nicType, ['freebase', 'saltnic'], true) ? $nicType : null;
            } else {
                $data['nicotine_type'] = null;
            }

            if ($isELiquid && $nicStr) {
                $strengths = collect(explode(',', (string) $nicStr))
                    ->map(fn ($s) => trim((string) $s))
                    ->filter()
                    ->map(fn ($s) => preg_replace('/[^0-9]/', '', $s))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $data['nicotine_strengths'] = $strengths;
            } else {
                $data['nicotine_strengths'] = null;
            }
        }

        if (Schema::hasColumn('products', 'volume_ml')) {
            $data['volume_ml'] = $isELiquid && $request->filled('volume_ml')
                ? (int) $request->input('volume_ml')
                : null;
        } else {
            unset($data['volume_ml']);
        }

        return [$data, $flavors, $batteryColors];
    }

    private function syncOptions(Product $product, array $optionRows, string $optionType): void
    {
        $defaultReorderLevel = (int) ($product->reorder_level ?? 5);
        $submittedIds = collect($optionRows)
            ->pluck('id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        // If the option_type column exists, restrict deletes/queries by it.
        if (Schema::hasColumn('product_flavors', 'option_type')) {
            ProductFlavor::where('product_id', $product->id)
                ->where('option_type', $optionType)
                ->when($submittedIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $submittedIds))
                ->when($submittedIds->isEmpty(), fn ($query) => $query)
                ->delete();
        } else {
            ProductFlavor::where('product_id', $product->id)
                ->when($submittedIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $submittedIds))
                ->delete();
        }

        foreach ($optionRows as $row) {
            $name = trim((string) ($row['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $payload = [
                'name' => $name,
                'option_type' => $optionType,
                'stock' => max(0, (int) ($row['stock'] ?? 0)),
                'reorder_level' => max(0, (int) ($row['reorder_level'] ?? $defaultReorderLevel)),
                'is_active' => true,
            ];

            $flavor = null;

            if (!empty($row['id'])) {
                $flavorQuery = $product->flavors()->whereKey($row['id']);
                if (Schema::hasColumn('product_flavors', 'option_type')) {
                    $flavorQuery = $flavorQuery->where('option_type', $optionType);
                }

                $flavor = $flavorQuery->first();
            }

            // If the DB doesn't have option_type, strip it from payload to avoid SQL errors.
            if (!Schema::hasColumn('product_flavors', 'option_type')) {
                unset($payload['option_type']);
            }

            if ($flavor) {
                // If another option with the same name exists for this product, merge into it
                $conflictQuery = ProductFlavor::where('product_id', $product->id)
                    ->where('name', $name)
                    ->when(Schema::hasColumn('product_flavors', 'option_type'), fn ($q) => $q->where('option_type', $optionType))
                    ->where('id', '!=', $flavor->id);

                $conflict = $conflictQuery->first();

                if ($conflict) {
                    // Apply payload to the existing conflicting row (admin input wins),
                    // then remove the stale row to keep the unique constraint satisfied.
                    $conflict->update($payload);
                    $flavor->delete();
                    continue;
                }

                $flavor->update($payload);
            } else {
                // No id supplied — check for an existing row with same name first
                $existingQuery = ProductFlavor::where('product_id', $product->id)
                    ->where('name', $name)
                    ->when(Schema::hasColumn('product_flavors', 'option_type'), fn ($q) => $q->where('option_type', $optionType));

                $existing = $existingQuery->first();

                if ($existing) {
                    $existing->update($payload);
                    $flavor = $existing;
                } else {
                    $flavor = $product->flavors()->create($payload);
                }
            }
        }
    }

    private function flavorSummary(array $flavors): ?string
    {
        $summary = collect($flavors)
            ->pluck('name')
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique()
            ->implode(', ');

        return $summary !== '' ? Str::limit($summary, 250, '') : null;
    }

    private function resolveCategory(array $data): ?Category
    {
        if (!Schema::hasTable('categories')) {
            return null;
        }

        if (!empty($data['category_id'])) {
            return Category::find($data['category_id']);
        }

        $name = trim((string) ($data['category'] ?? ''));

        if ($name === '') {
            return null;
        }

        return Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'is_active' => true]
        );
    }

    private function applyCategoryFilter($query, string $filter): void
    {
        $filter = Str::slug($filter);

        if ($filter === '') {
            return;
        }

        $labels = [
            'accessories' => 'Accessories',
            'coils-pods' => 'Coils & Pods',
            'devices' => 'Devices',
            'e-liquids' => 'E-liquids',
        ];

        $query->where(function ($query) use ($filter, $labels) {
            if (Schema::hasTable('categories')) {
                $query->whereHas('categoryModel', fn ($categoryQuery) => $categoryQuery->where('slug', $filter));
            }

            if (Schema::hasColumn('products', 'category')) {
                $query->orWhere('category', $labels[$filter] ?? str_replace('-', ' ', $filter));
            }
        });
    }

    private function uniqueSlug(string $name, ?Product $product = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (
            Product::where('slug', $slug)
                ->when($product, fn ($query) => $query->where('id', '!=', $product->id))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function categories()
    {
        if (Schema::hasTable('categories')) {
            return Category::active()->orderBy('name')->get();
        }

        return Product::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->map(fn (Product $product) => (object) ['id' => null, 'name' => $product->category]);
    }
}
