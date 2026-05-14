@extends('layouts.app')

@section('title', $payment?->isPaid() ? 'Payment Confirmed' : 'Payment Processing')

@section('content')
@php
    $isPaid = $payment?->isPaid();
@endphp

<style>
    body {
        background: #F8FAFC;
    }

    .result-page {
        background:
            radial-gradient(circle at 10% 8%, rgba(11, 99, 246, 0.08), transparent 28%),
            linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 44%, #FFFFFF 100%);
        min-height: 100vh;
        overflow: hidden;
        position: relative;
    }

    .vapor-bg {
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        position: absolute;
        z-index: 0;
    }

    .vapor-bg::before,
    .vapor-bg::after,
    .vapor-layer {
        animation: vaporDrift 18s ease-in-out infinite alternate;
        border-radius: 999px;
        content: "";
        filter: blur(28px);
        height: 24vw;
        min-height: 170px;
        min-width: 420px;
        opacity: 0.5;
        position: absolute;
        width: 58vw;
    }

    .vapor-bg::before {
        background:
            radial-gradient(circle at 18% 50%, rgba(255, 255, 255, 0.92), transparent 34%),
            radial-gradient(circle at 56% 46%, rgba(207, 231, 255, 0.78), transparent 36%),
            radial-gradient(circle at 86% 58%, rgba(235, 246, 255, 0.72), transparent 32%);
        left: -18%;
        top: 8%;
    }

    .vapor-bg::after {
        animation-delay: -6s;
        animation-duration: 22s;
        background:
            radial-gradient(circle at 18% 56%, rgba(223, 241, 255, 0.68), transparent 34%),
            radial-gradient(circle at 52% 48%, rgba(255, 255, 255, 0.82), transparent 38%),
            radial-gradient(circle at 86% 50%, rgba(178, 214, 255, 0.46), transparent 34%);
        bottom: 4%;
        right: -20%;
    }

    .vapor-layer {
        animation-delay: -10s;
        animation-duration: 20s;
        background:
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.72), transparent 34%),
            radial-gradient(circle at 60% 48%, rgba(191, 224, 255, 0.58), transparent 38%),
            radial-gradient(circle at 90% 52%, rgba(234, 242, 255, 0.58), transparent 32%);
        height: 15vw;
        min-height: 120px;
        min-width: 300px;
        right: 15%;
        top: 16%;
        width: 38vw;
    }

    @keyframes vaporDrift {
        0% { transform: translate3d(-16px, 8px, 0) scale(1); }
        100% { transform: translate3d(42px, -18px, 0) scale(1.08); }
    }

    .store-nav {
        align-items: center;
        backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.94);
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        min-height: 72px;
        padding: 0 48px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .logo {
        color: #0B63F6;
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
    }

    .nav-links {
        display: flex;
        gap: 8px;
    }

    .nav-links a {
        border-radius: var(--radius);
        color: #334155;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 14px;
    }

    .nav-links a:hover {
        background: #EAF2FF;
        color: #0B63F6;
    }

    .result-shell {
        margin: 0 auto;
        max-width: 1040px;
        padding: 64px 24px 80px;
        position: relative;
        z-index: 1;
    }

    .result-panel {
        background:
            radial-gradient(circle at 92% 10%, rgba(11, 99, 246, 0.1), transparent 24%),
            rgba(255, 255, 255, 0.94);
        border: 1px solid #E4ECF7;
        border-radius: var(--radius-lg);
        box-shadow: 0 22px 44px rgba(15, 23, 42, 0.08);
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 28px;
        padding: 34px;
    }

    .result-hero {
        background:
            radial-gradient(circle at 86% 18%, rgba(255, 255, 255, 0.2), transparent 26%),
            linear-gradient(135deg, #0B63F6 0%, #0F3A8A 100%);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 42px rgba(11, 99, 246, 0.18);
        color: #FFFFFF;
        padding: 34px;
    }

    .status-mark {
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.34);
        border-radius: 18px;
        display: grid;
        font-size: 28px;
        font-weight: 900;
        height: 64px;
        margin-bottom: 22px;
        place-items: center;
        width: 64px;
    }

    .result-hero h1 {
        color: #FFFFFF;
        font-size: 36px;
        line-height: 1.12;
        margin: 0 0 12px;
    }

    .result-hero p {
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.7;
        margin: 0;
        max-width: 620px;
    }

    .summary-card,
    .next-card {
        background: #FFFFFF;
        border: 1px solid #DDE8F7;
        border-radius: var(--radius);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.04);
        padding: 18px;
    }

    .summary-card {
        align-self: start;
    }

    .summary-card span,
    .next-card span {
        color: #6B7280;
        display: block;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 7px;
        text-transform: uppercase;
    }

    .summary-card strong {
        color: #0F172A;
        display: block;
        font-size: 18px;
        margin-bottom: 14px;
    }

    .summary-line {
        border-top: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        padding: 12px 0 0;
    }

    .summary-line b {
        color: #0B63F6;
    }

    .next-card {
        grid-column: 1 / -1;
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }

    .btn-primary,
    .btn-secondary {
        align-items: center;
        border-radius: 8px;
        display: inline-flex;
        font-size: 14px;
        font-weight: 800;
        justify-content: center;
        min-height: 46px;
        padding: 12px 18px;
    }

    .btn-primary {
        background: #0B63F6;
        color: #FFFFFF;
    }

    .btn-primary:hover {
        background: #084EC1;
        box-shadow: 0 12px 24px rgba(11, 99, 246, 0.22);
        color: #FFFFFF;
    }

    .btn-secondary {
        background: #FFFFFF;
        border: 1px solid #D6E0EE;
        color: #0B63F6;
    }

    .btn-secondary:hover {
        background: #EAF2FF;
        border-color: #9BBDFB;
        color: #0B63F6;
    }

    @media (max-width: 780px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 22px;
            position: static;
        }

        .result-panel {
            grid-template-columns: 1fr;
            padding: 20px;
        }

        .result-hero {
            padding: 28px 22px;
        }

        .result-hero h1 {
            font-size: 30px;
        }
    }
</style>

<div class="result-page">
    <div class="vapor-bg" aria-hidden="true">
        <span class="vapor-layer"></span>
    </div>

    <nav class="store-nav">
        <a class="logo" href="{{ route('home') }}">Puffcart</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('cart') }}">Cart</a>
            <a href="{{ route('orders.index') }}">Orders</a>
        </div>
    </nav>

    <main class="result-shell">
        <section class="result-panel">
            <div class="result-hero">
                <div class="status-mark">{{ $isPaid ? 'OK' : '...' }}</div>
                <h1>{{ $isPaid ? 'Payment Confirmed' : 'Payment Processing' }}</h1>
                <p>
                    {{ $isPaid
                        ? 'Your payment was confirmed and your Puffcart order is now moving into processing.'
                        : 'You returned from PayMongo successfully. We are waiting for the secure webhook before marking this order paid.' }}
                </p>
            </div>

            <aside class="summary-card">
                <span>Order</span>
                <strong>{{ $order->order_number }}</strong>
                <span>Status</span>
                <strong>{{ ucfirst($payment->payment_status ?? 'pending') }}</strong>
                <div class="summary-line">
                    <span>Total</span>
                    <b>PHP {{ number_format($order->total, 2) }}</b>
                </div>
            </aside>

            <div class="next-card">
                <span>Next Step</span>
                <p style="color:#4B5563;line-height:1.65;margin:0;">
                    {{ $isPaid
                        ? 'You can view your order details or track the latest fulfillment updates from your Puffcart account.'
                        : 'If the status does not update shortly, refresh the payment status or open your order details.' }}
                </p>
                <div class="actions">
                    <a class="btn-primary" href="{{ route('orders.show', $order) }}">View Order</a>
                    @if($isPaid)
                        <a class="btn-secondary" href="{{ route('orders.track', $order) }}">Track Order</a>
                    @else
                        <a class="btn-secondary" href="{{ route('payment.show', $order) }}">Refresh Payment Status</a>
                    @endif
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
