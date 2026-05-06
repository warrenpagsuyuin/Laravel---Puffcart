@extends('layouts.app')

@section('title', 'Shop')

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

    .shop-container {
        display: grid;
        grid-template-columns: 220px 1fr;
        min-height: calc(100vh - 58px);
    }

    .sidebar {
        background: var(--bg-light);
        border-right: 1px solid var(--border);
        padding: 32px 24px;
    }

    .sidebar h3 {
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .filter-group:last-child {
        margin-bottom: 0;
    }

    .filter-option {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .filter-option input {
        cursor: pointer;
    }

    .filter-option label {
        margin: 0;
        color: var(--text-secondary);
        font-size: 13px;
        cursor: pointer;
        flex: 1;
    }

    .main-content {
        padding: 40px;
        overflow-y: auto;
    }

    .content-header {
        margin-bottom: 40px;
    }

    .content-header h1 {
        font-size: 32px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .content-header p {
        color: var(--text-muted);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .product-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }

    .product-image {
        background: var(--bg-light);
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        border-bottom: 1px solid var(--border);
    }

    .product-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-category {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .product-card h3 {
        font-size: 16px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .product-brand {
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 12px;
    }

    .product-price {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 16px;
        flex: 1;
    }

    .product-btn {
        display: block;
        text-align: center;
        padding: 10px 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .product-btn:hover {
        background: var(--primary-hover);
        box-shadow: var(--shadow-md);
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    @media (max-width: 1200px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .shop-container {
            grid-template-columns: 1fr;
        }

        .sidebar {
            display: none;
        }

        .main-content {
            padding: 24px;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="nav">
    <div class="logo">Puffcart</div>
    <div class="nav-links">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/shop') }}">Shop</a>
        <a href="{{ url('/cart') }}">Cart</a>
        <a href="{{ url('/login') }}">Login</a>
        <a href="{{ url('/tracking') }}">Tracking</a>
    </div>
</div>

<div class="shop-container">

    <div class="sidebar">
        <h3>Filters</h3>

        <div class="filter-group">
            <div class="filter-option">
                <input type="checkbox" id="filter1" checked>
                <label for="filter1">Devices</label>
            </div>
            <div class="filter-option">
                <input type="checkbox" id="filter2">
                <label for="filter2">E-Liquids</label>
            </div>
            <div class="filter-option">
                <input type="checkbox" id="filter3">
                <label for="filter3">Coils & Pods</label>
            </div>
            <div class="filter-option">
                <input type="checkbox" id="filter4">
                <label for="filter4">Accessories</label>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="content-header">
            <h1>All Products</h1>
            <p>Discover our premium vaping collection</p>
        </div>

        <div class="products-grid">
            @forelse($products as $product)
                <div class="product-card">
                    <div class="product-image">💨</div>
                    <div class="product-body">
                        <div class="product-category">{{ $product->category }}</div>
                        <h3>{{ $product->name }}</h3>
                        <div class="product-brand">{{ $product->brand }}</div>
                        <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                        <a href="{{ url('/product') }}" class="product-btn">View Product</a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>No products found. Please check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection