@extends('layouts.app')

@section('title', 'Create Account')

@section('content')

<div class="container">
    <div class="card" style="max-width: 580px; margin: auto; padding: 38px;">

        <div style="text-align:center;margin-bottom:28px;">
            <span style="background:#e6f0ff;color:#0066ff;padding:7px 12px;border-radius:999px;font-size:13px;font-weight:700;">
                18+ Verification Required
            </span>

            <h1 style="margin:18px 0 8px;font-size:32px;">Create Account</h1>

            <p class="muted" style="margin:0;">
                Register for Puffcart / CloudPuffs. Valid ID upload and privacy consent are required.
            </p>
        </div>

        @if ($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:14px 16px;border-radius:12px;margin-bottom:22px;">
                @foreach ($errors->all() as $error)
                    <div style="margin-bottom:4px;">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/register" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Choose a username" required>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Contact Number</label>
                <input
                    type="text"
                    name="contact_number"
                    value="{{ old('contact_number') }}"
                    placeholder="09123456789"
                    maxlength="11"
                    minlength="11"
                    pattern="[0-9]{11}"
                    inputmode="numeric"
                    required
                >
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Address</label>
                <textarea name="address" placeholder="House no., street, barangay, city/province" required>{{ old('address') }}</textarea>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                <p class="muted" style="font-size:14px;margin:6px 0 0;">
                    You must be at least 18 years old to create an account.
                </p>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Upload Valid ID</label>
                <input type="file" name="valid_id" accept=".jpg,.jpeg,.png,.pdf" required>
                <p class="muted" style="font-size:14px;margin:6px 0 0;">
                    Accepted files: JPG, PNG, or PDF. Maximum size: 5MB.
                </p>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Password</label>
                <input type="password" name="password" placeholder="Create strong password" required>
                <p class="muted" style="font-size:14px;margin:6px 0 0;">
                    Minimum 10 characters with uppercase, lowercase, number, and symbol.
                </p>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Confirm password" required>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-weight:700;">Captcha: What is {{ session('captcha_question') }}?</label>
                <input type="number" name="captcha" placeholder="Enter captcha answer" required>
            </div>

            <div style="background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;padding:16px;border-radius:12px;margin-bottom:18px;">
                <strong>Age Restricted Products:</strong>
                This website is only for adults 18 years old and above. Your uploaded ID will be reviewed for verification.
            </div>

            <label style="display:flex;gap:10px;align-items:flex-start;margin-bottom:14px;">
                <input type="checkbox" name="age_confirmed" value="1" required style="width:auto;margin-top:4px;">
                <span>I confirm that I am 18 years old or above.</span>
            </label>

            <label style="display:flex;gap:10px;align-items:flex-start;margin-bottom:24px;">
                <input type="checkbox" name="privacy_consent" value="1" required style="width:auto;margin-top:4px;">
                <span>
                    I consent to Puffcart / CloudPuffs collecting and reviewing my uploaded ID
                    for age verification, fraud prevention, and account security.
                </span>
            </label>

            <button type="submit" class="btn-primary" style="width:100%;">
                Create Secure Account
            </button>
        </form>

        <p style="text-align:center;margin-top:24px;" class="muted">
            Already have an account?
            <a href="/login" style="color:#0066ff;font-weight:700;">Sign In</a>
        </p>

    </div>
</div>

@endsection
