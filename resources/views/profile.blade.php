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
    :root {
        --profile-bg: #f4f7fb;
        --profile-nav-bg: rgba(255, 255, 255, 0.94);
        --profile-surface: #ffffff;
        --profile-surface-soft: #f8fbff;
        --profile-surface-muted: #eef6ff;
        --profile-border: #d9e4f2;
        --profile-text: #0e1b2f;
        --profile-heading: #081326;
        --profile-muted: #5f718a;
        --profile-subtle: #7b8aa0;
        --profile-brand: #0b63f6;
        --profile-brand-hover: #084ec1;
        --profile-brand-soft: #eaf2ff;
        --profile-accent: #00897b;
        --profile-accent-soft: #e6fffa;
        --profile-avatar-bg: #13243d;
        --profile-shadow: 0 18px 40px rgba(20, 35, 58, 0.08);
        --profile-shadow-soft: 0 8px 22px rgba(20, 35, 58, 0.06);
        --profile-danger-bg: #fff1f2;
        --profile-danger-text: #b4232a;
        --profile-danger-border: #fecdd3;
        --profile-success-bg: #edfdf7;
        --profile-success-text: #057a55;
        --profile-success-border: #9ce7c8;
        --profile-warning-bg: #fff8e6;
        --profile-warning-text: #9a5b00;
        --profile-warning-border: #f7d875;
    }

    :root[data-theme="dark"] {
        --profile-bg: #0b1220;
        --profile-nav-bg: rgba(10, 16, 29, 0.94);
        --profile-surface: #121b2b;
        --profile-surface-soft: #162235;
        --profile-surface-muted: #112b4f;
        --profile-border: #26384f;
        --profile-text: #dbe7f5;
        --profile-heading: #f3f8ff;
        --profile-muted: #a9bad0;
        --profile-subtle: #8498b2;
        --profile-brand: #7db7ff;
        --profile-brand-hover: #a9ceff;
        --profile-brand-soft: #102a4d;
        --profile-accent: #5eead4;
        --profile-accent-soft: #0f3a3b;
        --profile-avatar-bg: #1d4ed8;
        --profile-shadow: 0 22px 46px rgba(0, 0, 0, 0.34);
        --profile-shadow-soft: 0 12px 28px rgba(0, 0, 0, 0.24);
        --profile-danger-bg: #351316;
        --profile-danger-text: #fecaca;
        --profile-danger-border: #7f1d1d;
        --profile-success-bg: #0c2f26;
        --profile-success-text: #86efac;
        --profile-success-border: #177b59;
        --profile-warning-bg: #332713;
        --profile-warning-text: #fde68a;
        --profile-warning-border: #8a610c;
    }

    body {
        background:
            radial-gradient(circle at top left, rgba(11, 99, 246, 0.08), transparent 30%),
            var(--profile-bg);
        color: var(--profile-text);
    }

    .account-nav {
        background: var(--profile-nav-bg);
        border-bottom: 1px solid var(--profile-border);
        padding: 0 48px;
        min-height: 72px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 20;
        backdrop-filter: blur(16px);
    }

    .brand {
        color: var(--profile-brand);
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
        color: var(--profile-muted);
        font-size: 14px;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 8px;
    }

    .nav-links a:hover,
    .nav-links .active {
        background: var(--profile-brand-soft);
        color: var(--profile-brand);
    }

    .theme-toggle {
        width: 44px;
        height: 44px;
        display: inline-grid;
        place-items: center;
        background: var(--profile-brand-soft);
        color: var(--profile-brand);
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        flex: 0 0 auto;
    }

    .theme-toggle:hover {
        background: var(--profile-surface-muted);
        color: var(--profile-brand-hover);
    }

    .theme-toggle svg {
        width: 18px;
        height: 18px;
    }

    .theme-toggle .sun-icon {
        display: none;
    }

    :root[data-theme="dark"] .theme-toggle .moon-icon {
        display: none;
    }

    :root[data-theme="dark"] .theme-toggle .sun-icon {
        display: block;
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
        color: var(--profile-accent);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .page-heading h1 {
        font-size: 34px;
        color: var(--profile-heading);
        margin-bottom: 8px;
    }

    .page-heading p {
        color: var(--profile-muted);
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
        background: var(--profile-brand);
        color: #ffffff;
        box-shadow: 0 10px 22px rgba(11, 99, 246, 0.18);
    }

    .btn-primary:hover {
        background: var(--profile-brand-hover);
        color: #ffffff;
    }

    .btn-secondary {
        background: var(--profile-surface);
        color: var(--profile-text);
        border-color: var(--profile-border);
    }

    .btn-secondary:hover {
        background: var(--profile-surface-soft);
        color: var(--profile-heading);
    }

    .account-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(320px, 0.8fr);
        gap: 24px;
        align-items: start;
    }

    .panel {
        background: var(--profile-surface);
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        box-shadow: var(--profile-shadow-soft);
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
        border-bottom: 1px solid var(--profile-border);
        background: linear-gradient(180deg, var(--profile-surface) 0%, var(--profile-surface-soft) 100%);
    }

    .avatar {
        width: 72px;
        height: 72px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, var(--profile-avatar-bg), #1d4ed8);
        color: #ffffff;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .identity-copy {
        min-width: 0;
    }

    .identity-copy h2 {
        color: var(--profile-heading);
        font-size: 24px;
        margin-bottom: 6px;
        overflow-wrap: anywhere;
    }

    .identity-copy p {
        color: var(--profile-muted);
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
        background: var(--profile-success-bg);
        color: var(--profile-success-text);
        border: 1px solid var(--profile-success-border);
    }

    .status-pending {
        background: var(--profile-warning-bg);
        color: var(--profile-warning-text);
        border: 1px solid var(--profile-warning-border);
    }

    .status-rejected {
        background: var(--profile-danger-bg);
        color: var(--profile-danger-text);
        border: 1px solid var(--profile-danger-border);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
    }

    .detail-item {
        padding: 24px 28px;
        border-bottom: 1px solid var(--profile-border);
    }

    .detail-item:nth-child(odd) {
        border-right: 1px solid var(--profile-border);
    }

    .detail-label {
        color: var(--profile-subtle);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .detail-value {
        color: var(--profile-heading);
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
        color: var(--profile-heading);
        font-size: 18px;
        margin-bottom: 6px;
    }

    .panel-heading p {
        color: var(--profile-muted);
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
        border: 1px solid var(--profile-border);
        background: var(--profile-surface);
        color: var(--profile-text);
        font-weight: 700;
        font-size: 14px;
    }

    .action-link:hover {
        border-color: color-mix(in srgb, var(--profile-brand) 45%, var(--profile-border));
        background: var(--profile-surface-soft);
        color: var(--profile-brand);
    }

    .logout-form {
        padding: 0 24px 24px;
    }

    .logout-btn {
        width: 100%;
        min-height: 44px;
        border: 1px solid var(--profile-danger-border);
        background: var(--profile-danger-bg);
        color: var(--profile-danger-text);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 800;
    }

    .logout-btn:hover {
        background: color-mix(in srgb, var(--profile-danger-bg) 82%, var(--profile-danger-text));
        border-color: var(--profile-danger-text);
    }

    :root[data-theme="dark"] body {
        background:
            radial-gradient(circle at top left, rgba(94, 234, 212, 0.1), transparent 30%),
            radial-gradient(circle at top right, rgba(125, 183, 255, 0.1), transparent 26%),
            var(--profile-bg);
    }

    :root[data-theme="dark"] .panel {
        box-shadow: var(--profile-shadow);
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
        <button type="button" class="theme-toggle" id="themeToggle" aria-label="Switch to dark mode" aria-pressed="false" title="Toggle dark mode">
            <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20.9 15.1A8.5 8.5 0 0 1 8.9 3.1a7 7 0 1 0 12 12z"></path>
            </svg>
            <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="4"></circle>
                <path d="M12 2v2"></path>
                <path d="M12 20v2"></path>
                <path d="m4.93 4.93 1.41 1.41"></path>
                <path d="m17.66 17.66 1.41 1.41"></path>
                <path d="M2 12h2"></path>
                <path d="M20 12h2"></path>
                <path d="m6.34 17.66-1.41 1.41"></path>
                <path d="m19.07 4.93-1.41 1.41"></path>
            </svg>
        </button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('themeToggle');
        var storageKey = 'puffcart-theme';

        function getTheme() {
            return document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';
        }

        function saveTheme(theme) {
            document.documentElement.dataset.theme = theme;

            try {
                localStorage.setItem(storageKey, theme);
            } catch (error) {
                // Ignore storage failures and still update the current page.
            }

            if (toggle) {
                var isDark = theme === 'dark';
                toggle.setAttribute('aria-pressed', String(isDark));
                toggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            }
        }

        saveTheme(getTheme());

        if (toggle) {
            toggle.addEventListener('click', function () {
                saveTheme(getTheme() === 'dark' ? 'light' : 'dark');
            });
        }
    });
</script>
@endsection
