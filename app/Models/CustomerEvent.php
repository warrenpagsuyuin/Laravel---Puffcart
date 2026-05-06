<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEvent extends Model
{
    public const PRODUCT_VIEWED = 'product_viewed';
    public const CART_ADDED = 'cart_added';
    public const PURCHASED = 'purchased';
    public const SEARCHED = 'searched';

    protected $fillable = [
        'user_id',
        'product_id',
        'event_type',
        'search_query',
        'metadata',
        'session_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
