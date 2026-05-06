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
        'sku',
        'category',
        'brand',
        'price',
        'original_price',
        'description',
        'stock',
        'image',
        'badge',
        'reorder_level',
        'is_featured',
        'is_active',
        'rating',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'stock' => 'integer',
        'reorder_level' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:1',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
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
}
