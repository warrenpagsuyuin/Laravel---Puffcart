@extends('layouts.app')

@section('title', $product->name)

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

    .product-shell {
        margin: 0 auto;
        max-width: 1180px;
        padding: 40px 20px 64px;
    }

    .detail-grid {
        display: grid;
        gap: 44px;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        align-items: start;
    }

    .product-media {
        align-items: center;
        aspect-ratio: 1 / 1;
        background: var(--bg-light);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        display: flex;
        justify-content: center;
        overflow: hidden;
    }

    .product-media img {
        height: 100%;
        object-fit: cover;
        width: 100%;
    }

    .product-placeholder {
        color: var(--primary);
        font-family: 'Poppins', sans-serif;
        font-size: 72px;
        font-weight: 800;
    }

    .eyebrow {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .product-title {
        font-size: 36px;
        line-height: 1.15;
        margin-bottom: 12px;
    }

    .rating {
        color: var(--text-secondary);
        font-size: 14px;
        margin-bottom: 24px;
    }

    .price-row {
        align-items: baseline;
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
    }

    .price {
        color: var(--primary);
        font-size: 32px;
        font-weight: 800;
    }

    .old-price {
        color: var(--text-muted);
        text-decoration: line-through;
    }

    .description {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 24px;
    }

    .muted {
        color: var(--text-muted);
        font-size: 14px;
    }

    .facts {
        border-bottom: 1px solid var(--border);
        border-top: 1px solid var(--border);
        display: grid;
        gap: 12px;
        margin-bottom: 24px;
        padding: 18px 0;
    }

    .fact-row {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        font-size: 14px;
    }

    .fact-row span:first-child {
        color: var(--text-muted);
        font-weight: 700;
    }

    .fact-row span:last-child {
        color: var(--text-primary);
        font-weight: 700;
        text-align: right;
    }

    .purchase-form {
        display: grid;
        gap: 12px;
        max-width: 360px;
    }

    .quantity-row {
        display: grid;
        gap: 8px;
        grid-template-columns: 110px minmax(0, 1fr);
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

    .btn-secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
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

    .section {
        border-top: 1px solid var(--border);
        margin-top: 44px;
        padding-top: 30px;
    }

    .cards {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .mini-card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
    }

    .mini-card strong {
        color: var(--text-primary);
        display: block;
        margin-bottom: 8px;
    }

    .mini-card span {
        color: var(--primary);
        font-weight: 800;
    }

    @media (max-width: 860px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px;
        }

        .detail-grid,
        .cards {
            grid-template-columns: 1fr;
        }

        .product-title {
            font-size: 30px;
        }
    }
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('cart') }}">Cart</a>
        <a href="{{ route('tracking') }}">Tracking</a>
        @auth
            <a href="{{ route('profile') }}">Profile</a>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>
</nav>

<main class="product-shell">
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

    <div class="detail-grid">
        <div class="product-media">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
                <span class="product-placeholder">PC</span>
            @endif
        </div>

        <section>
            <div class="eyebrow">{{ $product->category_name }}{{ $product->brand ? ' / ' . $product->brand : '' }}</div>
            <h1 class="product-title">{{ $product->name }}</h1>
            <div class="rating">Rated {{ number_format((float) $product->rating, 1) }} / 5</div>

            <div class="price-row">
                <div class="price">PHP {{ number_format($product->price, 2) }}</div>
                @if($product->original_price && $product->original_price > $product->price)
                    <div class="old-price">PHP {{ number_format($product->original_price, 2) }}</div>
                @endif
            </div>

            <p class="description">{{ $product->description ?: 'No product description is available yet.' }}</p>

            <div class="facts">
                <div class="fact-row">
                    <span>SKU</span>
                    <span>{{ $product->sku ?: 'N/A' }}</span>
                </div>
                <div class="fact-row">
                    <span>Stock</span>
                    <span>{{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}</span>
                </div>
                <div class="fact-row">
                    <span>Demand</span>
                    <span>{{ number_format((int) ($product->sales_count ?? 0)) }} sold</span>
                </div>
            </div>

            @auth
                <form class="purchase-form" method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="quantity-row">
                        <input type="number" name="quantity" min="1" max="{{ max(1, $product->stock) }}" value="1" @disabled($product->stock < 1)>
                        <button class="btn-primary" type="submit" @disabled($product->stock < 1)>Add to Cart</button>
                    </div>
                    <a class="btn-secondary" href="{{ route('shop') }}">Back to Shop</a>
                </form>
            @else
                <div class="purchase-form">
                    <a class="btn-primary" href="{{ route('login') }}">Login to Buy</a>
                    <a class="btn-secondary" href="{{ route('shop') }}">Back to Shop</a>
                </div>
            @endauth
        </section>
    </div>

    @if($related->isNotEmpty())
        <section class="section">
            <h2>Related Products</h2>
            <div class="cards">
                @foreach($related as $item)
                    <a class="mini-card" href="{{ route('product.show', $item) }}">
                        <strong>{{ $item->name }}</strong>
                        <div class="muted">{{ $item->category_name }}</div>
                        <span>PHP {{ number_format($item->price, 2) }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section">
        <h2>Reviews</h2>
        @forelse($product->reviews as $review)
            <div class="mini-card" style="margin-top:12px;">
                <strong>{{ $review->user?->name ?? 'Customer' }} / {{ $review->rating }} out of 5</strong>
                <div class="muted">{{ $review->comment }}</div>
            </div>
        @empty
            <p class="muted">No reviews yet.</p>
        @endforelse
    </section>
</main>
@endsection
