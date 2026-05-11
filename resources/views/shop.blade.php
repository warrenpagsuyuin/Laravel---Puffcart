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
        min-height: 72px;
        padding: 14px 40px;
        position: sticky;
        top: 0;
        z-index: 50;
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
        grid-template-columns: 320px minmax(0, 1fr);
        min-height: calc(100vh - 72px);
        background: #f6f8fb;
    }

    .filters {
        background: var(--bg-white);
        border-right: 1px solid var(--border);
        padding: 22px 28px;
        position: sticky;
        top: 72px;
        align-self: start;
        min-height: calc(100vh - 72px);
        overflow: visible;
        box-shadow: 8px 0 24px rgba(15, 23, 42, 0.04);
    }

    .filter-group {
        display: grid;
        gap: 7px;
        margin-bottom: 13px;
    }

    .filter-group label {
        color: #111827;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0;
    }

    .filters input,
    .filters select {
        width: 100%;
        min-height: 44px;
        padding: 9px 14px;
        border: 1px solid #d9dee8;
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
        color: #1f2937;
        box-sizing: border-box;
    }

    .filters input:focus,
    .filters select:focus {
        border-color: #0b66ff;
        box-shadow: 0 0 0 3px rgba(11, 102, 255, 0.12);
        outline: none;
    }

    .price-filter-row {
        display: grid;
        gap: 13px;
    }

    .filter-actions {
        display: grid;
        gap: 10px;
        margin-top: 16px;
    }

    .filter-actions .btn-primary {
        width: 100%;
        min-height: 46px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 15px;
        box-shadow: none;
    }

    .filter-actions .btn-secondary {
        width: 100%;
        min-height: 44px;
        padding: 10px 16px;
        border-radius: 8px;
    }

    .shop-main {
        padding: 34px 48px 44px;
        min-width: 0;
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
        gap: 24px;
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

    /* Improve mobile card spacing and button layout */
    .product-body {
        display: grid;
        gap: 12px;
        padding: 20px;
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

    .product-media {
        background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        min-height: 180px;
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

    .product-detail-line {
        color: var(--text-secondary);
        font-size: 13px;
    }

    .flavor-picker {
        display: grid;
        gap: 6px;
    }

    .flavor-picker label {
        color: var(--text-primary);
        font-size: 12px;
        font-weight: 800;
    }

    .flavor-picker select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
        font-size: 14px;
        background: #fff;
    }

    .card-actions {
        display: grid;
        gap: 8px;
        margin-top: auto;
    }

    .card-actions .btn-primary {
        width: 100%;
        padding: 12px 14px;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(11,102,255,0.08);
    }

    .card-actions .btn-secondary {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
    }

    /* corporate look: stronger primary color */
    .btn-primary {
        background: #0b66ff;
        color: #ffffff;
        border: none;
    }

    .btn-primary:hover { background: #0954d6; }

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
        margin-top: 28px;
        border-top: 1px solid #e3e8f0;
        padding-top: 22px;
    }

    .pagination nav {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
    }

    .pagination nav > div:first-child {
        display: none;
    }

    .pagination nav > div:last-child {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: space-between;
        width: 100%;
    }

    .pagination p {
        color: var(--text-secondary);
        margin: 0;
    }

    .pagination a,
    .pagination span {
        align-items: center;
        border-radius: 7px;
        display: inline-flex;
        font-size: 14px;
        font-weight: 700;
        gap: 6px;
        line-height: 1;
        min-height: 40px;
        padding: 10px 13px;
        text-decoration: none;
    }

    .pagination a {
        background: var(--bg-white);
        border: 1px solid #d9dee8;
        color: var(--text-primary);
    }

    .pagination a:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .pagination span {
        color: var(--text-secondary);
    }

    .pagination span[aria-disabled="true"],
    .pagination [aria-disabled="true"] span {
        opacity: 0.55;
    }

    .pagination [aria-current="page"] span,
    .pagination .active span {
        background: var(--primary);
        border: 1px solid var(--primary);
        color: #fff;
    }

    .pagination svg {
        display: block;
        flex: 0 0 18px;
        height: 18px;
        max-height: 18px;
        max-width: 18px;
        width: 18px;
    }

    @media (max-width: 1100px) {
        .shop-shell {
            grid-template-columns: 300px minmax(0, 1fr);
        }

        .shop-main {
            padding: 30px;
        }

        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .store-nav {
            align-items: center;
            flex-direction: row;
            gap: 12px;
            min-height: 64px;
            padding: 12px 20px;
            position: sticky;
            top: 0;
        }

        .nav-links {
            gap: 12px;
            justify-content: flex-end;
        }

        .shop-shell {
            grid-template-columns: 1fr;
            min-height: calc(100vh - 64px);
        }

        .filters {
            border-right: 0;
            border-bottom: 1px solid var(--border);
            height: auto;
            min-height: 0;
            max-height: none;
            position: static;
            padding: 18px 20px;
        }

        .filters form {
            display: grid;
            gap: 12px;
        }

        .filter-group {
            margin-bottom: 0;
        }

        .filter-group label {
            font-size: 11px;
        }

        .filters input,
        .filters select {
            min-height: 40px;
            padding: 8px 12px;
        }

        .price-filter-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 2px;
        }

        .shop-main {
            padding: 24px 20px 32px;
        }

        .shop-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .pagination nav > div:last-child {
            align-items: flex-start;
            flex-direction: column;
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
            <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
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
                <label for="nicotine_type">Nicotine Type</label>
                <select id="nicotine_type" name="nicotine_type">
                    <option value="">All nicotine types</option>
                    @foreach(\App\Models\Product::NICOTINE_TYPE_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected(request('nicotine_type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="price-filter-row">
                <div class="filter-group">
                    <label for="min_price">Min Price</label>
                    <input id="min_price" type="number" min="0" step="0.01" name="min_price" value="{{ request('min_price') }}">
                </div>

                <div class="filter-group">
                    <label for="max_price">Max Price</label>
                    <input id="max_price" type="number" min="0" step="0.01" name="max_price" value="{{ request('max_price') }}">
                </div>
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
                @php
                    $isBattery = $product->product_type === \App\Models\Product::TYPE_BATTERY;
                    $isBundle = $product->product_type === \App\Models\Product::TYPE_BUNDLE;
                    $availableFlavors = $isBattery ? collect() : $product->availableFlavorOptions;
                    $availableColors = ($isBattery || $isBundle) ? $product->availableColorOptions : collect();
                    $canAddFromCard = $isBattery ? $availableColors->isNotEmpty() : ($isBundle ? $availableFlavors->isNotEmpty() && $availableColors->isNotEmpty() : $availableFlavors->isNotEmpty());
                @endphp
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
                        <div class="stock">{{ $product->available_stock > 0 ? $product->available_stock . ' in stock' : 'Out of stock' }}</div>
                        <div class="product-detail-line">Type: {{ $product->product_type_label }}</div>
                        @if($product->nicotine_profile)
                            <div class="product-detail-line">Nicotine: {{ $product->nicotine_profile }}</div>
                        @endif
                        @if($product->volume_label)
                            <div class="product-detail-line">Size: {{ $product->volume_label }}</div>
                        @endif

                        <div class="card-actions">
                            <a class="btn-secondary" href="{{ route('product.show', $product) }}">View</a>
                            @auth
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="product_type" value="{{ $product->product_type ?: \App\Models\Product::TYPE_OTHER }}">
                                    @if($isBattery)
                                        @if($availableColors->isNotEmpty())
                                            <div class="flavor-picker">
                                                <label for="product-color-{{ $product->id }}">Color</label>
                                                <select id="product-color-{{ $product->id }}" name="product_flavor_id" required>
                                                    <option value="">Choose color</option>
                                                    @foreach($availableColors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }} ({{ $color->stock }} left)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @else
                                        @if($availableFlavors->isNotEmpty())
                                            <div class="flavor-picker">
                                                <label for="product-flavor-{{ $product->id }}">Flavor</label>
                                                <select id="product-flavor-{{ $product->id }}" name="product_flavor_id" required>
                                                    <option value="">Choose flavor</option>
                                                    @foreach($availableFlavors as $flavor)
                                                        <option value="{{ $flavor->id }}">{{ $flavor->name }} ({{ $flavor->stock }} left)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endif
                                    @if($isBundle && $availableColors->isNotEmpty())
                                        <div class="flavor-picker">
                                            <label for="battery-color-{{ $product->id }}">Battery Color</label>
                                            <select id="battery-color-{{ $product->id }}" name="battery_color_id" required>
                                                <option value="">Choose color</option>
                                                @foreach($availableColors as $color)
                                                    <option value="{{ $color->id }}">{{ $color->name }} ({{ $color->stock }} left)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <button class="btn-primary" name="intent" value="add_to_cart" type="submit" @disabled(!$canAddFromCard)>Add to Cart</button>
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
                                @if($product->nicotine_profile)
                                    <div class="product-detail-line">Nicotine: {{ $product->nicotine_profile }}</div>
                                @endif
                                @if($product->volume_label)
                                    <div class="product-detail-line">Size: {{ $product->volume_label }}</div>
                                @endif
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
