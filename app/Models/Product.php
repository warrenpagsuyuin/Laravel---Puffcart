<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'category_id',
        'category',
        'brand',
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
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query)
    {
        if (Schema::hasColumn('products', 'reorder_level')) {
            return $query->whereColumn('stock', '<=', 'reorder_level');
        }

        return $query->where('stock', '<=', 5);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->relations['category']->name ?? $this->attributes['category'] ?? 'Uncategorized';
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= ($this->reorder_level ?? 5);
    }
}
