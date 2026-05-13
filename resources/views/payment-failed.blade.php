@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div style="min-height:100vh;background:linear-gradient(180deg,#ffffff,#f4f8ff);display:flex;align-items:center;justify-content:center;padding:24px;">
    <div style="max-width:500px;width:100%;background:rgba(255,255,255,.9);border:1px solid #fecaca;border-radius:18px;box-shadow:0 24px 60px rgba(15,23,42,.08);padding:34px;text-align:center;">
        <div style="width:56px;height:56px;border-radius:50%;background:#fef2f2;color:#b91c1c;display:grid;place-items:center;margin:0 auto 18px;font-weight:900;">x</div>
        <h1 style="margin:0 0 10px;color:#0f172a;">Payment Failed</h1>
        <p style="color:#53657d;margin:0 0 22px;line-height:1.6;">PayMongo did not confirm this payment. You can safely try again from a new hosted checkout session.</p>

        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:16px;margin-bottom:22px;color:#991b1b;">
            <strong>{{ $order->order_number }}</strong>
            <div style="margin-top:6px;">PHP {{ number_format($order->total, 2) }}</div>
            <div style="margin-top:6px;font-size:13px;">Status: {{ ucfirst($payment->payment_status ?? 'failed') }}</div>
        </div>

        <div style="display:grid;gap:12px;">
            <a href="{{ route('payment.show', $order) }}" style="background:#0b66ff;color:#fff;border-radius:12px;padding:13px 16px;font-weight:800;">Try Payment Again</a>
            <a href="{{ route('orders.show', $order) }}" style="background:#fff;color:#111827;border:1px solid #cfd7e3;border-radius:12px;padding:13px 16px;font-weight:800;">View Order</a>
        </div>
    </div>
</div>
@endsection
