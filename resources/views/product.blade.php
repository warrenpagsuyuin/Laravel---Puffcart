@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    .store-nav {
        align-items: center;
        background: var(--bg-white);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        padding: 16px 40px;
    }

    .nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 22px;
        justify-content: flex-end;
        margin-left: auto;
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

    .choice-field {
        display: grid;
        gap: 6px;
    }

    .choice-field label {
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 800;
    }

    .choice-field select {
        min-height: 44px;
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
            align-items: flex-end;
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
                        <span>Available Flavors</span>
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
                    <span>Stock</span>
                    <span>{{ $product->available_stock > 0 ? $product->available_stock . ' available' : 'Out of stock' }}</span>
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
                            <label for="product_flavor_id">Flavor (Pods)</label>
                            @if($availableFlavors->isNotEmpty())
                                <select id="product_flavor_id" name="product_flavor_id" required>
                                    <option value="">Choose flavor</option>
                                    @foreach($availableFlavors as $flavor)
                                        <option value="{{ $flavor->id }}" data-stock="{{ $flavor->stock }}" @selected((string) old('product_flavor_id') === (string) $flavor->id)>
                                            {{ $flavor->name }} ({{ $flavor->stock }} left)
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="notice notice-error">No pod flavors are currently in stock for this product.</div>
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
                                <label for="product_flavor_id">Flavor</label>
                                <select id="product_flavor_id" name="product_flavor_id" required>
                                    <option value="">Choose flavor</option>
                                    @foreach($availableFlavors as $flavor)
                                        <option value="{{ $flavor->id }}" data-stock="{{ $flavor->stock }}" @selected((string) old('product_flavor_id') === (string) $flavor->id)>
                                            {{ $flavor->name }} ({{ $flavor->stock }} left)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="notice notice-error">No flavors are currently in stock for this product.</div>
                        @endif
                    @endif
                    <div class="quantity-row">
                        <input id="quantity" type="number" name="quantity" min="1" max="{{ max(1, $maxAvailable) }}" value="{{ old('quantity', 1) }}" @disabled(!$canPurchase)>
                        <button class="btn-primary" name="intent" value="add_to_cart" type="submit" @disabled(!$canPurchase)>Add to Cart</button>
                    </div>
                    <button class="btn-primary" name="intent" value="buy_now" type="submit" @disabled(!$canPurchase)>Buy Now</button>
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
