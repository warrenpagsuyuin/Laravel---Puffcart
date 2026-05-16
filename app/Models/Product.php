<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_name',
        'slug',
        'sku',
        'category_id',
        'category',
        'brand',
        'product_type',
        'flavor',
        'bundle_pods',
        'bundle_battery',
        'price',
        'original_price',
        'description',
        'tags',
        'stock',
        'image',
        'badge',
        'reorder_level',
        'is_featured',
        'is_active',
        'rating',
        'sales_count',
        'views_count',
        'nicotine_type',
        'nicotine_strengths',
        'volume_ml',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'tags' => 'array',
        'stock' => 'integer',
        'reorder_level' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:1',
        'sales_count' => 'integer',
        'views_count' => 'integer',
        'nicotine_strengths' => 'array',
        'volume_ml' => 'integer',
    ];

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;

        if (Schema::hasColumn('products', 'product_name')) {
            $this->attributes['product_name'] = $value;
        }
    }

    public const TYPE_PODS = 'pods';
    public const TYPE_BATTERY = 'battery';
    public const TYPE_BUNDLE = 'bundle';
    public const TYPE_E_LIQUID = 'e_liquid';
    public const TYPE_OTHER = 'other';

    public const NICOTINE_TYPE_LABELS = [
        'freebase' => 'Freebase',
        'saltnic' => 'Salt Nic',
    ];

    public const TYPE_LABELS = [
        self::TYPE_PODS => 'Pods',
        self::TYPE_BATTERY => 'Battery',
        self::TYPE_BUNDLE => 'Pods + Battery Bundle',
        self::TYPE_E_LIQUID => 'E-Liquid',
        self::TYPE_OTHER => 'Other',
    ];

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categoryRelation()
    {
        return $this->categoryModel();
    }

    public function categoryEntity()
    {
        return $this->categoryModel();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function flavors()
    {
        return $this->hasMany(ProductFlavor::class)->orderBy('name');
    }

    public function flavorOptions()
    {
        if (Schema::hasColumn('product_flavors', 'option_type')) {
            return $this->hasMany(ProductFlavor::class)->flavors()->orderBy('name');
        }

        return $this->hasMany(ProductFlavor::class)->orderBy('name');
    }

    public function colorOptions()
    {
        if (Schema::hasColumn('product_flavors', 'option_type')) {
            return $this->hasMany(ProductFlavor::class)->colors()->orderBy('name');
        }

        return $this->hasMany(ProductFlavor::class)->orderBy('name');
    }

    public function activeFlavors()
    {
        return $this->hasMany(ProductFlavor::class)->active()->orderBy('name');
    }

    public function availableFlavors()
    {
        return $this->hasMany(ProductFlavor::class)->inStock()->orderBy('name');
    }

    public function availableFlavorOptions()
    {
        if (Schema::hasColumn('product_flavors', 'option_type')) {
            return $this->hasMany(ProductFlavor::class)->flavors()->inStock()->orderBy('name');
        }

        return $this->hasMany(ProductFlavor::class)->inStock()->orderBy('name');
    }

    public function availableColorOptions()
    {
        if (Schema::hasColumn('product_flavors', 'option_type')) {
            return $this->hasMany(ProductFlavor::class)->colors()->inStock()->orderBy('name');
        }

        return $this->hasMany(ProductFlavor::class)->inStock()->orderBy('name');
    }

    public function scopeActive($query)
    {
        if (Schema::hasColumn('products', 'is_active')) {
            return $query->where('is_active', true);
        }

        return $query;
    }

    public function scopeFeatured($query)
    {
        if (Schema::hasColumn('products', 'is_featured')) {
            return $query->where('is_featured', true);
        }

        return $query;
    }

    public function scopeInStock($query)
    {
        if (Schema::hasTable('product_flavors')) {
            return $query->whereHas('availableFlavors');
        }

        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query)
    {
        if (Schema::hasTable('product_flavors')) {
            return $query->where(function ($query) {
                $query->whereHas('flavors', fn ($flavorQuery) => $flavorQuery->lowStock());

                if (Schema::hasColumn('products', 'reorder_level')) {
                    $query->orWhereColumn('stock', '<=', 'reorder_level');
                } else {
                    $query->orWhere('stock', '<=', 5);
                }
            });
        }

        if (Schema::hasColumn('products', 'reorder_level')) {
            return $query->whereColumn('stock', '<=', 'reorder_level');
        }

        return $query->where('stock', '<=', 5);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        if (!Storage::disk('public')->exists($this->image)) {
            return null;
        }

        return asset('storage/' . $this->image);
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->relations['category']->name ?? $this->attributes['category'] ?? 'Uncategorized';
    }

    public function getIsLowStockAttribute(): bool
    {
        if ($this->relationLoaded('flavors') && $this->flavors->isNotEmpty()) {
            return $this->flavors->contains(fn (ProductFlavor $flavor) => $flavor->is_active && $flavor->is_low_stock);
        }

        if (Schema::hasTable('product_flavors') && $this->exists && $this->flavors()->exists()) {
            return $this->flavors()->lowStock()->exists();
        }

        return $this->stock <= ($this->reorder_level ?? 5);
    }

    public function getProductTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->product_type ?: self::TYPE_OTHER] ?? 'Other';
    }

    public function getNicotineTypeLabelAttribute(): ?string
    {
        return self::NICOTINE_TYPE_LABELS[$this->nicotine_type] ?? null;
    }

    public function getNicotineProfileAttribute(): ?string
    {
        if (!$this->nicotine_type_label) {
            return null;
        }

        $strengths = collect($this->nicotine_strengths ?? [])
            ->filter()
            ->map(fn ($strength) => "{$strength}mg")
            ->implode(', ');

        return $strengths ? "{$this->nicotine_type_label} ({$strengths})" : $this->nicotine_type_label;
    }

    public function getVolumeLabelAttribute(): ?string
    {
        return $this->volume_ml ? "{$this->volume_ml}ml" : null;
    }

    public function getRequiresFlavorAttribute(): bool
    {
        return true;
    }

    public function getDisplayFlavorAttribute(): ?string
    {
        return $this->flavor_summary ?: ($this->flavor ?: null);
    }

    public function getFlavorSummaryAttribute(): ?string
    {
        $flavors = $this->optionCollection(ProductFlavor::TYPE_FLAVOR, includeOutOfStock: false);

        if ($flavors->isEmpty()) {
            $flavors = $this->optionCollection(ProductFlavor::TYPE_FLAVOR, includeOutOfStock: true);
        }

        return $flavors->pluck('name')->filter()->unique()->implode(', ') ?: null;
    }

    public function getColorSummaryAttribute(): ?string
    {
        $colors = $this->optionCollection(ProductFlavor::TYPE_COLOR, includeOutOfStock: false);

        if ($colors->isEmpty()) {
            $colors = $this->optionCollection(ProductFlavor::TYPE_COLOR, includeOutOfStock: true);
        }

        return $colors->pluck('name')->filter()->unique()->implode(', ') ?: null;
    }

    public function getAvailableStockAttribute(): int
    {
        $flavors = $this->optionCollection(ProductFlavor::TYPE_FLAVOR, includeOutOfStock: true)
            ->filter(fn (ProductFlavor $option) => $option->is_active && $option->stock > 0);
        $colors = $this->optionCollection(ProductFlavor::TYPE_COLOR, includeOutOfStock: true)
            ->filter(fn (ProductFlavor $option) => $option->is_active && $option->stock > 0);

        if ($this->product_type === self::TYPE_BUNDLE && $flavors->isNotEmpty() && $colors->isNotEmpty()) {
            return min((int) $flavors->sum('stock'), (int) $colors->sum('stock'));
        }

        if ($this->product_type === self::TYPE_BATTERY && $colors->isNotEmpty()) {
            return (int) $colors->sum('stock');
        }

        if ($flavors->isNotEmpty()) {
            return (int) $flavors->sum('stock');
        }

        if ($colors->isNotEmpty()) {
            return (int) $colors->sum('stock');
        }

        return max(0, (int) $this->stock);
    }

    public function getBundleDescriptionAttribute(): ?string
    {
        if ($this->product_type !== self::TYPE_BUNDLE) {
            return null;
        }

        return collect([
            $this->bundle_pods ? "Pods: {$this->bundle_pods}" : null,
            $this->flavor ? "Flavor: {$this->flavor}" : null,
            $this->bundle_battery ? "Battery: {$this->bundle_battery}" : null,
        ])->filter()->implode(' / ') ?: null;
    }

    public function syncStockFromFlavors(): void
    {
        if (!Schema::hasTable('product_flavors') || !$this->exists) {
            return;
        }

        $flavorStock = (int) $this->flavorOptions()->active()->sum('stock');
        $colorStock = (int) $this->colorOptions()->active()->sum('stock');
        $stock = match ($this->product_type) {
            self::TYPE_BATTERY => $colorStock,
            self::TYPE_BUNDLE => $flavorStock > 0 && $colorStock > 0 ? min($flavorStock, $colorStock) : max($flavorStock, $colorStock),
            default => $flavorStock > 0 ? $flavorStock : $colorStock,
        };

        $this->forceFill(['stock' => $stock])->save();
        $this->setAttribute('stock', $stock);
        $this->unsetRelation('flavors');
        $this->unsetRelation('activeFlavors');
        $this->unsetRelation('availableFlavors');
        $this->unsetRelation('flavorOptions');
        $this->unsetRelation('colorOptions');
        $this->unsetRelation('availableFlavorOptions');
        $this->unsetRelation('availableColorOptions');
    }

    private function flavorCollection(bool $includeOutOfStock)
    {
        if ($this->relationLoaded('availableFlavors') && !$includeOutOfStock) {
            return $this->availableFlavors;
        }

        if ($this->relationLoaded('activeFlavors')) {
            return $includeOutOfStock
                ? $this->activeFlavors
                : $this->activeFlavors->where('stock', '>', 0)->values();
        }

        if ($this->relationLoaded('flavors')) {
            return $this->flavors
                ->filter(fn (ProductFlavor $flavor) => $flavor->is_active && ($includeOutOfStock || $flavor->stock > 0))
                ->values();
        }

        if (!Schema::hasTable('product_flavors') || !$this->exists) {
            return collect();
        }

        $query = $includeOutOfStock ? $this->activeFlavors() : $this->availableFlavors();

        return $query->get();
    }

    private function optionCollection(string $type, bool $includeOutOfStock)
    {
        $loadedRelation = $type === ProductFlavor::TYPE_COLOR ? 'availableColorOptions' : 'availableFlavorOptions';
        $allRelation = $type === ProductFlavor::TYPE_COLOR ? 'colorOptions' : 'flavorOptions';

        if ($this->relationLoaded($loadedRelation) && !$includeOutOfStock) {
            return $this->{$loadedRelation};
        }

        if ($this->relationLoaded($allRelation)) {
            return $this->{$allRelation}
                ->filter(fn (ProductFlavor $option) => $option->is_active && ($includeOutOfStock || $option->stock > 0))
                ->values();
        }

        if ($this->relationLoaded('flavors')) {
            return $this->flavors
                ->filter(fn (ProductFlavor $option) => $option->option_type === $type && $option->is_active && ($includeOutOfStock || $option->stock > 0))
                ->values();
        }

        if (!Schema::hasTable('product_flavors') || !$this->exists || !Schema::hasColumn('product_flavors', 'option_type')) {
            return collect();
        }

        $query = $type === ProductFlavor::TYPE_COLOR ? $this->colorOptions() : $this->flavorOptions();
        $query->active();

        if (!$includeOutOfStock) {
            $query->where('stock', '>', 0);
        }

        return $query->get();
    }
}
