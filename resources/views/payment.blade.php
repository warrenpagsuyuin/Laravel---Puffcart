@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<style>
    body {
        background: #F8FAFC;
    }

    .payment-page {
        background:
            radial-gradient(circle at 10% 8%, rgba(11, 99, 246, 0.08), transparent 28%),
            linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 45%, #FFFFFF 100%);
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
        transform: translate3d(0, 0, 0);
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
        0% {
            transform: translate3d(-16px, 8px, 0) scale(1);
        }

        100% {
            transform: translate3d(42px, -18px, 0) scale(1.08);
        }
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
        flex-wrap: wrap;
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

    .payment-shell {
        margin: 0 auto;
        max-width: 960px;
        padding: 56px 24px 72px;
        position: relative;
        z-index: 1;
    }

    .panel {
        background:
            radial-gradient(circle at 92% 10%, rgba(11, 99, 246, 0.1), transparent 24%),
            rgba(255, 255, 255, 0.94);
        border: 1px solid #E4ECF7;
        border-radius: var(--radius-lg);
        box-shadow: 0 22px 44px rgba(15, 23, 42, 0.08);
        display: grid;
        gap: 24px;
        overflow: hidden;
        padding: 34px;
    }

    .payment-header {
        background:
            radial-gradient(circle at 86% 18%, rgba(255, 255, 255, 0.2), transparent 26%),
            linear-gradient(135deg, #0B63F6 0%, #0F3A8A 100%);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 42px rgba(11, 99, 246, 0.18);
        color: #FFFFFF;
        margin: -10px -10px 0;
        padding: 32px;
    }

    .payment-header h1 {
        color: #FFFFFF;
        font-size: 32px;
        margin: 0 0 8px;
    }

    .payment-header .muted {
        color: rgba(255, 255, 255, 0.86);
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
        background: #FFFFFF;
        border: 1px solid #DDE8F7;
        border-radius: var(--radius);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.04);
        padding: 16px;
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
        font-size: 17px;
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

    .notice-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
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
        background: #0B63F6;
        border: 1px solid #0B63F6;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #084EC1;
        box-shadow: 0 12px 24px rgba(11, 99, 246, 0.22);
        color: #FFFFFF;
    }

    .btn-primary[disabled] {
        cursor: wait;
        opacity: 0.72;
    }

    .btn-secondary {
        background: #ffffff;
        border: 1px solid #D6E0EE;
        color: #0B63F6;
    }

    .btn-secondary:hover {
        background: #EAF2FF;
        border-color: #9BBDFB;
        color: #0B63F6;
    }

    .actions {
        display: grid;
        gap: 12px;
        grid-template-columns: minmax(0, 1fr) 180px;
    }

    :root[data-theme="dark"] .payment-page {
        background:
            radial-gradient(circle at 10% 8%, rgba(125, 183, 255, 0.12), transparent 28%),
            radial-gradient(circle at 88% 14%, rgba(139, 92, 246, 0.12), transparent 24%),
            linear-gradient(180deg, #07111F 0%, #0F172A 48%, #08111F 100%);
    }

    :root[data-theme="dark"] .vapor-bg::before {
        background:
            radial-gradient(circle at 18% 50%, rgba(91, 157, 255, 0.34), transparent 34%),
            radial-gradient(circle at 56% 46%, rgba(168, 85, 247, 0.22), transparent 36%),
            radial-gradient(circle at 86% 58%, rgba(215, 233, 255, 0.18), transparent 32%);
        opacity: 0.42;
    }

    :root[data-theme="dark"] .vapor-bg::after,
    :root[data-theme="dark"] .vapor-layer {
        background:
            radial-gradient(circle at 18% 56%, rgba(71, 121, 255, 0.22), transparent 34%),
            radial-gradient(circle at 52% 48%, rgba(148, 163, 255, 0.2), transparent 38%),
            radial-gradient(circle at 86% 50%, rgba(216, 180, 254, 0.18), transparent 34%);
        opacity: 0.48;
    }

    :root[data-theme="dark"] .store-nav {
        background: rgba(10, 16, 29, 0.94);
        border-bottom-color: #203047;
    }

    :root[data-theme="dark"] .logo {
        color: #7DB7FF;
    }

    :root[data-theme="dark"] .nav-links a {
        color: #CBD5E1;
    }

    :root[data-theme="dark"] .nav-links a:hover {
        background: #112B4F;
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .panel,
    :root[data-theme="dark"] .summary-box {
        background: rgba(18, 27, 43, 0.92);
        border-color: #26384F;
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.24);
    }

    :root[data-theme="dark"] .payment-header {
        box-shadow: 0 20px 42px rgba(47, 124, 246, 0.18);
    }

    :root[data-theme="dark"] .summary-box strong {
        color: #F3F8FF;
    }

    :root[data-theme="dark"] .summary-box span,
    :root[data-theme="dark"] .muted {
        color: #B7C6DA;
    }

    :root[data-theme="dark"] .btn-secondary {
        background: #121B2B;
        border-color: #2F4562;
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .btn-secondary:hover {
        background: #112B4F;
        border-color: #5B9DFF;
    }

    @media (max-width: 720px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 22px;
            position: static;
        }

        .summary-grid,
        .actions {
            grid-template-columns: 1fr;
        }

        .payment-shell {
            padding: 28px 18px 56px;
        }

        .panel {
            padding: 20px;
        }

        .payment-header {
            margin: 0;
            padding: 26px 22px;
        }
    }
</style>

<div class="payment-page">
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
            Payment required. Your order stays pending until PayMongo confirms payment through a secure webhook.
        </div>

        <div class="notice notice-info">
            You will be redirected to PayMongo Hosted Checkout. Puffcart never handles or stores your card or wallet credentials.
        </div>

        <div class="actions">
            <form method="POST" action="{{ route('payment.checkout', $order) }}" id="paymongoCheckoutForm">
                @csrf
                <button class="btn-primary" type="submit" style="width:100%;" id="paymongoCheckoutButton">Pay Securely with PayMongo</button>
            </form>
            <a class="btn-secondary" href="{{ route('orders.show', $order) }}">View Order</a>
        </div>
        </section>
    </main>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('paymongoCheckoutForm');
        const button = document.getElementById('paymongoCheckoutButton');

        form?.addEventListener('submit', () => {
            if (!button) {
                return;
            }

            button.disabled = true;
            button.textContent = 'Redirecting to PayMongo...';
        });
    });
</script>
@endsection
