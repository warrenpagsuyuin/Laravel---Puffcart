@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<style>
    .store-nav {
        align-items: center;
        background: var(--bg-white);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        min-height: 72px;
        padding: 0 48px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .logo {
        color: var(--primary);
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 0;
    }

    .nav-links {
        display: flex;
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
    }

    .cart-shell {
        margin: 0 auto;
        max-width: 1120px;
        padding: 40px 20px 64px;
    }

    .cart-layout {
        display: grid;
        gap: 28px;
        grid-template-columns: minmax(0, 1fr) 340px;
        align-items: start;
    }

    .cart-list,
    .summary {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }

    .cart-list {
        display: grid;
    }

    .cart-row {
        align-items: center;
        border-bottom: 1px solid var(--border);
        display: grid;
        gap: 16px;
        grid-template-columns: minmax(0, 1fr) 150px 110px;
        padding: 18px;
    }

    .cart-row:last-child {
        border-bottom: 0;
    }

    .item-name {
        color: var(--text-primary);
        font-weight: 800;
    }

    .muted {
        color: var(--text-muted);
        font-size: 13px;
    }

    .quantity-form {
        display: flex;
        gap: 8px;
    }

    .quantity-form input {
        width: 72px;
    }

    .summary {
        display: grid;
        gap: 14px;
        padding: 22px;
        position: sticky;
        top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 18px;
    }

    .summary-row strong {
        color: var(--text-primary);
    }

    .total-row {
        border-top: 1px solid var(--border);
        font-size: 18px;
        font-weight: 800;
        padding-top: 14px;
    }

    .btn-primary,
    .btn-secondary,
    .btn-danger {
        align-items: center;
        border-radius: var(--radius);
        display: inline-flex;
        font-size: 14px;
        font-weight: 800;
        justify-content: center;
        min-height: 40px;
        padding: 9px 13px;
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

    .btn-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .notice {
        border-radius: var(--radius);
        font-size: 14px;
        margin-bottom: 18px;
        padding: 12px 14px;
    }

    .notice-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
    }

    .notice-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .empty {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 36px;
        text-align: center;
    }

    @media (max-width: 840px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px;
        }

        .cart-layout,
        .cart-row {
            grid-template-columns: 1fr;
        }

        .summary {
            position: static;
        }
    }
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('tracking') }}">Tracking</a>
        <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
    </div>
</nav>

<main class="cart-shell">
    @if(session('success'))
        <div class="notice notice-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="notice notice-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="notice notice-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <h1>Shopping Cart</h1>

    @if($items->isEmpty())
        <div class="empty">
            <h2>Your cart is empty</h2>
            <p class="muted">Add products from the shop to start an order.</p>
            <a class="btn-primary" href="{{ route('shop') }}">Shop Products</a>
        </div>
    @else
        <div class="cart-layout">
            <section class="cart-list">
                @foreach($items as $item)
                    <div class="cart-row">
                        <div>
                            <a class="item-name" href="{{ route('product.show', $item->product) }}">{{ $item->product->name }}</a>
                            <div class="muted">{{ $item->product->category_name }} / PHP {{ number_format($item->product->price, 2) }}</div>
                            <div class="muted">Type: {{ $item->product_type_label }}</div>
                            @if($item->flavor_label)
                                <div class="muted">Flavor: {{ $item->flavor_label }}</div>
                            @endif
                            @if($item->battery_color_label)
                                <div class="muted">Battery Color: {{ $item->battery_color_label }}</div>
                            @endif
                            @if($item->bundle_description)
                                <div class="muted">{{ $item->bundle_description }}</div>
                            @endif
                            <div class="muted">{{ $item->available_stock }} available for this selection</div>
                        </div>

                        <form class="quantity-form" method="POST" action="{{ route('cart.update', $item) }}">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" min="1" max="{{ max(1, $item->available_stock) }}" value="{{ $item->quantity }}">
                            <button class="btn-secondary" type="submit">Update</button>
                        </form>

                        <div>
                            <strong>PHP {{ number_format($item->subtotal, 2) }}</strong>
                            <form method="POST" action="{{ route('cart.remove', $item) }}" style="margin-top:8px;">
                                @csrf
                                @method('DELETE')
                                <button class="btn-danger" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </section>

            <aside class="summary">
                <h2>Order Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>PHP {{ number_format($subtotal, 2) }}</strong>
                </div>
                <div class="summary-row">
                    <span>Delivery</span>
                    <strong>{{ $deliveryFee > 0 ? 'PHP ' . number_format($deliveryFee, 2) : 'Free' }}</strong>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <strong>PHP {{ number_format($total, 2) }}</strong>
                </div>
                <a class="btn-primary" href="{{ route('checkout') }}">Checkout</a>
                <a class="btn-secondary" href="{{ route('shop') }}">Continue Shopping</a>
            </aside>
        </div>
    @endif
</main>
@endsection
