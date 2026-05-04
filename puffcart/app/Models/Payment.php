<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model {
    protected $fillable = ['order_id','reference_number','method','amount','status','paid_at','notes'];
    protected $casts = ['paid_at' => 'datetime', 'amount' => 'decimal:2'];
    public function order() { return $this->belongsTo(Order::class); }
}
