<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'brand', 'sku',
        'description', 'price', 'original_price', 'stock',
        'reorder_level', 'image', 'specs', 'images',
        'is_active', 'is_featured', 'badge', 'rating', 'review_count',
    ];

    protected function casts(): array
    {
        return [
            'specs'       => 'array',
            'images'      => 'array',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'price'       => 'decimal:2',
            'original_price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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

    public function isLowStock(): bool
    {
        return $this->stock <= $this->reorder_level;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->original_price || $this->original_price <= $this->price) {
            return 0;
        }
        return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'reorder_level');
    }
}
