@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<style>
    .store-nav {
        align-items: center;
        background: var(--bg-white);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        padding: 16px 40px;
    }

    .logo {
        color: var(--primary);
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 700;
    }

    .nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 22px;
    }

    .nav-links a {
        color: var(--text-secondary);
        font-size: 14px;
        font-weight: 600;
    }

    .checkout-shell {
        margin: 0 auto;
        max-width: 1120px;
        padding: 40px 20px 64px;
    }

    .checkout-grid {
        display: grid;
        gap: 28px;
        grid-template-columns: minmax(0, 1fr) 360px;
        align-items: start;
    }

    .panel {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px;
    }

    .form-grid {
        display: grid;
        gap: 16px;
    }

    .summary-row,
    .item-row {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        padding: 10px 0;
    }

    .item-row {
        border-bottom: 1px solid var(--border);
    }

    .muted {
        color: var(--text-muted);
        font-size: 13px;
    }

    .total-row {
        border-top: 1px solid var(--border);
        font-size: 18px;
        font-weight: 800;
        margin-top: 10px;
        padding-top: 16px;
    }

    .btn-primary,
    .btn-secondary {
        align-items: center;
        border-radius: var(--radius);
        display: inline-flex;
        font-size: 14px;
        font-weight: 800;
        justify-content: center;
        min-height: 44px;
        padding: 11px 16px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        color: white;
    }

    .btn-secondary {
        background: var(--bg-white);
        border: 1px solid var(--border);
        color: var(--text-primary);
    }

    .notice {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        color: #991b1b;
        font-size: 14px;
        margin-bottom: 18px;
        padding: 12px 14px;
    }

    @media (max-width: 860px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px;
        }

        .checkout-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('cart') }}">Cart</a>
        <a href="{{ route('tracking') }}">Tracking</a>
        <a href="{{ route('profile') }}">Profile</a>
    </div>
</nav>

<main class="checkout-shell">
    <h1>Checkout</h1>

    @if($errors->any())
        <div class="notice">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="checkout-grid">
        <section class="panel">
            <h2>Delivery & Payment</h2>
            <form class="form-grid" method="POST" action="{{ route('checkout.place') }}">
                @csrf

                <div>
                    <label for="delivery_address">Delivery Address</label>
                    <textarea id="delivery_address" name="delivery_address" required>{{ old('delivery_address', auth()->user()->address) }}</textarea>
                </div>

                <div>
                    <label for="delivery_phone">Delivery Phone</label>
                    <input id="delivery_phone" name="delivery_phone" value="{{ old('delivery_phone', auth()->user()->phone) }}" required>
                </div>

                <div>
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        @foreach(['gcash' => 'GCash', 'maya' => 'Maya', 'cod' => 'Cash on Delivery', 'bank_transfer' => 'Bank Transfer'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('payment_method') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="promo_code">Promo Code</label>
                    <input id="promo_code" name="promo_code" value="{{ old('promo_code', request('promo_code')) }}" placeholder="PUFF10">
                </div>

                <div>
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                </div>

                <button class="btn-primary" type="submit">Place Order</button>
                <a class="btn-secondary" href="{{ route('cart') }}">Back to Cart</a>
            </form>
        </section>

        <aside class="panel">
            <h2>Summary</h2>
            @foreach($items as $item)
                <div class="item-row">
                    <div>
                        <strong>{{ $item->product->name }}</strong>
                        <div class="muted">Qty {{ $item->quantity }}</div>
                    </div>
                    <strong>PHP {{ number_format($item->subtotal, 2) }}</strong>
                </div>
            @endforeach

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

            @if($promo)
                <p class="muted">Promo {{ $promo->code }} applied.</p>
            @endif
        </aside>
    </div>
</main>
@endsection
