<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkinOrder extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'payment_method',
        'mobile_number',
        'status',
        'subtotal',
        'total',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(WalkinOrderItem::class);
    }
}