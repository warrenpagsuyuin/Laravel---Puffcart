@extends('layouts.app')

@section('title', 'Orders')

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

    .orders-shell {
        margin: 0 auto;
        max-width: 1040px;
        padding: 40px 20px 64px;
    }

    .orders-table {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow-x: auto;
    }

    table {
        border-collapse: collapse;
        min-width: 760px;
        width: 100%;
    }

    th,
    td {
        border-bottom: 1px solid var(--border);
        padding: 14px;
        text-align: left;
    }

    th {
        color: var(--text-muted);
        font-size: 12px;
        text-transform: uppercase;
    }

    tr:last-child td {
        border-bottom: 0;
    }

    .badge {
        background: var(--primary-light);
        border-radius: 999px;
        color: var(--primary);
        display: inline-flex;
        font-size: 12px;
        font-weight: 800;
        padding: 5px 10px;
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
        min-height: 38px;
        padding: 8px 12px;
    }

    .muted {
        color: var(--text-muted);
    }

    .pagination {
        margin-top: 18px;
    }

    @media (max-width: 760px) {
        .store-nav {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px;
        }
    }
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('cart') }}">Cart</a>
        <a href="{{ route('tracking') }}">Tracking</a>
        <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
    </div>
</nav>

<main class="orders-shell">
    <h1>My Orders</h1>

    <div class="orders-table">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td><span class="badge">{{ $order->status_label }}</span></td>
                        <td>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</td>
                        <td>PHP {{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->created_at?->format('M d, Y') }}</td>
                        <td><a class="btn-secondary" href="{{ route('orders.show', $order) }}">View</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="muted">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $orders->links() }}
    </div>
</main>
@endsection
