@extends('layouts.app')

@section('title', 'Login')

@section('content')
@php
    $registerFields = [
        'name',
        'username',
        'contact_number',
        'address',
        'email',
        'date_of_birth',
        'valid_id',
        'password',
        'password_confirmation',
        'captcha',
        'age_confirmed',
        'privacy_consent',
    ];

    $hasRegisterErrors = old('_form') === 'register';

    foreach ($registerFields as $field) {
        if ($errors->has($field)) {
            $hasRegisterErrors = true;
            break;
        }
    }
@endphp

<style>
    .auth-container {
        min-height: 100vh;
        background:
            linear-gradient(135deg, rgba(0, 102, 255, 0.08) 0%, rgba(16, 185, 129, 0.07) 48%, rgba(255, 255, 255, 0.96) 100%);
        padding: 28px;
    }

    .auth-shell {
        width: min(720px, 100%);
        margin: 0 auto;
        min-height: calc(100vh - 56px);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .auth-box,
    .register-box {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
    }

    .auth-box {
        padding: 36px;
        width: 100%;
        max-width: 480px;
    }

    .register-box {
        padding: 30px;
        width: 100%;
        max-width: 720px;
        display: none;
    }

    .auth-box.is-hidden {
        display: none;
    }

    .register-box.is-visible {
        display: block;
    }

    .auth-header {
        margin-bottom: 26px;
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

    .verification-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
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

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 11px 12px;
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-group textarea {
        min-height: 90px;
        resize: vertical;
    }

    .form-group input[type="file"] {
        padding: 9px 12px;
        color: var(--text-secondary);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
        outline: none;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: var(--text-muted);
    }

    .password-field {
        position: relative;
    }

    .password-field input {
        padding-right: 46px;
    }

    .password-toggle {
        align-items: center;
        background: transparent;
        border: 0;
        border-radius: 8px;
        color: var(--text-muted);
        cursor: pointer;
        display: inline-flex;
        height: 34px;
        justify-content: center;
        padding: 0;
        position: absolute;
        right: 7px;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
    }

    .password-toggle:hover,
    .password-toggle:focus {
        background: var(--primary-light);
        color: var(--primary);
        outline: none;
    }

    .password-toggle svg {
        height: 18px;
        width: 18px;
    }

    .password-toggle .icon-eye-off {
        display: none;
    }

    .password-toggle.is-visible .icon-eye {
        display: none;
    }

    .password-toggle.is-visible .icon-eye-off {
        display: block;
    }

    .field-help {
        margin-top: 6px;
        color: var(--text-muted);
        font-size: 12px;
        line-height: 1.45;
    }

    .field-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
        line-height: 1.45;
    }

    .is-invalid {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
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

    .register-submit {
        background: #059669;
    }

    .register-submit:hover {
        background: #047857;
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

    .verification-note {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        border-radius: var(--radius);
        padding: 12px 14px;
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .consent-check {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: var(--text-secondary);
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .consent-check input {
        width: auto;
        margin-top: 4px;
        flex: 0 0 auto;
    }

    .login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .login-options .consent-check {
        margin-bottom: 0;
    }

    .forgot-link {
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .forgot-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
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
        text-decoration: none;
    }

    .auth-footer a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        .auth-container {
            padding: 14px;
        }

        .auth-shell {
            min-height: auto;
            align-items: flex-start;
        }

        .auth-box,
        .register-box {
            padding: 22px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
</style>

<div class="auth-container">
    <div
        class="auth-shell"
        id="authShell"
        data-register-errors="{{ $hasRegisterErrors ? 'true' : 'false' }}"
    >
        <section class="auth-box {{ $hasRegisterErrors ? 'is-hidden' : '' }}" id="loginBox">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Sign in to your Puffcart account</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error') && !$hasRegisterErrors)
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->has('login'))
                <div class="alert alert-error">{{ $errors->first('login') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_form" value="login">

                <div class="form-group">
                    <label for="login">Username or Email Address</label>
                    <input
                        id="login"
                        type="text"
                        name="login"
                        value="{{ old('_form') === 'login' ? old('login') : '' }}"
                        placeholder="Enter username or email"
                        class="{{ $errors->has('login') ? 'is-invalid' : '' }}"
                        autocomplete="off"
                        required
                    >
                    @error('login')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="login_password">Password</label>
                    <div class="password-field">
                        <input
                            id="login_password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            class="{{ old('_form') === 'login' && $errors->has('password') ? 'is-invalid' : '' }}"
                            autocomplete="current-password"
                            required
                        >
                        <button class="password-toggle" type="button" data-password-toggle="login_password" aria-label="Show password" aria-pressed="false">
                            <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 10.6A3 3 0 0 0 13.4 13.4"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.8 18.8 0 0 1-3.2 4.4"/><path d="M6.6 6.6A18.3 18.3 0 0 0 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.1-.8"/></svg>
                        </button>
                    </div>
                    @if(old('_form') === 'login')
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="login-options">
                    <label class="consent-check" for="remember">
                        <input id="remember" type="checkbox" name="remember" value="1">
                        <span>Keep me signed in on this device</span>
                    </label>

                    <a class="forgot-link" href="{{ route('password.forgot') }}">Forgot password?</a>
                </div>

                <button type="submit" class="auth-submit">Sign In</button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="#" id="showRegister">Create one</a></p>
                <p><a href="{{ route('home') }}">Back to Home</a></p>
            </div>
        </section>

        <section class="register-box {{ $hasRegisterErrors ? 'is-visible' : '' }}" id="register">
            <div class="auth-header">
                <span class="verification-badge">18+ ID Verification Required</span>
                <h1>Create Account</h1>
                <p>Register with a valid ID so an admin can review and approve your account.</p>
            </div>

            @if($hasRegisterErrors && $errors->any())
                <div class="alert alert-error">
                    Please check the fields below and fix the errors before submitting again.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="_form" value="register">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="register_name">Full Name</label>
                        <input
                            id="register_name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Juan Dela Cruz"
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                            autocomplete="name"
                            required
                        >
                        @error('name')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="register_username">Username</label>
                        <input
                            id="register_username"
                            type="text"
                            name="username"
                            value="{{ old('_form') === 'register' ? old('username') : '' }}"
                            placeholder="Choose a username"
                            class="{{ $errors->has('username') ? 'is-invalid' : '' }}"
                            autocomplete="off"
                            required
                        >
                        <p class="field-help">Use your own username. Do not use an email address as username.</p>
                        @error('username')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="register_email">Email Address</label>
                        <input
                            id="register_email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input
                            id="contact_number"
                            type="text"
                            name="contact_number"
                            value="{{ old('contact_number') }}"
                            placeholder="09XXXXXXXXX"
                            maxlength="11"
                            minlength="11"
                            pattern="[0-9]{11}"
                            inputmode="numeric"
                            class="{{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                            autocomplete="tel"
                            required
                        >
                        <p class="field-help">Enter exactly 11 digits. Example: 09123456789</p>
                        @error('contact_number')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input
                            id="date_of_birth"
                            type="date"
                            name="date_of_birth"
                            value="{{ old('date_of_birth') }}"
                            class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}"
                            autocomplete="bday"
                            required
                        >
                        @error('date_of_birth')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="valid_id">Upload Valid ID</label>
                        <input
                            id="valid_id"
                            type="file"
                            name="valid_id"
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="{{ $errors->has('valid_id') ? 'is-invalid' : '' }}"
                            required
                        >
                        <p class="field-help">Accepted files: JPG, PNG, or PDF. Maximum size: 5MB.</p>
                        @error('valid_id')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea
                        id="address"
                        name="address"
                        placeholder="House no., street, barangay, city/province"
                        class="{{ $errors->has('address') ? 'is-invalid' : '' }}"
                        autocomplete="street-address"
                        required
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="register_password">Password</label>
                        <div class="password-field">
                            <input
                                id="register_password"
                                type="password"
                                name="password"
                                placeholder="Create strong password"
                                class="{{ old('_form') === 'register' && $errors->has('password') ? 'is-invalid' : '' }}"
                                autocomplete="new-password"
                                required
                            >
                            <button class="password-toggle" type="button" data-password-toggle="register_password" aria-label="Show password" aria-pressed="false">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 10.6A3 3 0 0 0 13.4 13.4"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.8 18.8 0 0 1-3.2 4.4"/><path d="M6.6 6.6A18.3 18.3 0 0 0 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.1-.8"/></svg>
                            </button>
                        </div>
                        <p class="field-help">
                            Use at least 10 characters with uppercase, lowercase, number, and symbol.
                        </p>
                        @if(old('_form') === 'register')
                            @error('password')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="password-field">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm password"
                                class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                autocomplete="new-password"
                                required
                            >
                            <button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show password" aria-pressed="false">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 10.6A3 3 0 0 0 13.4 13.4"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.8 18.8 0 0 1-3.2 4.4"/><path d="M6.6 6.6A18.3 18.3 0 0 0 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.1-.8"/></svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="captcha">Captcha: Type {{ session('captcha_question') }}</label>
                    <input
                        id="captcha"
                        type="text"
                        name="captcha"
                        placeholder="Enter the 6-character code"
                        class="{{ $errors->has('captcha') ? 'is-invalid' : '' }}"
                        maxlength="6"
                        autocomplete="off"
                        required
                    >
                    @error('captcha')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="verification-note">
                    Account access is reviewed after registration. Your uploaded ID is used only for age verification and account security.
                </div>

                <label class="consent-check" for="age_confirmed">
                    <input
                        id="age_confirmed"
                        type="checkbox"
                        name="age_confirmed"
                        value="1"
                        {{ old('age_confirmed') ? 'checked' : '' }}
                        required
                    >
                    <span>I confirm that I am 18 years old or above.</span>
                </label>
                @error('age_confirmed')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <label class="consent-check" for="privacy_consent">
                    <input
                        id="privacy_consent"
                        type="checkbox"
                        name="privacy_consent"
                        value="1"
                        {{ old('privacy_consent') ? 'checked' : '' }}
                        required
                    >
                    <span>
                        I consent to Puffcart / CloudPuffs collecting and reviewing my uploaded ID for age verification,
                        fraud prevention, and account security.
                        <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                    </span>
                </label>
                @error('privacy_consent')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <button type="submit" class="auth-submit register-submit">Create Account for Review</button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="#" id="hideRegister">Sign in instead</a></p>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showRegister = document.getElementById('showRegister');
        const hideRegister = document.getElementById('hideRegister');
        const loginBox = document.getElementById('loginBox');
        const registerBox = document.getElementById('register');
        const authShell = document.getElementById('authShell');
        const contactNumber = document.getElementById('contact_number');
        const registerUsername = document.getElementById('register_username');

        const hasRegisterErrors = authShell && authShell.dataset.registerErrors === 'true';

        function openRegister() {
            if (!loginBox || !registerBox || !authShell) {
                return;
            }

            loginBox.classList.add('is-hidden');
            registerBox.classList.add('is-visible');

            authShell.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function closeRegister() {
            if (!loginBox || !registerBox || !authShell) {
                return;
            }

            registerBox.classList.remove('is-visible');
            loginBox.classList.remove('is-hidden');

            authShell.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        if (showRegister) {
            showRegister.addEventListener('click', function (event) {
                event.preventDefault();
                openRegister();

                if (registerUsername && !registerUsername.value) {
                    registerUsername.value = '';
                }
            });
        }

        if (hideRegister) {
            hideRegister.addEventListener('click', function (event) {
                event.preventDefault();
                closeRegister();
            });
        }

        if (contactNumber) {
            contactNumber.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
            });
        }

        if (registerUsername && !hasRegisterErrors) {
            registerUsername.value = '';
        }

        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.passwordToggle);
                if (!input) {
                    return;
                }

                const shouldShow = input.type === 'password';
                input.type = shouldShow ? 'text' : 'password';
                this.classList.toggle('is-visible', shouldShow);
                this.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
                this.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
            });
        });

        if (hasRegisterErrors) {
            openRegister();
        }
    });
</script>
@endsection
