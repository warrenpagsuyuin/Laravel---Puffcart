@extends('layouts.app')

@section('title', 'Track ' . $order->order_number)

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

    .track-shell {
        margin: 0 auto;
        max-width: 860px;
        padding: 40px 20px 64px;
    }

    .panel {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
    }

    .timeline {
        display: grid;
        gap: 16px;
        margin-top: 24px;
    }

    .step {
        border-left: 4px solid var(--primary);
        padding-left: 16px;
    }

    .step strong {
        color: var(--text-primary);
        display: block;
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
        <a href="{{ route('orders.index') }}">Orders</a>
        <a href="{{ route('tracking') }}">Tracking</a>
        <a href="{{ route('shop') }}">Shop</a>
    </div>
</nav>

<main class="track-shell">
    <div class="panel">
        <h1>{{ $order->order_number }}</h1>
        <p class="muted">{{ $order->status_label }} / PHP {{ number_format($order->total, 2) }}</p>

        <div class="timeline">
            @forelse($order->tracking as $tracking)
                <div class="step">
                    <strong>{{ ucfirst(str_replace('_', ' ', $tracking->status)) }}</strong>
                    <div>{{ $tracking->message }}</div>
                    <div class="muted">{{ $tracking->occurred_at?->format('M d, Y h:i A') }}</div>
                </div>
            @empty
                <p class="muted">No tracking updates yet.</p>
            @endforelse
        </div>

        <a class="btn-secondary" href="{{ route('orders.show', $order) }}" style="margin-top:24px;">Order Details</a>
    </div>
</main>
@endsection
