<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkinOrderItem extends Model
{
    protected $fillable = [
        'walkin_order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(WalkinOrder::class, 'walkin_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}