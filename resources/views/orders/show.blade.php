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

    .notice-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--radius);
        color: #15803d;
        margin-bottom: 18px;
        padding: 12px 14px;
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
            <a class="btn-secondary" href="{{ route('orders.track', $order) }}" style="width:100%;margin-top:14px;">Track Order</a>
        </aside>
    </div>
</main>
@endsection
