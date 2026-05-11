@extends('layouts.app')

@section('title', 'Profile')

@section('content')
@php
    $user = auth()->user();
    $nameParts = preg_split('/\s+/', trim($user->name));
    $initials = strtoupper(collect($nameParts)->filter()->take(2)->map(fn ($part) => substr($part, 0, 1))->join(''));
    $verificationStatus = $user->verification_status ?: 'pending';
    $statusClass = match ($verificationStatus) {
        'approved' => 'status-approved',
        'rejected' => 'status-rejected',
        default => 'status-pending',
    };
@endphp

<style>
    body {
        background: #f8fafc;
    }

    .account-nav {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 0 48px;
        min-height: 72px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .brand {
        color: #0b66ff;
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

    .nav-links a:hover,
    .nav-links .active {
        background: #eff6ff;
        color: #0b66ff;
    }

    .account-shell {
        width: min(1120px, calc(100% - 48px));
        margin: 0 auto;
        padding: 40px 0 64px;
    }

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 24px;
        margin-bottom: 24px;
    }

    .eyebrow {
        color: #0f766e;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .page-heading h1 {
        font-size: 34px;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .page-heading p {
        color: #64748b;
        font-size: 15px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .btn-primary {
        background: #0b66ff;
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(11, 102, 255, 0.16);
    }

    .btn-primary:hover {
        background: #0954d6;
        color: #ffffff;
    }

    .btn-secondary {
        background: #ffffff;
        color: #334155;
        border-color: #cbd5e1;
    }

    .btn-secondary:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .account-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(320px, 0.8fr);
        gap: 24px;
        align-items: start;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    }

    .identity-panel {
        overflow: hidden;
    }

    .identity-top {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr);
        gap: 18px;
        align-items: center;
        padding: 28px;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .avatar {
        width: 72px;
        height: 72px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #0f172a;
        color: #ffffff;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .identity-copy {
        min-width: 0;
    }

    .identity-copy h2 {
        color: #0f172a;
        font-size: 24px;
        margin-bottom: 6px;
        overflow-wrap: anywhere;
    }

    .identity-copy p {
        color: #64748b;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        gap: 8px;
        margin-top: 12px;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .status-approved {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .status-pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .status-rejected {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
    }

    .detail-item {
        padding: 24px 28px;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-item:nth-child(odd) {
        border-right: 1px solid #e2e8f0;
    }

    .detail-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .detail-value {
        color: #0f172a;
        font-size: 15px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .side-stack {
        display: grid;
        gap: 18px;
    }

    .panel-heading {
        padding: 22px 24px 0;
    }

    .panel-heading h2 {
        color: #0f172a;
        font-size: 18px;
        margin-bottom: 6px;
    }

    .panel-heading p {
        color: #64748b;
        font-size: 14px;
    }

    .action-list {
        display: grid;
        padding: 18px 24px 24px;
        gap: 10px;
    }

    .action-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 48px;
        padding: 12px 14px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        font-weight: 700;
        font-size: 14px;
    }

    .action-link:hover {
        border-color: #93c5fd;
        background: #f8fbff;
        color: #0b66ff;
    }

    .logout-form {
        padding: 0 24px 24px;
    }

    .logout-btn {
        width: 100%;
        min-height: 44px;
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #991b1b;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 800;
    }

    .logout-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    @media (max-width: 860px) {
        .account-nav {
            padding: 16px 20px;
            align-items: flex-start;
            gap: 14px;
            flex-direction: column;
        }

        .nav-links {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .account-shell {
            width: min(100% - 32px, 1120px);
            padding-top: 28px;
        }

        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
            justify-content: stretch;
        }

        .header-actions .btn {
            flex: 1;
        }

        .account-grid,
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item:nth-child(odd) {
            border-right: 0;
        }
    }

    @media (max-width: 520px) {
        .identity-top {
            grid-template-columns: 1fr;
        }

        .avatar {
            width: 64px;
            height: 64px;
        }

        .page-heading h1 {
            font-size: 28px;
        }
    }
</style>

<nav class="account-nav">
    <a class="brand" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('cart') }}">Cart</a>
        <a href="{{ route('profile') }}" class="active">{{ $user->name }}</a>
    </div>
</nav>

<main class="account-shell">
    <section class="page-heading">
        <div>
            <div class="eyebrow">Customer Account</div>
            <h1>My Account</h1>
            <p>Review your account information, order activity, and verification status.</p>
        </div>
        <div class="header-actions">
            <a class="btn btn-secondary" href="{{ route('orders.index') }}">View Orders</a>
            <a class="btn btn-primary" href="{{ route('shop') }}">Continue Shopping</a>
        </div>
    </section>

    <section class="account-grid">
        <div class="panel identity-panel">
            <div class="identity-top">
                <div class="avatar">{{ $initials ?: 'PC' }}</div>
                <div class="identity-copy">
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                    <div class="status-pill {{ $statusClass }}">
                        {{ ucfirst($verificationStatus) }} account
                    </div>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Full Name</div>
                    <div class="detail-value">{{ $user->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">{{ $user->email }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Username</div>
                    <div class="detail-value">{{ $user->username ?: 'Not provided' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">{{ $user->phone ?: 'Not provided' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date of Birth</div>
                    <div class="detail-value">{{ $user->date_of_birth?->format('M d, Y') ?: 'Not provided' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Member Since</div>
                    <div class="detail-value">{{ $user->created_at?->format('M d, Y') ?: 'Not available' }}</div>
                </div>
            </div>
        </div>

        <aside class="side-stack">
            <div class="panel">
                <div class="panel-heading">
                    <h2>Account Tools</h2>
                    <p>Quick access to your purchasing workflow.</p>
                </div>
                <div class="action-list">
                    <a class="action-link" href="{{ route('orders.index') }}">
                        <span>Orders</span>
                        <span>View</span>
                    </a>
                    <a class="action-link" href="{{ route('tracking') }}">
                        <span>Tracking</span>
                        <span>Open</span>
                    </a>
                    <a class="action-link" href="{{ route('cart') }}">
                        <span>Cart</span>
                        <span>Review</span>
                    </a>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h2>Session</h2>
                    <p>Sign out when you are finished using this device.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Sign Out</button>
                </form>
            </div>
        </aside>
    </section>
</main>
@endsection
