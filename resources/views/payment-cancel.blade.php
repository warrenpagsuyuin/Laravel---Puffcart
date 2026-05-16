@extends('layouts.app')

@section('title', 'Payment Cancelled')

@section('content')
<div style="min-height:100vh;background:radial-gradient(circle at 10% 8%,rgba(11,99,246,.08),transparent 28%),linear-gradient(180deg,#fff 0%,#f8fafc 45%,#fff 100%);display:flex;align-items:center;justify-content:center;padding:24px;">
    <div style="max-width:560px;width:100%;background:rgba(255,255,255,.94);border:1px solid #e4ecf7;border-radius:18px;box-shadow:0 22px 44px rgba(15,23,42,.08);padding:28px;">
        <div style="background:linear-gradient(135deg,#0b63f6 0%,#0f3a8a 100%);border-radius:16px;color:#fff;padding:28px;margin-bottom:18px;">
            <div style="width:54px;height:54px;border-radius:16px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.34);display:grid;place-items:center;font-weight:900;margin-bottom:18px;">!</div>
            <h1 style="color:#fff;margin:0 0 10px;font-size:32px;">Payment Cancelled</h1>
            <p style="color:rgba(255,255,255,.88);line-height:1.65;margin:0;">Your order is saved, but it cannot proceed until payment is completed and confirmed by PayMongo.</p>
        </div>

        <div style="background:#fff;border:1px solid #dde8f7;border-radius:12px;padding:16px;margin-bottom:18px;">
            <div style="color:#6b7280;font-size:12px;font-weight:800;text-transform:uppercase;margin-bottom:7px;">Order</div>
            <strong style="display:block;color:#0f172a;font-size:18px;margin-bottom:12px;">{{ $order->order_number }}</strong>
            <div style="display:flex;justify-content:space-between;border-top:1px solid #e5e7eb;padding-top:12px;">
                <span style="color:#6b7280;">Total</span>
                <b style="color:#0b63f6;">PHP {{ number_format($order->total, 2) }}</b>
            </div>
        </div>

        <div style="display:grid;gap:12px;">
            <a href="{{ route('payment.show', $order) }}" style="background:#0b63f6;color:#fff;border-radius:8px;padding:13px 16px;font-weight:800;text-align:center;">Try Payment Again</a>
            <a href="{{ route('orders.show', $order) }}" style="background:#fff;color:#0b63f6;border:1px solid #d6e0ee;border-radius:8px;padding:13px 16px;font-weight:800;text-align:center;">View Order</a>
        </div>
    </div>
</div>
@endsection
