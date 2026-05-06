<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'reference_number',
        'method',
        'amount',
        'status',
        'paid_at',
        'notes',
        'paymongo_checkout_id',
        'paymongo_payment_intent_id',
        'paymongo_payment_id',
        'payment_status',
        'payment_method',
        'currency',
        'transaction_reference',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is completed
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' || $this->status === 'completed';
    }
}

