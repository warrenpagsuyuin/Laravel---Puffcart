@extends('layouts.app')

@section('title', 'Register')

@section('content')
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
        padding: 20px;
    }

    .auth-box {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 40px;
        width: 100%;
        max-width: 420px;
        box-shadow: var(--shadow-md);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .auth-header h1 {
        font-size: 24px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .auth-header p {
        color: var(--text-muted);
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 6px;
        font-size: 13px;
    }

    .form-group input {
        width: 100%;
        padding: 11px 12px;
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-group input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .form-group input::placeholder {
        color: var(--text-muted);
    }

    .auth-submit {
        width: 100%;
        padding: 11px 12px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 8px;
    }

    .auth-submit:hover {
        background: var(--primary-hover);
        box-shadow: var(--shadow-md);
    }

    .auth-submit:active {
        transform: translateY(1px);
    }

    .alert {
        padding: 12px 14px;
        border-radius: var(--radius);
        font-size: 14px;
        margin-bottom: 16px;
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .auth-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }

    .auth-footer p {
        color: var(--text-secondary);
        margin: 12px 0;
    }

    .auth-footer a {
        color: var(--primary);
        font-weight: 500;
    }

    .auth-footer a:hover {
        color: var(--primary-hover);
    }
</style>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <h1>Create Account</h1>
            <p>Join VapeVault and start shopping today</p>
        </div>

        @foreach ($errors->all() as $error)
            <div class="alert alert-error">{{ $error }}</div>
        @endforeach

        <form method="POST" action="/register">
            @csrf
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="your@email.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a strong password" required>
            </div>

            <button type="submit" class="auth-submit">Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="/login">Sign In</a></p>
        </div>
    </div>
</div>
@endsection