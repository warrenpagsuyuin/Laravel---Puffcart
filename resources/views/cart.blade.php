@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<style>
    body {
        background: #ffffff;
    }

    .nav {
        background: var(--bg-white);
        border-bottom: 1px solid var(--border);
        padding: 16px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-family: 'Poppins', sans-serif;
        color: var(--primary);
        font-size: 18px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .nav-links {
        display: flex;
        gap: 32px;
    }

    .nav a {
        color: var(--text-secondary);
        font-size: 14px;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .nav a:hover {
        color: var(--primary);
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .cart-header {
        margin-bottom: 40px;
    }

    .cart-header h1 {
        font-size: 32px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .cart-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }

    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .cart-item {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .item-info h3 {
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .item-meta {
        color: var(--text-muted);
        font-size: 13px;
    }

    .item-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
    }

    .order-summary {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 24px;
        height: fit-content;
        box-shadow: var(--shadow-sm);
    }

    .order-summary h2 {
        font-size: 18px;
        margin-bottom: 20px;
        color: var(--text-primary);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        color: var(--text-secondary);
    }

    .summary-divider {
        height: 1px;
        background: var(--border);
        margin: 16px 0;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .summary-total .amount {
        color: var(--primary);
    }

    .checkout-btn {
        width: 100%;
        padding: 12px 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 20px;
    }

    .checkout-btn:hover {
        background: var(--primary-hover);
        box-shadow: var(--shadow-md);
    }

    .continue-shopping {
        display: inline-block;
        padding: 8px 16px;
        color: var(--primary);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-top: 12px;
    }

    .continue-shopping:hover {
        color: var(--primary-hover);
    }

    @media (max-width: 768px) {
        .cart-layout {
            grid-template-columns: 1fr;
        }

        .order-summary {
            order: -1;
        }
    }
</style>

<div class="nav">
    <div class="logo">VapeVault</div>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
        <a href="/tracking">Tracking</a>
    </div>
</div>

<div class="cart-container">
    <div class="cart-header">
        <h1>Shopping Cart</h1>
    </div>

    <div class="cart-layout">
        <div class="cart-items">
            <div class="cart-item">
                <div class="item-info">
                    <h3>XROS 4 Mini</h3>
                    <div class="item-meta">Quantity: 1</div>
                </div>
                <div class="item-price">₱1,299.00</div>
            </div>

            <div class="cart-item">
                <div class="item-info">
                    <h3>Lava Flow</h3>
                    <div class="item-meta">Quantity: 2</div>
                </div>
                <div class="item-price">₱900.00</div>
            </div>
        </div>

        <div class="order-summary">
            <h2>Order Summary</h2>

            <div class="summary-row">
                <span>Subtotal</span>
                <span>₱2,199.00</span>
            </div>

            <div class="summary-row">
                <span>Shipping</span>
                <span style="color: var(--primary); font-weight: 600;">FREE</span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-total">
                <span>Total</span>
                <span class="amount">₱2,199.00</span>
            </div>

            <a href="/tracking" class="checkout-btn">Proceed to Checkout</a>
            <a href="/shop" class="continue-shopping">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection