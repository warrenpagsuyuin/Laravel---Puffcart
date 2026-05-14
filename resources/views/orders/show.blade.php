@extends('layouts.app')

@section('title', $order->order_number)

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

    .order-shell {
        margin: 0 auto;
        max-width: 1100px;
        padding: 40px 20px 64px;
    }

    .grid {
        display: grid;
        gap: 22px;
        grid-template-columns: minmax(0, 1fr) 340px;
        align-items: start;
    }

    .panel {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px;
    }

    .row {
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        gap: 18px;
        padding: 12px 0;
    }

    .row:last-child {
        border-bottom: 0;
    }

    .muted {
        color: var(--text-muted);
        font-size: 13px;
    }

    .btn-secondary {
        align-items: center;
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        color: var(--text-primary);
        display: inline-flex;
        font-size: 14px;
        font-weight: 800;
        justify-content: center;
        min-height: 40px;
        padding: 9px 13px;
    }

    .btn-primary {
        align-items: center;
        background: var(--primary);
        border-radius: var(--radius);
        color: white;
        display: inline-flex;
        font-size: 14px;
        font-weight: 800;
        justify-content: center;
        min-height: 40px;
        padding: 9px 13px;
    }

    .btn-primary:hover,
    .btn-secondary:hover {
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        transform: translateY(-1px);
    }

    .notice-warning {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: var(--radius);
        color: #9a3412;
        margin-bottom: 18px;
        padding: 12px 14px;
    }

    .notice-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--radius);
        color: #15803d;
        margin-bottom: 18px;
        padding: 12px 14px;
    }

    .review-panel {
        background: #ffffff;
        border-color: #d9e2ec;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        margin-top: 22px;
        padding: 0;
        overflow: hidden;
    }

    .review-header {
        align-items: center;
        background: #f8fafc;
        border-bottom: 1px solid #d9e2ec;
        display: flex;
        gap: 16px;
        justify-content: space-between;
        padding: 20px 22px;
    }

    .review-header h2 {
        font-size: 20px;
        margin: 0;
    }

    .review-list {
        display: grid;
    }

    .review-card {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: grid;
        gap: 0;
        margin-top: 0;
        padding: 0;
    }

    .review-card:last-child {
        border-bottom: 0;
    }

    .review-row-head {
        align-items: center;
        display: grid;
        gap: 16px;
        grid-template-columns: minmax(0, 1fr) minmax(260px, auto);
        padding: 18px 22px;
    }

    .review-product {
        align-items: center;
        display: flex;
        gap: 16px;
        min-width: 0;
    }

    .review-product-image {
        align-items: center;
        background: #f8fafc;
        border: 1px solid #d9e2ec;
        border-radius: var(--radius);
        display: inline-flex;
        flex: 0 0 86px;
        height: 86px;
        justify-content: center;
        overflow: hidden;
        width: 86px;
    }

    .review-product-image img {
        height: 100%;
        object-fit: contain;
        padding: 6px;
        width: 100%;
    }

    .review-product-fallback {
        color: var(--primary);
        font-size: 18px;
        font-weight: 900;
    }

    .review-product-info {
        min-width: 0;
    }

    .review-card h3 {
        color: var(--text-primary);
        font-size: 16px;
        margin: 0;
    }

    .review-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
    }

    .review-toggle {
        text-align: left;
        width: 100%;
    }

    .review-toggle summary {
        align-items: center;
        background: var(--primary);
        border-radius: var(--radius);
        color: #ffffff;
        cursor: pointer;
        display: inline-flex;
        font-size: 13px;
        font-weight: 900;
        justify-content: center;
        list-style: none;
        min-height: 38px;
        padding: 9px 13px;
    }

    .review-toggle summary::-webkit-details-marker {
        display: none;
    }

    .review-toggle summary:hover {
        background: var(--primary-hover);
    }

    .review-toggle[open] {
        width: min(520px, 100%);
    }

    .review-form {
        background: #f8fafc;
        border: 1px solid #d9e2ec;
        border-radius: var(--radius);
        display: grid;
        gap: 12px;
        margin-top: 10px;
        padding: 16px;
    }

    .review-form label {
        color: var(--text-primary);
        display: grid;
        font-size: 13px;
        font-weight: 800;
        gap: 6px;
    }

    .review-form select,
    .review-form textarea {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        color: var(--text-primary);
        font-size: 14px;
        padding: 10px 12px;
        width: 100%;
    }

    .review-form textarea {
        min-height: 92px;
        resize: vertical;
    }

    .reviewed-box {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 14px 22px 18px;
    }

    .rating-chip {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        color: #0b66ff;
        display: inline-flex;
        font-size: 12px;
        font-weight: 900;
        padding: 5px 9px;
    }

    .status-chip {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        color: #047857;
        display: inline-flex;
        font-size: 12px;
        font-weight: 900;
        padding: 6px 10px;
        white-space: nowrap;
    }

    @media (max-width: 820px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px;
        }

        .grid {
            grid-template-columns: 1fr;
        }

        .review-header,
        .review-row-head {
            align-items: flex-start;
            grid-template-columns: 1fr;
        }

        .review-header {
            flex-direction: column;
        }

        .review-actions {
            justify-content: flex-start;
            width: 100%;
        }

        .review-product {
            align-items: flex-start;
        }

        .review-product-image {
            flex-basis: 72px;
            height: 72px;
            width: 72px;
        }
    }
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('cart') }}">Cart</a>
        <a href="{{ route('orders.index') }}">Orders</a>
        <a href="{{ route('tracking') }}">Tracking</a>
    </div>
</nav>

<main class="order-shell">
    @if(session('success'))
        <div class="notice-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="notice-warning">{{ session('error') }}</div>
    @endif

    @if(!$order->isPaymentComplete())
        <div class="notice-warning">
            Payment is required before this order can proceed to tracking or processing.
            <a href="{{ route('payment.show', $order) }}" style="font-weight:800;color:#9a3412;text-decoration:underline;">Pay now</a>
        </div>
    @endif

    <h1>{{ $order->order_number }}</h1>
    <p class="muted">{{ $order->status_label }} / {{ $order->created_at?->format('M d, Y h:i A') }}</p>

    <div class="grid">
        <section class="panel">
            <h2>Items</h2>
            @foreach($order->items as $item)
                <div class="row">
                    <div>
                        <strong>{{ $item->product_name }}</strong>
                        <div class="muted">Qty {{ $item->quantity }} at PHP {{ number_format($item->price, 2) }}</div>
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
                    </div>
                    <strong>PHP {{ number_format($item->subtotal, 2) }}</strong>
                </div>
            @endforeach
        </section>

        <aside class="panel">
            <h2>Summary</h2>
            <div class="row">
                <span>Subtotal</span>
                <strong>PHP {{ number_format($order->subtotal, 2) }}</strong>
            </div>
            <div class="row">
                <span>Delivery</span>
                <strong>PHP {{ number_format($order->delivery_fee, 2) }}</strong>
            </div>
            <div class="row">
                <span>Discount</span>
                <strong>- PHP {{ number_format($order->discount, 2) }}</strong>
            </div>
            <div class="row">
                <span>Total</span>
                <strong>PHP {{ number_format($order->total, 2) }}</strong>
            </div>
            <div class="row">
                <span>Payment</span>
                <strong>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</strong>
            </div>
            @if($order->isPaymentComplete())
                <a class="btn-secondary" href="{{ route('orders.track', $order) }}" style="width:100%;margin-top:14px;">Track Order</a>
                @if(in_array($order->status, ['completed', 'delivered'], true))
                    <a class="btn-primary" href="#product-reviews" style="width:100%;margin-top:10px;">Review Products</a>
                @endif
            @else
                <a class="btn-primary" href="{{ route('payment.show', $order) }}" style="width:100%;margin-top:14px;">Pay Now</a>
            @endif
        </aside>
    </div>

    @if(in_array($order->status, ['completed', 'delivered'], true))
        <section class="panel review-panel" id="product-reviews">
            <div class="review-header">
                <div>
                    <h2>Product Reviews</h2>
                    <p class="muted">Rate products from this completed order.</p>
                </div>
                <a class="btn-secondary" href="{{ route('orders.index') }}">Back to Orders</a>
            </div>

            <div class="review-list">
                @foreach($order->items->unique('product_id') as $item)
                    @continue(!$item->product_id)

                    @php
                        $existingReview = $reviewedProductIds->get($item->product_id);
                    @endphp

                    <article class="review-card" id="review-product-{{ $item->product_id }}">
                        <div class="review-row-head">
                            <div class="review-product">
                                <span class="review-product-image">
                                    @if($item->product?->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}">
                                    @else
                                        <span class="review-product-fallback">{{ strtoupper(substr((string) $item->product_name, 0, 2)) }}</span>
                                    @endif
                                </span>
                                <div class="review-product-info">
                                    <h3>{{ $item->product_name }}</h3>
                                    <div class="muted">Purchased in {{ $order->order_number }}</div>
                                    @if($item->flavor_label)
                                        <div class="muted">Flavor: {{ $item->flavor_label }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="review-actions">
                                @if($existingReview)
                                    <span class="status-chip">Reviewed</span>
                                    <span class="rating-chip">{{ $existingReview->rating }} / 5</span>
                                @else
                                    <details class="review-toggle">
                                        <summary>Write Review</summary>
                                        <form class="review-form" method="POST" action="{{ route('orders.reviews.store', $order) }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                            <label>
                                                Rating
                                                <select name="rating" required>
                                                    <option value="">Select rating</option>
                                                    <option value="5">5 - Excellent</option>
                                                    <option value="4">4 - Very good</option>
                                                    <option value="3">3 - Good</option>
                                                    <option value="2">2 - Fair</option>
                                                    <option value="1">1 - Poor</option>
                                                </select>
                                            </label>

                                            <label>
                                                Comment
                                                <textarea name="comment" maxlength="1000" placeholder="Tell other customers about the product"></textarea>
                                            </label>

                                            <button class="btn-primary" type="submit">Submit Review</button>
                                        </form>
                                    </details>
                                @endif
                            </div>
                        </div>

                        @if($existingReview)
                            <div class="reviewed-box">
                                <div class="muted">{{ $existingReview->comment ?: 'No comment added.' }}</div>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</main>
@endsection
