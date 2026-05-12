@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<style>
    body {
        background: #f6f8fb;
    }

    .store-nav {
        align-items: center;
        background: #ffffff;
        border-bottom: 1px solid #e3e8f0;
        display: flex;
        justify-content: space-between;
        min-height: 72px;
        padding: 14px 48px;
    }

    .logo {
        color: #0b66ff;
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 800;
    }

    .nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
    }

    .nav-links a {
        color: #4b5563;
        font-size: 14px;
        font-weight: 700;
    }

    .payment-shell {
        margin: 0 auto;
        max-width: 860px;
        padding: 48px 24px 72px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #dfe5ef;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        display: grid;
        gap: 22px;
        padding: 30px;
    }

    .payment-header {
        border-bottom: 1px solid #e8edf5;
        padding-bottom: 20px;
    }

    .payment-header h1 {
        color: #111827;
        font-size: 32px;
        margin: 0 0 8px;
    }

    .muted {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.55;
    }

    .summary-grid {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .summary-box {
        background: #f8fafc;
        border: 1px solid #e3e8f0;
        border-radius: 8px;
        padding: 14px;
    }

    .summary-box span {
        color: #6b7280;
        display: block;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .summary-box strong {
        color: #111827;
        font-size: 16px;
    }

    .notice {
        border-radius: 8px;
        font-size: 14px;
        padding: 13px 15px;
    }

    .notice-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .notice-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .btn-primary,
    .btn-secondary {
        align-items: center;
        border-radius: 8px;
        display: inline-flex;
        font-size: 14px;
        font-weight: 900;
        justify-content: center;
        min-height: 48px;
        padding: 12px 16px;
        text-align: center;
    }

    .btn-primary {
        background: #0b66ff;
        border: 1px solid #0b66ff;
        color: #ffffff;
    }

    .btn-secondary {
        background: #ffffff;
        border: 1px solid #cfd7e3;
        color: #111827;
    }

    .actions {
        display: grid;
        gap: 12px;
        grid-template-columns: minmax(0, 1fr) 180px;
    }

    @media (max-width: 720px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 22px;
        }

        .summary-grid,
        .actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('cart') }}">Cart</a>
        <a href="{{ route('orders.index') }}">Orders</a>
    </div>
</nav>

<main class="payment-shell">
    <section class="panel">
        <div class="payment-header">
            <h1>Complete Payment</h1>
            <p class="muted">Your order is saved, but it will not proceed to tracking or processing until payment is confirmed.</p>
        </div>

        @if(session('success'))
            <div class="notice notice-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="notice notice-error">{{ session('error') }}</div>
        @endif

        <div class="summary-grid">
            <div class="summary-box">
                <span>Order</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div class="summary-box">
                <span>Payment</span>
                <strong>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</strong>
            </div>
            <div class="summary-box">
                <span>Total</span>
                <strong>PHP {{ number_format($order->total, 2) }}</strong>
            </div>
        </div>

        <div class="notice notice-error">
            Payment required. Please pay now to continue with this order.
        </div>

        <div class="actions">
            <form method="POST" action="{{ route('payment.checkout', $order) }}">
                @csrf
                <button class="btn-primary" type="submit" style="width:100%;">Pay Now</button>
            </form>
            <a class="btn-secondary" href="{{ route('orders.show', $order) }}">View Order</a>
        </div>
    </section>
</main>
@endsection
