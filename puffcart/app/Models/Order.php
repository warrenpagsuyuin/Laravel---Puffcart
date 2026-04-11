<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'status', 'subtotal',
        'delivery_fee', 'discount', 'total', 'promo_code',
        'delivery_address', 'delivery_phone', 'notes', 'payment_method',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(Str::random(6));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function tracking()
    {
        return $this->hasMany(OrderTracking::class)->orderBy('occurred_at', 'desc');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed'        => 'cyan',
            'processing', 'packed', 'out_for_delivery' => 'blue',
            'pending'          => 'yellow',
            'cancelled'        => 'pink',
            default            => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'out_for_delivery' => 'Out for Delivery',
            default            => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
