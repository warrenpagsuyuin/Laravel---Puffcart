@extends('layouts.app')

@section('title', 'Forgot Password')

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
        line-height: 1.5;
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
        box-sizing: border-box;
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

    .alert-success {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
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

    .info-text {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius);
        padding: 12px 14px;
        font-size: 13px;
        color: #1e40af;
        margin-bottom: 20px;
    }
</style>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <h1>Forgot Password?</h1>
            <p>Enter your email address and we'll send you a link to reset your password.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="info-text">
            💡 Please enter the email address associated with your Puffcart account.
        </div>

        <form method="POST" action="{{ route('password.send-reset-link') }}">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
            </div>

            <button type="submit" class="auth-submit">Send Reset Link</button>
        </form>

        <div class="auth-footer">
            <p><a href="{{ route('login') }}">← Back to Login</a></p>
            <p>Don't have an account? <a href="{{ route('register') }}">Create one</a></p>
        </div>
    </div>
</div>
@endsection
