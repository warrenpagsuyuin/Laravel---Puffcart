@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php
    $selectedPayment = old('payment_method', 'gcash');
    $promoValue = old('promo_code', request('promo_code', $promo?->code));
    $paymentMethods = [
        'gcash' => ['label' => 'GCash', 'detail' => 'Pay securely through PayMongo after order review.', 'badge' => 'Wallet', 'icon' => 'G'],
        'maya' => ['label' => 'Maya', 'detail' => 'Digital wallet payment for fast confirmation.', 'badge' => 'Fast', 'icon' => 'M'],
        'cod' => ['label' => 'Cash on Delivery', 'detail' => 'Pay in cash when your order arrives.', 'badge' => 'On arrival', 'icon' => 'C'],
        'bank_transfer' => ['label' => 'Bank Transfer', 'detail' => 'Receive transfer instructions after placing the order.', 'badge' => 'Manual', 'icon' => 'B'],
    ];
@endphp

<style>
    body {
        background: #F8FAFC;
    }

    .checkout-page {
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
        letter-spacing: 0;
    }

    .nav-links {
        align-items: center;
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

    .checkout-shell {
        margin: 0 auto;
        max-width: 1180px;
        padding: 48px 24px 72px;
        position: relative;
        z-index: 1;
    }

    .checkout-header {
        align-items: center;
        background:
            radial-gradient(circle at 92% 12%, rgba(255, 255, 255, 0.52), transparent 24%),
            linear-gradient(135deg, #0B63F6 0%, #0F3A8A 100%);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 42px rgba(11, 99, 246, 0.18);
        color: #FFFFFF;
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
        padding: 34px 38px;
    }

    .checkout-header h1 {
        color: #FFFFFF;
        font-size: 34px;
        line-height: 1.15;
        margin: 0 0 8px;
    }

    .checkout-header .muted {
        color: rgba(255, 255, 255, 0.86);
    }

    .muted {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.55;
    }

    .checkout-steps {
        align-items: center;
        display: flex;
        gap: 10px;
        min-width: 320px;
    }

    .step {
        align-items: center;
        color: rgba(255, 255, 255, 0.82);
        display: flex;
        gap: 8px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .step-dot {
        align-items: center;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.34);
        border-radius: 999px;
        color: #FFFFFF;
        display: inline-flex;
        height: 26px;
        justify-content: center;
        width: 26px;
    }

    .step-line {
        background: rgba(255, 255, 255, 0.28);
        height: 1px;
        flex: 1;
    }

    .checkout-grid {
        align-items: start;
        display: grid;
        gap: 24px;
        grid-template-columns: minmax(0, 1fr) 390px;
    }

    .panel {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #E4ECF7;
        border-radius: var(--radius-lg);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
    }

    .panel-header {
        border-bottom: 1px solid #e8edf5;
        padding: 22px 26px 18px;
    }

    .panel-header h2 {
        color: #111827;
        font-size: 22px;
        margin: 0 0 6px;
    }

    .checkout-form {
        display: grid;
        gap: 18px;
        padding: 24px 26px 26px;
    }

    .form-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .form-field {
        display: grid;
        gap: 7px;
    }

    .form-field.full {
        grid-column: 1 / -1;
    }

    .form-field label,
    .field-label {
        color: #111827;
        font-size: 13px;
        font-weight: 800;
    }

    .form-field input,
    .form-field textarea {
        background: #ffffff;
        border: 1px solid #cfd7e3;
        border-radius: 8px;
        color: #111827;
        font-size: 14px;
        min-height: 46px;
        padding: 11px 13px;
        width: 100%;
    }

    .form-field textarea {
        min-height: 96px;
        resize: vertical;
    }

    .form-field input:focus,
    .form-field textarea:focus {
        border-color: #0b66ff;
        box-shadow: 0 0 0 3px rgba(11, 102, 255, 0.12);
        outline: none;
    }

    .field-error {
        color: #b91c1c;
        font-size: 12px;
        font-weight: 700;
    }

    .payment-grid {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .payment-option {
        background:
            radial-gradient(circle at 84% 16%, rgba(11, 99, 246, 0.08), transparent 28%),
            #FFFFFF;
        border: 1px solid #DDE8F7;
        border-radius: var(--radius-lg);
        cursor: pointer;
        display: flex;
        gap: 14px;
        min-height: 126px;
        padding: 18px;
        position: relative;
        transition: all 0.24s ease;
    }

    .payment-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-icon {
        align-items: center;
        background: linear-gradient(135deg, #0B63F6, #4A93FF);
        border-radius: 16px;
        box-shadow: 0 12px 22px rgba(11, 99, 246, 0.18);
        color: #FFFFFF;
        display: inline-flex;
        flex: 0 0 48px;
        font-size: 18px;
        font-weight: 900;
        height: 48px;
        justify-content: center;
        width: 48px;
    }

    .payment-copy {
        display: grid;
        gap: 5px;
        min-width: 0;
    }

    .payment-topline {
        align-items: center;
        display: flex;
        gap: 10px;
        justify-content: space-between;
    }

    .payment-name {
        color: #111827;
        font-size: 14px;
        font-weight: 900;
    }

    .payment-badge {
        background: #EAF2FF;
        border-radius: 999px;
        color: #0B63F6;
        font-size: 11px;
        font-weight: 900;
        padding: 4px 8px;
        white-space: nowrap;
    }

    .payment-check {
        align-items: center;
        background: #FFFFFF;
        border: 1px solid #D6E0EE;
        border-radius: 999px;
        color: transparent;
        display: inline-flex;
        font-size: 12px;
        font-weight: 900;
        height: 24px;
        justify-content: center;
        position: absolute;
        right: 14px;
        top: 14px;
        width: 24px;
    }

    .payment-option:hover {
        border-color: #9BBDFB;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }

    .payment-option:has(input:checked) {
        background:
            radial-gradient(circle at 84% 16%, rgba(11, 99, 246, 0.18), transparent 30%),
            linear-gradient(135deg, rgba(234, 242, 255, 0.95), rgba(255, 255, 255, 0.96));
        border-color: #0B63F6;
        box-shadow: 0 18px 34px rgba(11, 99, 246, 0.14);
    }

    .payment-option:has(input:checked) .payment-check {
        background: #0B63F6;
        border-color: #0B63F6;
        color: #FFFFFF;
    }

    .notice {
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 18px;
        padding: 13px 15px;
    }

    .notice-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .notice-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .form-actions {
        display: grid;
        gap: 12px;
        grid-template-columns: 1fr 180px;
        margin-top: 4px;
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
        color: #ffffff;
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

    .summary-panel {
        position: sticky;
        top: 96px;
    }

    .summary-body {
        padding: 22px 26px 26px;
    }

    .item-row {
        border-bottom: 1px solid #e8edf5;
        display: grid;
        gap: 14px;
        grid-template-columns: minmax(0, 1fr) auto;
        padding: 14px 0;
    }

    .item-row:first-child {
        padding-top: 0;
    }

    .item-name {
        color: #111827;
        font-size: 14px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .item-price {
        color: #111827;
        font-weight: 900;
        white-space: nowrap;
    }

    .summary-lines {
        display: grid;
        gap: 14px;
        padding-top: 16px;
    }

    .summary-row {
        align-items: center;
        display: flex;
        justify-content: space-between;
        gap: 18px;
    }

    .summary-row strong {
        color: #111827;
    }

    .total-row {
        border-top: 1px solid #d9dee8;
        font-size: 18px;
        font-weight: 900;
        margin-top: 4px;
        padding-top: 18px;
    }

    .promo-box {
        background: #F8FAFC;
        border: 1px solid #E3EAF5;
        border-radius: var(--radius);
        display: grid;
        gap: 10px;
        margin-top: 18px;
        padding: 14px;
    }

    .promo-form {
        display: grid;
        gap: 8px;
        grid-template-columns: minmax(0, 1fr) 92px;
    }

    .promo-form input {
        border: 1px solid #cfd7e3;
        border-radius: 8px;
        min-height: 42px;
        padding: 10px 12px;
        width: 100%;
    }

    .promo-form button {
        min-height: 42px;
        padding: 9px 12px;
    }

    :root[data-theme="dark"] .checkout-page {
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
    :root[data-theme="dark"] .payment-option,
    :root[data-theme="dark"] .summary-box,
    :root[data-theme="dark"] .promo-box {
        background: rgba(18, 27, 43, 0.92);
        border-color: #26384F;
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.24);
    }

    :root[data-theme="dark"] .panel-header,
    :root[data-theme="dark"] .item-row,
    :root[data-theme="dark"] .total-row {
        border-color: #26384F;
    }

    :root[data-theme="dark"] .checkout-header {
        box-shadow: 0 20px 42px rgba(47, 124, 246, 0.18);
    }

    :root[data-theme="dark"] .panel-header h2,
    :root[data-theme="dark"] .form-field label,
    :root[data-theme="dark"] .field-label,
    :root[data-theme="dark"] .payment-name,
    :root[data-theme="dark"] .item-name,
    :root[data-theme="dark"] .item-price,
    :root[data-theme="dark"] .summary-row strong,
    :root[data-theme="dark"] .promo-box strong {
        color: #F3F8FF;
    }

    :root[data-theme="dark"] .muted,
    :root[data-theme="dark"] .summary-row span {
        color: #B7C6DA;
    }

    :root[data-theme="dark"] .payment-badge {
        background: #112B4F;
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .payment-option:has(input:checked) {
        background:
            radial-gradient(circle at 84% 16%, rgba(125, 183, 255, 0.16), transparent 30%),
            rgba(18, 27, 43, 0.96);
        border-color: #5B9DFF;
    }

    :root[data-theme="dark"] .form-field input,
    :root[data-theme="dark"] .form-field textarea,
    :root[data-theme="dark"] .promo-form input,
    :root[data-theme="dark"] .btn-secondary {
        background: #121B2B;
        border-color: #2F4562;
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .btn-secondary:hover {
        background: #112B4F;
        border-color: #5B9DFF;
    }

    @media (max-width: 900px) {
        .store-nav,
        .checkout-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .store-nav {
            gap: 14px;
            padding: 16px 22px;
            position: static;
        }

        .checkout-shell {
            padding: 28px 18px 56px;
        }

        .checkout-header {
            padding: 28px 22px;
        }

        .checkout-grid,
        .form-grid,
        .payment-grid,
        .form-actions {
            grid-template-columns: 1fr;
        }

        .checkout-steps {
            min-width: 0;
            width: 100%;
        }

        .summary-panel {
            position: static;
        }
    }

    @media (max-width: 560px) {
        .payment-option {
            min-height: 0;
            padding: 16px;
        }

        .payment-topline {
            align-items: flex-start;
            flex-direction: column;
            gap: 6px;
            padding-right: 28px;
        }
    }
</style>

<div class="checkout-page">
    <div class="vapor-bg" aria-hidden="true">
        <span class="vapor-layer"></span>
    </div>

    <nav class="store-nav">
        <a class="logo" href="{{ route('home') }}">Puffcart</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('cart') }}">Cart</a>
            <a href="{{ route('tracking') }}">Tracking</a>
            <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
        </div>
    </nav>

    <main class="checkout-shell">
    <div class="checkout-header">
        <div>
            <h1>Secure Checkout</h1>
            <p class="muted">Confirm delivery details, choose a payment method, and submit the order for processing.</p>
        </div>
        <div class="checkout-steps" aria-label="Checkout progress">
            <div class="step"><span class="step-dot">1</span> Cart</div>
            <span class="step-line"></span>
            <div class="step"><span class="step-dot">2</span> Checkout</div>
            <span class="step-line"></span>
            <div class="step"><span class="step-dot">3</span> Review</div>
        </div>
    </div>

    @if(session('success'))
        <div class="notice notice-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="notice notice-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="checkout-grid">
        <section class="panel">
            <div class="panel-header">
                <h2>Delivery & Payment</h2>
                <p class="muted">Use accurate contact information so the rider or support team can reach you.</p>
            </div>

            <form class="checkout-form" method="POST" action="{{ route('checkout.place') }}" id="checkoutForm">
                @csrf
                <input type="hidden" name="promo_code" value="{{ $promoValue }}">
                @foreach($cart_item_ids as $cartItemId)
                    <input type="hidden" name="cart_item_ids[]" value="{{ $cartItemId }}">
                @endforeach

                <div class="form-grid">
                    <div class="form-field full">
                        <label for="delivery_address">Delivery Address</label>
                        <textarea id="delivery_address" name="delivery_address" required>{{ old('delivery_address', auth()->user()->address) }}</textarea>
                        @error('delivery_address')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="delivery_phone">Delivery Phone</label>
                        <input
                            id="delivery_phone"
                            name="delivery_phone"
                            value="{{ old('delivery_phone', auth()->user()->phone) }}"
                            maxlength="11"
                            minlength="11"
                            pattern="[0-9]{11}"
                            inputmode="numeric"
                            placeholder="09123456789"
                            required
                        >
                        @error('delivery_phone')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="notes">Delivery Notes</label>
                        <input id="notes" name="notes" value="{{ old('notes') }}" placeholder="Landmark, gate code, or preferred time">
                        @error('notes')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-field">
                    <div class="field-label">Payment Method</div>
                    <div class="payment-grid">
                        @foreach($paymentMethods as $value => $method)
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="{{ $value }}" @checked($selectedPayment === $value) required>
                                <span class="payment-icon" aria-hidden="true">{{ $method['icon'] }}</span>
                                <span class="payment-copy">
                                    <span class="payment-topline">
                                        <span class="payment-name">{{ $method['label'] }}</span>
                                        <span class="payment-badge">{{ $method['badge'] }}</span>
                                    </span>
                                    <span class="muted">{{ $method['detail'] }}</span>
                                </span>
                                <span class="payment-check" aria-hidden="true">✓</span>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="btn-primary" type="submit" id="checkoutSubmit">Place Order</button>
                    <a class="btn-secondary" href="{{ route('cart') }}">Back to Cart</a>
                </div>
            </form>
        </section>

        <aside class="panel summary-panel">
            <div class="panel-header">
                <h2>Order Summary</h2>
                <p class="muted">{{ $items->count() }} item(s) ready for checkout.</p>
            </div>

            <div class="summary-body">
                @foreach($items as $item)
                    <div class="item-row">
                        <div>
                            <div class="item-name">{{ $item->product->name }}</div>
                            <div class="muted">Qty {{ $item->quantity }} / {{ $item->product_type_label }}</div>
                            @if($item->flavor_label)
                                <div class="muted">Flavor: {{ $item->flavor_label }}</div>
                            @endif
                            @if($item->battery_color_label)
                                <div class="muted">Battery Color: {{ $item->battery_color_label }}</div>
                            @endif
                            @if($item->bundle_description)
                                <div class="muted">{{ $item->bundle_description }}</div>
                            @endif
                        </div>
                        <div class="item-price">PHP {{ number_format($item->subtotal, 2) }}</div>
                    </div>
                @endforeach

                <div class="promo-box">
                    <div>
                        <strong>Promo Code</strong>
                        @if($promo)
                            <div class="muted">{{ $promo->code }} applied to this checkout.</div>
                        @else
                            <div class="muted">Apply a valid code before placing the order.</div>
                        @endif
                    </div>
                    <form class="promo-form" method="GET" action="{{ route('checkout') }}">
                        @foreach($cart_item_ids as $cartItemId)
                            <input type="hidden" name="cart_item_ids[]" value="{{ $cartItemId }}">
                        @endforeach
                        <input name="promo_code" value="{{ $promoValue }}" placeholder="PUFF10">
                        <button class="btn-secondary" type="submit">Apply</button>
                    </form>
                </div>

                <div class="summary-lines">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong>PHP {{ number_format($subtotal, 2) }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Delivery</span>
                        <strong>{{ $delivery_fee > 0 ? 'PHP ' . number_format($delivery_fee, 2) : 'Free' }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Discount</span>
                        <strong>- PHP {{ number_format($discount, 2) }}</strong>
                    </div>
                    <div class="summary-row total-row">
                        <span>Total</span>
                        <strong>PHP {{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phone = document.getElementById('delivery_phone');

        if (phone) {
            phone.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
            });
        }

        const checkoutForm = document.getElementById('checkoutForm');
        const checkoutSubmit = document.getElementById('checkoutSubmit');

        checkoutForm?.addEventListener('submit', function () {
            if (!checkoutSubmit) {
                return;
            }

            const selectedPayment = checkoutForm.querySelector('input[name="payment_method"]:checked')?.value;

            checkoutSubmit.disabled = true;
            checkoutSubmit.textContent = selectedPayment === 'cod'
                ? 'Placing order...'
                : 'Creating secure checkout...';
        });
    });
</script>
@endsection
