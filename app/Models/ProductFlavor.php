<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFlavor extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'option_type',
        'stock',
        'reorder_level',
        'is_active',
    ];

    public const TYPE_FLAVOR = 'flavor';
    public const TYPE_COLOR = 'color';

    protected $casts = [
        'stock' => 'integer',
        'reorder_level' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->active()->where('stock', '>', 0);
    }

    public function scopeFlavors($query)
    {
        return $query->where('option_type', self::TYPE_FLAVOR);
    }

    public function scopeColors($query)
    {
        return $query->where('option_type', self::TYPE_COLOR);
    }

    public function scopeLowStock($query)
    {
        return $query->active()->whereColumn('stock', '<=', 'reorder_level');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->reorder_level;
    }
}
