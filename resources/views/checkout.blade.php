@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php
    $selectedPayment = old('payment_method', 'gcash');
    $promoValue = old('promo_code', request('promo_code', $promo?->code));
    $paymentMethods = [
        'gcash' => ['label' => 'GCash', 'detail' => 'Pay securely through PayMongo after order review.'],
        'maya' => ['label' => 'Maya', 'detail' => 'Digital wallet payment for fast confirmation.'],
        'cod' => ['label' => 'Cash on Delivery', 'detail' => 'Pay in cash when your order arrives.'],
        'bank_transfer' => ['label' => 'Bank Transfer', 'detail' => 'Receive transfer instructions after placing the order.'],
    ];
@endphp

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
<<<<<<< HEAD
        padding: 0 48px;
        position: sticky;
        top: 0;
        z-index: 100;
=======
        padding: 14px 48px;
        position: sticky;
        top: 0;
        z-index: 20;
>>>>>>> 39a8d5667957c4ee89318e534e51c21bcdbd68e9
    }

    .logo {
        color: #0b66ff;
        font-family: 'Poppins', sans-serif;
<<<<<<< HEAD
        font-size: 20px;
        font-weight: 700;
=======
        font-size: 18px;
        font-weight: 800;
>>>>>>> 39a8d5667957c4ee89318e534e51c21bcdbd68e9
        letter-spacing: 0;
    }

    .nav-links {
        align-items: center;
        display: flex;
<<<<<<< HEAD
        align-items: center;
        gap: 8px;
    }

    .nav-links a {
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 8px;
    }

    .nav-links a:hover {
        background: #eff6ff;
        color: #0b66ff;
=======
        flex-wrap: wrap;
        gap: 24px;
    }

    .nav-links a {
        color: #4b5563;
        font-size: 14px;
        font-weight: 700;
>>>>>>> 39a8d5667957c4ee89318e534e51c21bcdbd68e9
    }

    .checkout-shell {
        margin: 0 auto;
        max-width: 1180px;
        padding: 36px 24px 64px;
    }

    .checkout-header {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 22px;
    }

    .checkout-header h1 {
        color: #111827;
        font-size: 34px;
        line-height: 1.15;
        margin: 0 0 8px;
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
        color: #6b7280;
        display: flex;
        gap: 8px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .step-dot {
        align-items: center;
        background: #dbeafe;
        border: 1px solid #93c5fd;
        border-radius: 999px;
        color: #0b66ff;
        display: inline-flex;
        height: 26px;
        justify-content: center;
        width: 26px;
    }

    .step-line {
        background: #d9dee8;
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
        background: #ffffff;
        border: 1px solid #dfe5ef;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
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
        gap: 12px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .payment-option {
        border: 1px solid #cfd7e3;
        border-radius: 8px;
        cursor: pointer;
        display: grid;
        gap: 5px;
        padding: 14px;
    }

    .payment-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-name {
        color: #111827;
        font-size: 14px;
        font-weight: 900;
    }

    .payment-option:has(input:checked) {
        background: #eef5ff;
        border-color: #0b66ff;
        box-shadow: 0 0 0 3px rgba(11, 102, 255, 0.08);
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
        background: #0b66ff;
        border: 1px solid #0b66ff;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #0954d6;
        color: #ffffff;
    }

    .btn-secondary {
        background: #ffffff;
        border: 1px solid #cfd7e3;
        color: #111827;
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
        background: #f8fafc;
        border: 1px solid #e3e8f0;
        border-radius: 8px;
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
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
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

            <form class="checkout-form" method="POST" action="{{ route('checkout.place') }}">
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
                                <span class="payment-name">{{ $method['label'] }}</span>
                                <span class="muted">{{ $method['detail'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="btn-primary" type="submit">Place Order</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phone = document.getElementById('delivery_phone');

        if (phone) {
            phone.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
            });
        }
    });
</script>
@endsection
