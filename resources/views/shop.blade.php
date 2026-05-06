@extends('layouts.app')

@section('title', 'Shop')

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

    .shop-shell {
        display: grid;
        grid-template-columns: 280px minmax(0, 1fr);
        min-height: calc(100vh - 65px);
    }

    .filters {
        background: var(--bg-light);
        border-right: 1px solid var(--border);
        padding: 28px 24px;
    }

    .filter-group {
        display: grid;
        gap: 8px;
        margin-bottom: 18px;
    }

    .filter-group label {
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 700;
    }

    .filter-actions {
        display: grid;
        gap: 10px;
        margin-top: 22px;
    }

    .shop-main {
        padding: 36px 40px;
    }

    .shop-heading {
        align-items: end;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
    }

    .shop-heading h1 {
        font-size: 32px;
        margin-bottom: 6px;
    }

    .muted {
        color: var(--text-muted);
        font-size: 14px;
    }

    .products-grid {
        display: grid;
        gap: 20px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .product-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        min-height: 100%;
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
    }

    .product-media {
        align-items: center;
        aspect-ratio: 4 / 3;
        background: var(--bg-light);
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
        font-size: 42px;
        font-weight: 700;
    }

    .product-body {
        display: grid;
        flex: 1;
        gap: 10px;
        padding: 18px;
    }

    .product-meta {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .product-title {
        color: var(--text-primary);
        font-size: 17px;
        font-weight: 700;
        line-height: 1.35;
    }

    .price-row {
        align-items: baseline;
        display: flex;
        gap: 8px;
    }

    .price {
        color: var(--primary);
        font-size: 20px;
        font-weight: 800;
    }

    .old-price {
        color: var(--text-muted);
        font-size: 13px;
        text-decoration: line-through;
    }

    .stock {
        color: var(--text-secondary);
        font-size: 13px;
    }

    .card-actions {
        display: grid;
        gap: 8px;
        margin-top: auto;
    }

    .btn-primary,
    .btn-secondary {
        align-items: center;
        border-radius: var(--radius);
        display: inline-flex;
        font-size: 14px;
        font-weight: 700;
        justify-content: center;
        min-height: 42px;
        padding: 10px 14px;
        text-align: center;
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
        margin-bottom: 16px;
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

    .recommendations {
        border-top: 1px solid var(--border);
        margin-top: 38px;
        padding-top: 28px;
    }

    .pagination {
        margin-top: 24px;
    }

    @media (max-width: 1100px) {
        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px;
        }

        .shop-shell {
            grid-template-columns: 1fr;
        }

        .filters {
            border-right: 0;
            border-bottom: 1px solid var(--border);
        }

        .shop-main {
            padding: 26px 20px;
        }

        .shop-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .products-grid {
            grid-template-columns: 1fr;
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

<div class="shop-shell">
    <aside class="filters">
        <form method="GET" action="{{ route('shop') }}">
            <div class="filter-group">
                <label for="search">Search</label>
                <input id="search" name="search" value="{{ request('search') }}" placeholder="Product, brand, flavor">
            </div>

            <div class="filter-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="brand">Brand</label>
                <select id="brand" name="brand">
                    <option value="">All brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand }}" @selected(request('brand') === $brand)>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="min_price">Min Price</label>
                <input id="min_price" type="number" min="0" step="0.01" name="min_price" value="{{ request('min_price') }}">
            </div>

            <div class="filter-group">
                <label for="max_price">Max Price</label>
                <input id="max_price" type="number" min="0" step="0.01" name="max_price" value="{{ request('max_price') }}">
            </div>

            <div class="filter-group">
                <label for="sort">Sort</label>
                <select id="sort" name="sort">
                    <option value="recommended" @selected(request('sort', 'recommended') === 'recommended')>Recommended</option>
                    <option value="popular" @selected(request('sort') === 'popular')>Popular</option>
                    <option value="rating" @selected(request('sort') === 'rating')>Top rated</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price low to high</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price high to low</option>
                    <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                </select>
            </div>

            <div class="filter-actions">
                <button class="btn-primary" type="submit">Apply</button>
                <a class="btn-secondary" href="{{ route('shop') }}">Reset</a>
            </div>
        </form>
    </aside>

    <main class="shop-main">
        @if(session('success'))
            <div class="notice notice-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="notice notice-error">{{ session('error') }}</div>
        @endif

        <div class="shop-heading">
            <div>
                <h1>Shop Products</h1>
                <div class="muted">{{ number_format($products->total()) }} item(s) available</div>
            </div>
        </div>

        <div class="products-grid">
            @forelse($products as $product)
                <article class="product-card">
                    <a class="product-media" href="{{ route('product.show', $product) }}">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        @else
                            <span class="product-placeholder">PC</span>
                        @endif
                    </a>

                    <div class="product-body">
                        <div class="product-meta">{{ $product->category_name }}{{ $product->brand ? ' / ' . $product->brand : '' }}</div>
                        <a class="product-title" href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                        <div class="price-row">
                            <span class="price">PHP {{ number_format($product->price, 2) }}</span>
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="old-price">PHP {{ number_format($product->original_price, 2) }}</span>
                            @endif
                        </div>
                        <div class="stock">{{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}</div>

                        <div class="card-actions">
                            <a class="btn-secondary" href="{{ route('product.show', $product) }}">View</a>
                            @auth
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn-primary" type="submit" @disabled($product->stock < 1)>Add to Cart</button>
                                </form>
                            @else
                                <a class="btn-primary" href="{{ route('login') }}">Login to Buy</a>
                            @endauth
                        </div>
                    </div>
                </article>
            @empty
                <p class="muted">No products match your filters.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $products->links() }}
        </div>

        @if($recommendedProducts->isNotEmpty())
            <section class="recommendations">
                <div class="shop-heading">
                    <div>
                        <h2>Recommended Picks</h2>
                        <div class="muted">Based on product demand and your store activity.</div>
                    </div>
                </div>

                <div class="products-grid">
                    @foreach($recommendedProducts as $product)
                        <article class="product-card">
                            <a class="product-media" href="{{ route('product.show', $product) }}">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                @else
                                    <span class="product-placeholder">PC</span>
                                @endif
                            </a>
                            <div class="product-body">
                                <div class="product-meta">{{ $product->category_name }}</div>
                                <a class="product-title" href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                                <div class="price">PHP {{ number_format($product->price, 2) }}</div>
                                <a class="btn-secondary" href="{{ route('product.show', $product) }}">View</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</div>
@endsection
