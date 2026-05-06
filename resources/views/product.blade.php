@extends('layouts.app')

@section('title', 'Product')

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

    .product-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .product-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }

    .product-image {
        background: var(--bg-light);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
    }

    .product-emoji {
        font-size: 120px;
    }

    .product-details h1 {
        font-size: 32px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .product-meta {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 24px;
    }

    .product-price {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 24px;
    }

    .product-description {
        color: var(--text-secondary);
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 32px;
    }

    .product-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .btn-primary {
        flex: 1;
        padding: 12px 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        flex: 1;
        padding: 12px 20px;
        background: var(--bg-light);
        color: var(--primary);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    @media (max-width: 768px) {
        .product-layout {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .product-actions {
            flex-direction: column;
        }
    }
</style>

<div class="nav">
    <div class="logo">Puffcart</div>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
        <a href="/cart">Cart</a>
        <a href="/login">Login</a>
    </div>
</div>

<div class="product-container">
    <div class="product-layout">
        <div class="product-image">
            <div class="product-emoji">💨</div>
        </div>

        <div class="product-details">
            <h1>XROS 4 Mini</h1>
            <div class="product-meta">Pod System · Vaporesso</div>
            
            <div class="product-price">₱1,299.00</div>

            <div class="product-description">
                Experience superior vaping with the Vaporesso XROS 4 Mini. Featuring a compact and ergonomic design, advanced chipset technology, and an innovative pod system. Delivers smooth vapor production with exceptional flavor clarity.
            </div>

            <div style="background: var(--bg-light); border-radius: var(--radius); padding: 16px; margin-bottom: 24px;">
                <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Key Features</div>
                <ul style="margin: 0; padding-left: 20px; color: var(--text-secondary); font-size: 14px;">
                    <li>1000mAh Battery Capacity</li>
                    <li>Fast Charging Technology</li>
                    <li>Long Coil Lifespan</li>
                    <li>Enhanced Flavor Delivery</li>
                </ul>
            </div>

            <div class="product-actions">
                <a href="/cart" class="btn-primary">Add to Cart</a>
                <a href="/shop" class="btn-secondary">Back to Shop</a>
            </div>
        </div>
    </div>
</div>
@endsection