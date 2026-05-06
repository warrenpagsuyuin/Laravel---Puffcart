@extends('layouts.app')

@section('title', 'Tracking')

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

    .tracking-shell {
        margin: 0 auto;
        max-width: 1040px;
        padding: 40px 20px 64px;
    }

    .orders-list {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .order-row {
        align-items: center;
        background: var(--bg-white);
        border-bottom: 1px solid var(--border);
        display: grid;
        gap: 16px;
        grid-template-columns: minmax(0, 1fr) 160px 150px 120px;
        padding: 18px;
    }

    .order-row:last-child {
        border-bottom: 0;
    }

    .muted {
        color: var(--text-muted);
        font-size: 13px;
    }

    .badge {
        border-radius: 999px;
        display: inline-flex;
        font-size: 12px;
        font-weight: 800;
        padding: 5px 10px;
    }

    .badge-green {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-yellow {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-red {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-blue {
        background: var(--primary-light);
        color: var(--primary);
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

    .empty {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 36px;
        text-align: center;
    }

    .pagination {
        margin-top: 18px;
    }

    @media (max-width: 780px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px;
        }

        .order-row {
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
        <a href="{{ route('profile') }}">Profile</a>
    </div>
</nav>

<main class="tracking-shell">
    <h1>Order Tracking</h1>

    @if($orders->isEmpty())
        <div class="empty">
            <h2>No orders yet</h2>
            <p class="muted">Your order tracking history will appear here.</p>
            <a class="btn-secondary" href="{{ route('shop') }}">Shop Products</a>
        </div>
    @else
        <div class="orders-list">
            @foreach($orders as $order)
                @php
                    $statusClass = match($order->status) {
                        'completed' => 'badge-green',
                        'cancelled' => 'badge-red',
                        'pending' => 'badge-yellow',
                        default => 'badge-blue',
                    };
                @endphp
                <div class="order-row">
                    <div>
                        <strong>{{ $order->order_number }}</strong>
                        <div class="muted">{{ $order->created_at?->format('M d, Y h:i A') }}</div>
                    </div>
                    <span class="badge {{ $statusClass }}">{{ $order->status_label }}</span>
                    <strong>PHP {{ number_format($order->total, 2) }}</strong>
                    <a class="btn-secondary" href="{{ route('orders.track', $order) }}">Track</a>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    @endif
</main>
@endsection
