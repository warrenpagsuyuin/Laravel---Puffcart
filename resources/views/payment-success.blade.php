@extends('layouts.app')

@section('title', $payment?->isPaid() ? 'Payment Confirmed' : 'Payment Processing')

@section('content')
@php
    $isPaid = $payment?->isPaid();
@endphp

<div style="min-height:100vh;background:linear-gradient(180deg,#ffffff,#f4f8ff);display:flex;align-items:center;justify-content:center;padding:24px;">
    <div style="max-width:500px;width:100%;background:rgba(255,255,255,.9);border:1px solid #dbeafe;border-radius:18px;box-shadow:0 24px 60px rgba(11,99,246,.12);padding:34px;text-align:center;">
        <div style="width:56px;height:56px;border-radius:50%;background:{{ $isPaid ? '#dcfce7' : '#eaf2ff' }};color:{{ $isPaid ? '#166534' : '#0b63f6' }};display:grid;place-items:center;margin:0 auto 18px;font-weight:900;">
            {{ $isPaid ? '✓' : '...' }}
        </div>

        <h1 style="margin:0 0 10px;color:#0f172a;">{{ $isPaid ? 'Payment Confirmed' : 'Payment Processing' }}</h1>
        <p style="color:#53657d;margin:0 0 22px;line-height:1.6;">
            {{ $isPaid
                ? 'PayMongo confirmed your payment by webhook. Your order can now proceed to processing and tracking.'
                : 'You returned from PayMongo successfully. We are waiting for the secure PayMongo webhook before marking this order paid.' }}
        </p>

        <div style="background:#f8fafc;border:1px solid #dbe4ef;border-radius:14px;padding:16px;margin-bottom:22px;color:#0f172a;">
            <strong>{{ $order->order_number }}</strong>
            <div style="margin-top:6px;">PHP {{ number_format($order->total, 2) }}</div>
            <div style="margin-top:6px;color:#64748b;font-size:13px;">Status: {{ ucfirst($payment->payment_status ?? 'pending') }}</div>
        </div>

        <div style="display:grid;gap:12px;">
            <a href="{{ route('orders.show', $order) }}" style="background:#0b66ff;color:#fff;border-radius:12px;padding:13px 16px;font-weight:800;">View Order</a>
            @if($isPaid)
                <a href="{{ route('orders.track', $order) }}" style="background:#fff;color:#111827;border:1px solid #cfd7e3;border-radius:12px;padding:13px 16px;font-weight:800;">Track Order</a>
            @else
                <a href="{{ route('payment.show', $order) }}" style="background:#fff;color:#111827;border:1px solid #cfd7e3;border-radius:12px;padding:13px 16px;font-weight:800;">Refresh Payment Status</a>
            @endif
        </div>
    </div>
</div>
@endsection
