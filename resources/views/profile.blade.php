@extends('layouts.app')

@section('title', 'Profile')

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

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .profile-header {
        margin-bottom: 40px;
    }

    .profile-header h1 {
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .profile-header p {
        color: var(--text-muted);
    }

    .profile-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 32px;
        max-width: 600px;
        box-shadow: var(--shadow-sm);
    }

    .profile-section {
        margin-bottom: 28px;
    }

    .profile-section:last-child {
        margin-bottom: 0;
    }

    .profile-label {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .profile-value {
        color: var(--text-secondary);
        font-size: 16px;
    }

    .profile-divider {
        height: 1px;
        background: var(--border);
        margin: 24px 0;
    }

    .logout-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 20px;
    }

    .logout-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .logout-btn:active {
        transform: translateY(1px);
    }
</style>

<div class="nav">
    <div class="logo">Puffcart</div>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
        <a href="/cart">Cart</a>
    </div>
</div>

<div class="container">
    <div class="profile-header">
        <h1>My Account</h1>
        <p>Manage your profile and preferences</p>
    </div>

    <div class="profile-card">
        <div class="profile-section">
            <div class="profile-label">Full Name</div>
            <div class="profile-value">{{ Auth::user()->name }}</div>
        </div>

        <div class="profile-divider"></div>

        <div class="profile-section">
            <div class="profile-label">Email Address</div>
            <div class="profile-value">{{ Auth::user()->email }}</div>
        </div>

        <form method="POST" action="/logout" style="margin-top: 24px;">
            @csrf
            <button type="submit" class="logout-btn">Sign Out</button>
        </form>
    </div>
</div>
@endsection
