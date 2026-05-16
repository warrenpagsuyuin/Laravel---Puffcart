<?php

namespace App\Services;

use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFlavor;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductInventoryService
{
    public function __construct(
        private readonly ProductImageService $images,
        private readonly InventoryLogService $logs,
    ) {
    }

    public function create(ProductRequest $request): Product
    {
        return DB::transaction(function () use ($request): Product {
            [$data, $flavors, $batteryColors] = $this->prepareData($request);
            $data = $this->defaults($request, $data);
            $data['slug'] = $this->uniqueSlug($data['name']);
            $data['stock'] = 0;
            $data['flavor'] = $this->flavorSummary($flavors);
            $data['product_name'] = $data['name'];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->store($request->file('image'), $data['name']);
            }

            $product = Product::create($this->onlyExistingColumns('products', $data));
            $this->syncOptions($product, $flavors, ProductFlavor::TYPE_FLAVOR);
            $this->syncOptions($product, $batteryColors, ProductFlavor::TYPE_COLOR);
            $product->syncStockFromFlavors();
            $this->logs->productEvent('product_added', $product, ['name' => $product->name], 0, (int) $product->stock);
            $this->exportSeederQuietly();

            return $product;
        });
    }

    public function update(ProductRequest $request, Product $product): Product
    {
        return DB::transaction(function () use ($request, $product): Product {
            $beforeStock = (int) $product->stock;
            $oldImage = $product->image;
            [$data, $flavors, $batteryColors] = $this->prepareData($request);
            $data = $this->defaults($request, $data);
            $data['slug'] = $this->uniqueSlug($data['name'], $product);
            $data['flavor'] = $this->flavorSummary($flavors);
            $data['product_name'] = $data['name'];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->store($request->file('image'), $data['name']);
            }

            $product->update($this->onlyExistingColumns('products', $data));
            $this->syncOptions($product, $flavors, ProductFlavor::TYPE_FLAVOR);
            $this->syncOptions($product, $batteryColors, ProductFlavor::TYPE_COLOR);
            $product->syncStockFromFlavors();
            $product->refresh();

            if ($request->hasFile('image') && $oldImage !== $product->image) {
                $this->images->delete($oldImage);
            }

            $this->logs->productEvent('product_updated', $product, ['name' => $product->name], $beforeStock, (int) $product->stock);
            $this->exportSeederQuietly();

            return $product;
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product): void {
            $stock = (int) $product->stock;
            $image = $product->image;

            if ($product->orderItems()->exists() && Schema::hasColumn('products', 'is_active')) {
                $product->update(['is_active' => false]);
                $this->logs->productEvent('product_deleted', $product, ['mode' => 'deactivated'], $stock, 0);
                $this->exportSeederQuietly();
                return;
            }

            $this->logs->productEvent('product_deleted', $product, ['mode' => 'deleted', 'name' => $product->name], $stock, 0);
            $product->delete();
            $this->images->delete($image);
            $this->exportSeederQuietly();
        });
    }

    public function updateInventory(Product $product, array $data): void
    {
        DB::transaction(function () use ($product, $data): void {
            $beforeStock = (int) $product->stock;
            $product->update($this->onlyExistingColumns('products', [
                'reorder_level' => $data['reorder_level'],
            ]));

            foreach ($data['flavors'] as $flavorData) {
                $flavor = $product->flavors()->whereKey($flavorData['id'])->first();
                if (!$flavor) {
                    continue;
                }

                $before = (int) $flavor->stock;
                $flavor->update($this->onlyExistingColumns('product_flavors', [
                    'stock' => (int) $flavorData['stock'],
                    'reorder_level' => (int) $flavorData['reorder_level'],
                ]));
                $this->logs->flavorStockChanged($flavor->fresh(), $before, (int) $flavorData['stock'], 'stock_changed');
            }

            $product->syncStockFromFlavors();
            $product->refresh();
            $this->logs->productEvent('stock_changed', $product, ['source' => 'admin_inventory'], $beforeStock, (int) $product->stock);
            $this->exportSeederQuietly();
        });
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
            $data['tags'] = collect(explode(',', (string) $data['tags']))->map(fn ($tag) => trim($tag))->filter()->values()->all();
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

        $data['nicotine_type'] = $isELiquid && in_array($request->input('nicotine_type'), array_keys(Product::NICOTINE_TYPE_LABELS), true)
            ? $request->input('nicotine_type')
            : null;

        $data['nicotine_strengths'] = $isELiquid && $request->filled('nicotine_strengths')
            ? collect(explode(',', (string) $request->input('nicotine_strengths')))->map(fn ($s) => preg_replace('/[^0-9]/', '', trim($s)))->filter()->unique()->values()->all()
            : null;
        $data['volume_ml'] = $isELiquid && $request->filled('volume_ml') ? (int) $request->input('volume_ml') : null;

        return [$data, $flavors, $batteryColors];
    }

    private function syncOptions(Product $product, array $optionRows, string $optionType): void
    {
        if (!Schema::hasTable('product_flavors')) {
            return;
        }

        $defaultReorderLevel = (int) ($product->reorder_level ?? 5);
        $submittedIds = collect($optionRows)->pluck('id')->filter()->map(fn ($id) => (int) $id)->values();
        $deleteQuery = ProductFlavor::where('product_id', $product->id);

        if (Schema::hasColumn('product_flavors', 'option_type')) {
            $deleteQuery->where('option_type', $optionType);
        }

        $staleOptions = (clone $deleteQuery)
            ->when($submittedIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $submittedIds))
            ->get();

        foreach ($staleOptions as $staleOption) {
            $this->logs->flavorStockChanged($staleOption, (int) $staleOption->stock, 0, 'option_removed');
        }

        $deleteQuery->when($submittedIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $submittedIds))->delete();

        foreach ($optionRows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $payload = $this->onlyExistingColumns('product_flavors', [
                'name' => $name,
                'flavor' => $name,
                'option_type' => $optionType,
                'stock' => max(0, (int) ($row['stock'] ?? 0)),
                'reorder_level' => max(0, (int) ($row['reorder_level'] ?? $defaultReorderLevel)),
                'is_active' => true,
            ]);

            $flavor = !empty($row['id'])
                ? $product->flavors()->whereKey($row['id'])->first()
                : null;

            if (!$flavor) {
                $query = ProductFlavor::where('product_id', $product->id)->where('name', $name);
                if (Schema::hasColumn('product_flavors', 'option_type')) {
                    $query->where('option_type', $optionType);
                }
                $flavor = $query->first();
            }

            $before = $flavor ? (int) $flavor->stock : 0;
            $flavor = $flavor
                ? tap($flavor)->update($payload)
                : $product->flavors()->create($payload);

            $this->logs->flavorStockChanged($flavor->fresh(), $before, (int) $payload['stock'], $before === 0 && empty($row['id']) ? 'option_added' : 'stock_changed');
        }
    }

    private function defaults(ProductRequest $request, array $data): array
    {
        $data['reorder_level'] = $data['reorder_level'] ?? 5;
        $data['badge'] = $data['badge'] ?? 'none';
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
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
        return $name === ''
            ? null
            : Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'is_active' => true]);
    }

    private function flavorSummary(array $flavors): ?string
    {
        $summary = collect($flavors)->pluck('name')->map(fn ($name) => trim((string) $name))->filter()->unique()->implode(', ');

        return $summary !== '' ? Str::limit($summary, 250, '') : null;
    }

    private function uniqueSlug(string $name, ?Product $product = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (Product::where('slug', $slug)->when($product, fn ($query) => $query->where('id', '!=', $product->id))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        return collect($data)->filter(fn ($value, string $column) => Schema::hasColumn($table, $column))->all();
    }

    private function exportSeederQuietly(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        try {
            Artisan::call('products:export-seeder', ['--class' => 'ProductSeeder', '--json' => true]);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
