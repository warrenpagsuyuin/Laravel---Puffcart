<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $fillable = [
        'product_id',
        'product_flavor_id',
        'user_id',
        'event',
        'quantity_before',
        'quantity_after',
        'quantity_delta',
        'metadata',
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'quantity_delta' => 'integer',
        'metadata' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flavor()
    {
        return $this->belongsTo(ProductFlavor::class, 'product_flavor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
