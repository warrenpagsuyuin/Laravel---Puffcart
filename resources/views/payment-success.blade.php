@extends('layouts.app')

@section('title', 'Payment Confirmed')

@section('content')
<div style="min-height:100vh;background:#f6f8fb;display:flex;align-items:center;justify-content:center;padding:24px;">
    <div style="max-width:460px;width:100%;background:#fff;border:1px solid #dfe5ef;border-radius:8px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:32px;text-align:center;">
        <h1 style="margin:0 0 10px;color:#111827;">Payment Confirmed</h1>
        <p style="color:#6b7280;margin:0 0 22px;">Your payment was received. Your order can now proceed to processing and tracking.</p>

        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin-bottom:22px;color:#166534;">
            <strong>{{ $order->order_number }}</strong>
            <div style="margin-top:6px;">PHP {{ number_format($order->total, 2) }}</div>
        </div>

        <div style="display:grid;gap:12px;">
            <a href="{{ route('orders.show', $order) }}" style="background:#0b66ff;color:#fff;border-radius:8px;padding:13px 16px;font-weight:800;">View Order</a>
            <a href="{{ route('orders.track', $order) }}" style="background:#fff;color:#111827;border:1px solid #cfd7e3;border-radius:8px;padding:13px 16px;font-weight:800;">Track Order</a>
        </div>
    </div>
</div>
@endsection
