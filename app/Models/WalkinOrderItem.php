<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkinOrderItem extends Model
{
    protected $fillable = [
        'walkin_order_id',
        'product_id',
        'product_flavor_id',
        'battery_color_id',
        'product_name',
        'price',
        'quantity',
        'selected_flavor',
        'selected_battery_color',
        'product_type',
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

    public function flavor()
    {
        return $this->belongsTo(ProductFlavor::class, 'product_flavor_id');
    }

    public function batteryColor()
    {
        return $this->belongsTo(ProductFlavor::class, 'battery_color_id');
    }
}
