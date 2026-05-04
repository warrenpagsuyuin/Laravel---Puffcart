@extends('layouts.app')

@section('title', 'Tracking')

@section('content')
<style>
    body {
        background: #ffffff;
    }

    .nav {
        background: var(--bg-white);
        border-bottom: 1px solid var(--border);
        padding: 16px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-family: 'Poppins', sans-serif;
        color: var(--primary);
        font-size: 18px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .nav-links {
        display: flex;
        gap: 32px;
    }

    .nav a {
        color: var(--text-secondary);
        font-size: 14px;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .nav a:hover {
        color: var(--primary);
    }

    .tracking-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .tracking-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 40px;
        box-shadow: var(--shadow-sm);
    }

    .tracking-header {
        margin-bottom: 40px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 24px;
    }

    .tracking-header h1 {
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .order-id {
        font-family: 'Poppins', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 12px;
    }

    .order-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #f0fdf4;
        color: #15803d;
        border-radius: var(--radius);
        font-size: 13px;
        font-weight: 600;
    }

    .order-status.in-progress {
        background: #fef3c7;
        color: #b45309;
    }

    .timeline {
        margin-top: 32px;
    }

    .timeline-step {
        display: flex;
        gap: 20px;
        margin-bottom: 24px;
    }

    .timeline-step:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--bg-light);
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        position: relative;
    }

    .timeline-dot.completed {
        background: #f0fdf4;
        border-color: #15803d;
        color: #15803d;
    }

    .timeline-dot.in-progress {
        background: #fef3c7;
        border-color: #b45309;
        color: #b45309;
        box-shadow: 0 0 0 4px rgba(180, 83, 9, 0.1);
    }

    .timeline-step:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        width: 2px;
        height: 24px;
        background: var(--border);
    }

    .timeline-step.completed:not(:last-child)::before {
        background: #15803d;
    }

    .timeline-content {
        flex: 1;
        padding-top: 8px;
    }

    .timeline-content h3 {
        color: var(--text-primary);
        font-size: 15px;
        margin-bottom: 4px;
    }

    .timeline-content p {
        color: var(--text-muted);
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .tracking-container {
            padding: 40px 20px;
        }

        .tracking-card {
            padding: 24px;
        }

        .timeline-dot {
            width: 36px;
            height: 36px;
        }
    }
</style>

<div class="nav">
    <div class="logo">VapeVault</div>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
        <a href="/cart">Cart</a>
    </div>
</div>

<div class="tracking-container">
    <div class="tracking-card">
        <div class="tracking-header">
            <h1>Order Tracking</h1>
            <div class="order-id">#ORD-0091</div>
            <span class="order-status in-progress">🚚 Out for Delivery</span>
        </div>

        <div class="timeline">
            <div class="timeline-step completed" style="position: relative;">
                <div class="timeline-dot completed">✓</div>
                <div class="timeline-content">
                    <h3>Order Placed</h3>
                    <p>May 1, 2026 at 2:30 PM</p>
                </div>
            </div>

            <div class="timeline-step completed" style="position: relative;">
                <div class="timeline-dot completed">✓</div>
                <div class="timeline-content">
                    <h3>Order Packed</h3>
                    <p>May 2, 2026 at 10:15 AM</p>
                </div>
            </div>

            <div class="timeline-step in-progress" style="position: relative;">
                <div class="timeline-dot in-progress">📦</div>
                <div class="timeline-content">
                    <h3>Out for Delivery</h3>
                    <p>Today at 9:00 AM</p>
                </div>
            </div>

            <div class="timeline-step" style="position: relative;">
                <div class="timeline-dot">✓</div>
                <div class="timeline-content">
                    <h3>Delivered</h3>
                    <p>Coming soon</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection