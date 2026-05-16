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
        justify-content: flex-end;
        margin-left: auto;
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

    .product-shell {
        margin: 0 auto;
        max-width: 1500px;
        padding: 28px 32px 64px;
    }

    .detail-grid {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        display: grid;
        gap: 34px;
        grid-template-columns: minmax(430px, 0.8fr) minmax(0, 1.2fr);
        align-items: start;
        padding: 28px;
    }

    .product-gallery {
        display: grid;
        gap: 14px;
    }

    .product-media {
        align-items: center;
        aspect-ratio: 1 / 1;
        background:
            radial-gradient(circle at 50% 36%, rgba(11, 99, 246, 0.10), transparent 34%),
            linear-gradient(180deg, #f7faff 0%, #ffffff 100%);
        border: 1px solid var(--border);
        border-radius: 6px;
        display: flex;
        justify-content: center;
        overflow: hidden;
    }

    .product-media img {
        height: 100%;
        object-fit: contain;
        padding: 20px;
        width: 100%;
    }

    .product-placeholder {
        color: var(--primary);
        font-family: 'Poppins', sans-serif;
        font-size: 72px;
        font-weight: 800;
    }

    

    .product-summary {
        display: grid;
        gap: 16px;
        min-width: 0;
    }

    .eyebrow {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.04em;
        margin-bottom: -6px;
        text-transform: uppercase;
    }

    .product-title {
        font-size: 34px;
        line-height: 1.18;
        margin-bottom: 0;
    }

    .rating {
        color: var(--text-secondary);
        font-size: 14px;
        margin-bottom: -4px;
    }

    .price-row {
        align-items: baseline;
        background: #f6f9ff;
        border: 1px solid #e0ebff;
        border-radius: 4px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 2px;
        padding: 18px 20px;
    }

    .price {
        color: var(--primary);
        font-size: 38px;
        font-weight: 800;
    }

    .old-price {
        color: var(--text-muted);
        text-decoration: line-through;
    }

    .description {
        color: var(--text-secondary);
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 0;
        max-width: none;
    }

    .muted {
        color: var(--text-muted);
        font-size: 14px;
    }

    .commerce-panel {
        background: transparent;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        display: grid;
        gap: 18px;
        padding: 0;
    }

    .facts {
        display: grid;
        gap: 0;
    }

    .fact-row {
        border-bottom: 0;
        display: grid;
        gap: 18px;
        grid-template-columns: 140px minmax(0, 1fr);
        font-size: 14px;
        padding: 8px 0;
    }

    .fact-row:first-child {
        padding-top: 0;
    }

    .fact-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .fact-row span:first-child {
        color: var(--text-muted);
        font-weight: 600;
    }

    .fact-row span:last-child {
        color: var(--text-primary);
        font-weight: 600;
        line-height: 1.65;
        text-align: left;
        overflow-wrap: anywhere;
    }

    .purchase-form {
        display: grid;
        gap: 16px;
        border-top: 1px solid var(--border);
        padding-top: 18px;
    }

    .choice-field {
        align-items: center;
        display: grid;
        gap: 18px;
        grid-template-columns: 140px minmax(0, 1fr);
    }

    .choice-field label {
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
    }

    .choice-field select {
        min-height: 44px;
    }

    .quantity-row {
        align-items: center;
        display: grid;
        gap: 18px;
        grid-template-columns: 140px 140px;
    }

    .quantity-row::before {
        color: var(--text-muted);
        content: "Quantity";
        font-size: 14px;
        font-weight: 600;
    }

    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        padding-top: 8px;
    }

    .btn-primary,
    .btn-secondary {
        align-items: center;
        border-radius: var(--radius);
        display: inline-flex;
        font-size: 14px;
        font-weight: 800;
        justify-content: center;
        min-height: 48px;
        padding: 11px 16px;
    }

    .btn-cart {
        background: #eff6ff;
        border: 1px solid var(--primary);
        color: var(--primary);
        gap: 10px;
        min-width: 224px;
    }

    .btn-cart:hover {
        background: var(--primary-light);
        color: var(--primary-hover);
    }

    .cart-icon {
        height: 20px;
        width: 20px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        min-width: 224px;
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

    .purchase-form > .btn-secondary {
        justify-self: start;
        min-width: 220px;
        width: 220px;
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
        margin-top: 42px;
        padding-top: 28px;
    }

    .section h2 {
        font-size: 26px;
        margin-bottom: 14px;
    }

    .cards {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .mini-card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
    }

    .related-card {
        background: var(--bg-white);
        display: flex;
        flex-direction: column;
        min-height: 100%;
        overflow: hidden;
        padding: 0;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .related-card:hover {
        border-color: var(--primary);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }

    .related-media {
        align-items: center;
        aspect-ratio: 4 / 3;
        background:
            radial-gradient(circle at 50% 36%, rgba(11, 99, 246, 0.12), transparent 34%),
            linear-gradient(180deg, #f6f9fd 0%, #ffffff 100%);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: center;
        min-height: 132px;
        overflow: hidden;
    }

    .related-media img {
        display: block;
        height: 100%;
        object-fit: contain;
        padding: 14px;
        width: 100%;
    }

    .related-placeholder {
        align-items: center;
        background: linear-gradient(135deg, var(--primary), #4a93ff);
        border-radius: 14px;
        color: #ffffff;
        display: inline-flex;
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 800;
        height: 72px;
        justify-content: center;
        width: 72px;
    }

    .related-body {
        display: grid;
        gap: 6px;
        padding: 14px 16px 16px;
    }

    .mini-card strong {
        color: var(--text-primary);
        display: block;
        line-height: 1.4;
        margin-bottom: 2px;
    }

    .mini-card span {
        color: var(--primary);
        font-weight: 800;
    }

    @media (max-width: 1180px) and (min-width: 861px) {
        .product-shell {
            padding: 36px 32px 60px;
        }

        .detail-grid {
            grid-template-columns: minmax(360px, 0.86fr) minmax(0, 1.14fr);
        }
    }

    @media (max-width: 860px) {
        .store-nav {
            align-items: flex-end;
            gap: 14px;
            padding: 16px 20px;
        }

        .product-shell {
            padding: 30px 22px 56px;
        }

        .detail-grid {
            gap: 28px;
            grid-template-columns: 1fr;
        }

        .cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .product-media {
            position: static;
        }

        .product-title {
            font-size: 30px;
        }

        .product-summary {
            gap: 18px;
        }

        .choice-field,
        .quantity-row {
            grid-template-columns: 1fr;
        }

        .action-row,
        .purchase-form > .btn-secondary {
            display: grid;
            grid-template-columns: 1fr;
        }

        .btn-primary,
        .btn-cart,
        .purchase-form > .btn-secondary {
            justify-self: stretch;
            min-width: 0;
            width: 100%;
        }

        .quantity-row::before {
            content: "Quantity";
        }
    }

    @media (max-width: 640px) {
        .product-shell {
            padding: 28px 16px 52px;
        }

        .product-summary {
            display: block;
        }

        .commerce-panel {
            margin-top: 0;
            padding: 18px;
        }

        .product-media img {
            padding: 12px;
        }

        .fact-row {
            gap: 6px;
            grid-template-columns: 1fr;
        }

        .cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .purchase-form > .btn-primary,
        .purchase-form > .btn-secondary,
        .btn-cart {
            min-width: 0;
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .cards {
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
            <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>
</nav>

<main class="product-shell">
    @php
        $isBattery = ($product->product_type ?? null) === \App\Models\Product::TYPE_BATTERY;
        $isBundle = ($product->product_type ?? null) === \App\Models\Product::TYPE_BUNDLE;
        $categoryText = strtolower((string) $product->category_name);
        $usesOhmOptions = str_contains($categoryText, 'coils') && str_contains($categoryText, 'pods');
        $usesColorOptions = str_contains($categoryText, 'accessories')
            || str_contains($categoryText, 'devices');
        $optionLabel = $usesOhmOptions ? 'Ohm' : ($usesColorOptions ? 'Color' : 'Flavor');
        $optionPluralLabel = $usesOhmOptions ? 'Ohms' : ($usesColorOptions ? 'Colors' : 'Flavors');
        $availableFlavors = $isBattery ? collect() : ($product->availableFlavorOptions ?? collect());
        $availableColors = ($isBattery || $isBundle) ? ($product->availableColorOptions ?? collect()) : collect();

        $selectedFlavorId = old('product_flavor_id');
        $selectedFlavor = $availableFlavors->firstWhere('id', (int) $selectedFlavorId) ?: $availableFlavors->first();
        $selectedColorId = old($isBattery ? 'product_flavor_id' : 'battery_color_id');
        $selectedColor = $availableColors->firstWhere('id', (int) $selectedColorId) ?: $availableColors->first();

        if ($isBundle) {
            $podStock = $selectedFlavor?->stock ?? 0;
            $batteryStock = $selectedColor?->stock ?? 0;
            $maxAvailable = max(0, min($podStock, $batteryStock));
        } elseif ($isBattery) {
            $maxAvailable = max(0, $selectedColor?->stock ?? 0);
        } else {
            $maxAvailable = max(0, $selectedFlavor?->stock ?? 0);
        }

        $canPurchase = $maxAvailable > 0;
    @endphp
    

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
        <div class="product-gallery">
            <div class="product-media">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                @else
                    <span class="product-placeholder">PC</span>
                @endif
            </div>
        </div>

        <section class="product-summary">
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

            <div class="commerce-panel">
                <div class="facts">
                    <div class="fact-row">
                        <span>Product Type</span>
                        <span>{{ $product->product_type_label }}</span>
                    </div>
                    @if($product->nicotine_profile)
                        <div class="fact-row">
                            <span>Nicotine Type</span>
                            <span>{{ $product->nicotine_profile }}</span>
                        </div>
                    @endif
                    @if($product->volume_label)
                        <div class="fact-row">
                            <span>Bottle Size</span>
                            <span>{{ $product->volume_label }}</span>
                        </div>
                    @endif
                    @if($availableFlavors->isNotEmpty())
                        <div class="fact-row">
                            <span>Available {{ $optionPluralLabel }}</span>
                            <span>{{ $availableFlavors->pluck('name')->implode(', ') }}</span>
                        </div>
                    @endif
                    @if($availableColors->isNotEmpty())
                        <div class="fact-row">
                            <span>Battery Colors</span>
                            <span>{{ $availableColors->pluck('name')->implode(', ') }}</span>
                        </div>
                    @endif
                    @if($product->bundle_description)
                        <div class="fact-row">
                            <span>Bundle Includes</span>
                            <span>{{ $product->bundle_description }}</span>
                        </div>
                    @endif
                    <div class="fact-row">
                        <span>Demand</span>
                        <span>{{ number_format((int) ($product->sales_count ?? 0)) }} sold</span>
                    </div>
                </div>

                @auth
                    <form class="purchase-form" method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_type" value="{{ $product->product_type ?: \App\Models\Product::TYPE_OTHER }}">
                        @if($isBattery)
                            @if($availableColors->isNotEmpty())
                                <div class="choice-field">
                                    <label for="product_flavor_id">Color</label>
                                    <select id="product_flavor_id" name="product_flavor_id" required>
                                        <option value="">Choose color</option>
                                        @foreach($availableColors as $color)
                                            <option value="{{ $color->id }}" data-stock="{{ $color->stock }}" @selected((string) old('product_flavor_id') === (string) $color->id)>
                                                {{ $color->name }} ({{ $color->stock }} left)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="notice notice-error">No colors are currently in stock for this product.</div>
                            @endif
                        @elseif($isBundle)
                            <div class="choice-field">
                                <label for="product_flavor_id">{{ $optionLabel }} (Pods)</label>
                                @if($availableFlavors->isNotEmpty())
                                    <select id="product_flavor_id" name="product_flavor_id" required>
                                        <option value="">Choose {{ strtolower($optionLabel) }}</option>
                                        @foreach($availableFlavors as $flavor)
                                            <option value="{{ $flavor->id }}" data-stock="{{ $flavor->stock }}" @selected((string) old('product_flavor_id') === (string) $flavor->id)>
                                                {{ $flavor->name }} ({{ $flavor->stock }} left)
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="notice notice-error">No pod {{ strtolower($optionPluralLabel) }} are currently in stock for this product.</div>
                                @endif
                            </div>

                            <div class="choice-field">
                                <label for="battery_color_id">Color (Battery)</label>
                                @if($availableColors->isNotEmpty())
                                    <select id="battery_color_id" name="battery_color_id" required>
                                        <option value="">Choose color</option>
                                        @foreach($availableColors as $color)
                                            <option value="{{ $color->id }}" data-stock="{{ $color->stock }}" @selected((string) old('battery_color_id') === (string) $color->id)>
                                                {{ $color->name }} ({{ $color->stock }} left)
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="notice notice-error">No battery colors are currently in stock for this bundle.</div>
                                @endif
                            </div>
                        @else
                            @if($availableFlavors->isNotEmpty())
                                <div class="choice-field">
                                    <label for="product_flavor_id">{{ $optionLabel }}</label>
                                    <select id="product_flavor_id" name="product_flavor_id" required>
                                        <option value="">Choose {{ strtolower($optionLabel) }}</option>
                                        @foreach($availableFlavors as $flavor)
                                            <option value="{{ $flavor->id }}" data-stock="{{ $flavor->stock }}" @selected((string) old('product_flavor_id') === (string) $flavor->id)>
                                                {{ $flavor->name }} ({{ $flavor->stock }} left)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="notice notice-error">No {{ strtolower($optionPluralLabel) }} are currently in stock for this product.</div>
                            @endif
                        @endif
                        <div class="quantity-row">
                            <input id="quantity" type="number" name="quantity" min="1" max="{{ max(1, $maxAvailable) }}" value="{{ old('quantity', 1) }}" @disabled(!$canPurchase)>
                        </div>
                        <div class="action-row">
                            <button class="btn-secondary btn-cart" name="intent" value="add_to_cart" type="submit" @disabled(!$canPurchase)>
                                <svg class="cart-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M6.5 7h14l-1.5 8.5h-11L6.5 7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="M6.5 7 5.8 4H3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 20.2a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4ZM17 20.2a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z" fill="currentColor"/>
                                    <path d="M15 10v3M13.5 11.5h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                Add To Cart
                            </button>
                            <button class="btn-primary" name="intent" value="buy_now" type="submit" @disabled(!$canPurchase)>Buy Now</button>
                        </div>
                    </form>
                @else
                    <div class="purchase-form">
                        <a class="btn-primary" href="{{ route('login') }}">Login to Buy</a>
                    </div>
                @endauth
            </div>
        </section>
    </div>

    @if($related->isNotEmpty())
        <section class="section">
            <h2>Related Products</h2>
            <div class="cards">
                @foreach($related as $item)
                    <a class="mini-card related-card" href="{{ route('product.show', $item) }}">
                        <div class="related-media">
                            @if($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy" decoding="async">
                            @else
                                <span class="related-placeholder">PC</span>
                            @endif
                        </div>
                        <div class="related-body">
                            <strong>{{ $item->name }}</strong>
                            <div class="muted">{{ $item->category_name }}</div>
                            <span>PHP {{ number_format($item->price, 2) }}</span>
                        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flavorSelect = document.getElementById('product_flavor_id');
        const batteryColorSelect = document.getElementById('battery_color_id');
        const quantityInput = document.getElementById('quantity');

        if (!flavorSelect || !quantityInput) return;

        function selectedStock(select) {
            if (!select) return null;

            const selected = select.options[select.selectedIndex];

            return Number(selected ? selected.dataset.stock : 0) || 0;
        }

        function syncQuantityLimit() {
            const flavorStock = selectedStock(flavorSelect);
            const colorStock = selectedStock(batteryColorSelect);
            const stock = colorStock === null ? flavorStock : Math.min(flavorStock, colorStock);

            quantityInput.max = String(Math.max(1, stock));

            if (Number(quantityInput.value) > stock) {
                quantityInput.value = stock > 0 ? String(stock) : '1';
            }
        }

        flavorSelect.addEventListener('change', syncQuantityLimit);
        if (batteryColorSelect) {
            batteryColorSelect.addEventListener('change', syncQuantityLimit);
        }
        syncQuantityLimit();
    });
</script>
@endsection
