@extends('layouts.app')

@section('title', 'Create Account')

@section('content')

<style>
    body {
        background: #F7FAFF;
    }

    .register-shell {
        min-height: 100vh;
        padding: 42px 20px;
        background:
            radial-gradient(circle at 12% 8%, rgba(11, 99, 246, 0.12), transparent 28%),
            radial-gradient(circle at 88% 18%, rgba(103, 172, 255, 0.13), transparent 24%),
            linear-gradient(180deg, #FFFFFF 0%, #F5F9FF 46%, #FFFFFF 100%);
        position: relative;
        overflow: hidden;
    }

    .register-vapor {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .register-vapor::before,
    .register-vapor::after,
    .register-vapor span {
        content: "";
        position: absolute;
        width: 56vw;
        height: 22vw;
        min-width: 360px;
        min-height: 150px;
        border-radius: 999px;
        filter: blur(30px);
        opacity: 0.54;
        animation: registerVapor 20s ease-in-out infinite alternate;
    }

    .register-vapor::before {
        left: -18%;
        top: 9%;
        background:
            radial-gradient(circle at 18% 50%, rgba(255, 255, 255, 0.94), transparent 34%),
            radial-gradient(circle at 58% 48%, rgba(198, 226, 255, 0.74), transparent 38%),
            radial-gradient(circle at 88% 52%, rgba(235, 246, 255, 0.68), transparent 34%);
    }

    .register-vapor::after {
        right: -18%;
        bottom: 8%;
        background:
            radial-gradient(circle at 18% 50%, rgba(225, 242, 255, 0.72), transparent 34%),
            radial-gradient(circle at 58% 48%, rgba(255, 255, 255, 0.78), transparent 38%),
            radial-gradient(circle at 88% 52%, rgba(174, 213, 255, 0.5), transparent 34%);
        animation-delay: -8s;
        animation-duration: 24s;
    }

    .register-vapor span {
        left: 34%;
        top: 24%;
        width: 34vw;
        height: 14vw;
        min-width: 280px;
        min-height: 110px;
        background:
            radial-gradient(circle at 24% 50%, rgba(255, 255, 255, 0.68), transparent 34%),
            radial-gradient(circle at 62% 48%, rgba(179, 218, 255, 0.52), transparent 38%),
            radial-gradient(circle at 90% 50%, rgba(232, 243, 255, 0.58), transparent 34%);
        animation-delay: -12s;
    }

    @keyframes registerVapor {
        0% {
            transform: translate3d(-18px, 8px, 0) scale(1);
        }

        100% {
            transform: translate3d(42px, -18px, 0) scale(1.08);
        }
    }

    .register-wrap {
        width: min(1120px, 100%);
        margin: 0 auto;
        display: grid;
        grid-template-columns: 0.72fr 1.28fr;
        gap: 24px;
        position: relative;
        z-index: 1;
    }

    .trust-panel,
    .register-card {
        border: 1px solid rgba(214, 226, 242, 0.86);
        background: rgba(255, 255, 255, 0.82);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.1);
        backdrop-filter: blur(18px);
    }

    .trust-panel {
        border-radius: 24px;
        padding: 30px;
        position: sticky;
        top: 24px;
        align-self: start;
        overflow: hidden;
    }

    .trust-panel::after {
        content: "";
        position: absolute;
        right: -72px;
        bottom: -72px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(11, 99, 246, 0.16), transparent 66%);
    }

    .brand-link {
        color: #0B63F6;
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 800;
        display: inline-flex;
        margin-bottom: 34px;
    }

    .age-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #EAF2FF;
        color: #084EC1;
        border: 1px solid #C9DEFF;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .age-badge strong {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #0B63F6;
        color: #FFFFFF;
        font-size: 14px;
    }

    .trust-panel h1 {
        font-size: 38px;
        line-height: 1.08;
        margin-bottom: 14px;
    }

    .trust-panel p {
        color: #475569;
        margin-bottom: 26px;
    }

    .trust-list {
        display: grid;
        gap: 12px;
        margin-top: 26px;
        position: relative;
        z-index: 1;
    }

    .trust-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(219, 228, 239, 0.8);
    }

    .trust-icon,
    .section-icon,
    .input-icon {
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        color: #0B63F6;
    }

    .trust-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #EAF2FF;
    }

    .trust-item h3 {
        font-size: 14px;
        margin-bottom: 2px;
    }

    .trust-item p {
        font-size: 13px;
        margin: 0;
    }

    .register-card {
        border-radius: 28px;
        padding: 30px;
    }

    .form-head {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: flex-start;
        margin-bottom: 22px;
    }

    .form-head h2 {
        font-size: 28px;
        margin-bottom: 6px;
    }

    .form-head p {
        margin: 0;
    }

    .secure-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 999px;
        background: #F0FDF4;
        color: #166534;
        border: 1px solid #BBF7D0;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .error-box {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
        padding: 14px 16px;
        border-radius: 16px;
        margin-bottom: 22px;
        font-size: 14px;
    }

    .form-section {
        padding: 22px 0;
        border-top: 1px solid #E5EEF8;
    }

    .section-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .section-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #EAF2FF;
    }

    .section-title-row h3 {
        font-size: 16px;
    }

    .field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .field {
        display: grid;
        gap: 7px;
    }

    .field.full {
        grid-column: 1 / -1;
    }

    .field label {
        color: #0F172A;
        font-size: 13px;
        font-weight: 800;
    }

    .input-wrap {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        opacity: 0.78;
    }

    .input-wrap input,
    .input-wrap textarea {
        width: 100%;
        min-height: 48px;
        padding-left: 42px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.92);
        border-color: #D8E4F2;
        color: #0F172A;
    }

    .input-wrap textarea {
        min-height: 104px;
        resize: vertical;
        padding-top: 13px;
    }

    .input-wrap textarea + .input-icon {
        top: 24px;
    }

    .input-wrap input:valid:not(:placeholder-shown),
    .input-wrap textarea:valid:not(:placeholder-shown) {
        border-color: #86EFAC;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .input-wrap input:invalid:not(:placeholder-shown),
    .input-wrap textarea:invalid:not(:placeholder-shown) {
        border-color: #FDA4AF;
        box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.1);
    }

    .field-note {
        color: #64748B;
        font-size: 12px;
        line-height: 1.45;
    }

    .upload-card {
        position: relative;
        display: grid;
        place-items: center;
        gap: 8px;
        min-height: 150px;
        padding: 22px;
        border: 1.5px dashed #A8C9F8;
        border-radius: 18px;
        background:
            radial-gradient(circle at 50% 0%, rgba(11, 99, 246, 0.1), transparent 42%),
            rgba(255, 255, 255, 0.76);
        color: #334155;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .upload-card:hover,
    .upload-card.is-dragging {
        border-color: #0B63F6;
        background: #EAF2FF;
        transform: translateY(-1px);
    }

    .upload-card input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: #0B63F6;
        color: #FFFFFF;
        box-shadow: 0 14px 24px rgba(11, 99, 246, 0.22);
    }

    .upload-card strong {
        color: #0F172A;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        height: 32px;
        padding: 0 10px;
        border: 1px solid #D8E4F2;
        background: #FFFFFF;
        color: #0B63F6;
        font-size: 12px;
        font-weight: 800;
        border-radius: 10px;
    }

    .input-wrap.has-toggle input {
        padding-right: 68px;
    }

    .strength {
        display: grid;
        gap: 7px;
        margin-top: 2px;
    }

    .strength-track {
        height: 7px;
        border-radius: 999px;
        background: #E5EEF8;
        overflow: hidden;
    }

    .strength-bar {
        display: block;
        width: 0%;
        height: 100%;
        border-radius: inherit;
        background: #EF4444;
        transition: width 0.2s ease, background 0.2s ease;
    }

    .strength-text {
        color: #64748B;
        font-size: 12px;
        font-weight: 700;
    }

    .verification-callout {
        display: flex;
        gap: 14px;
        padding: 16px;
        border-radius: 18px;
        background: #FFF7ED;
        border: 1px solid #FED7AA;
        color: #9A3412;
        margin-top: 4px;
    }

    .verification-callout strong {
        display: block;
        color: #7C2D12;
        margin-bottom: 2px;
    }

    .check-row {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 14px;
        color: #334155;
        font-size: 14px;
    }

    .check-row input {
        width: 18px;
        height: 18px;
        margin-top: 2px;
        accent-color: #0B63F6;
    }

    .submit-btn {
        width: 100%;
        min-height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, #0B63F6, #084EC1);
        color: #FFFFFF;
        font-size: 15px;
        font-weight: 800;
        box-shadow: 0 18px 34px rgba(11, 99, 246, 0.24);
    }

    .submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 22px 40px rgba(11, 99, 246, 0.3);
    }

    .signin-note {
        text-align: center;
        margin-top: 22px;
        color: #64748B;
        font-size: 14px;
    }

    .signin-note a {
        color: #0B63F6;
        font-weight: 800;
    }

    :root[data-theme="dark"] body {
        background: #07111F;
    }

    :root[data-theme="dark"] .register-shell {
        background:
            radial-gradient(circle at 12% 8%, rgba(91, 157, 255, 0.16), transparent 28%),
            radial-gradient(circle at 88% 18%, rgba(139, 92, 246, 0.16), transparent 24%),
            linear-gradient(180deg, #07111F 0%, #0F172A 48%, #08111F 100%);
    }

    :root[data-theme="dark"] .register-vapor::before {
        background:
            radial-gradient(circle at 18% 50%, rgba(91, 157, 255, 0.32), transparent 34%),
            radial-gradient(circle at 58% 48%, rgba(168, 85, 247, 0.2), transparent 38%),
            radial-gradient(circle at 88% 52%, rgba(215, 233, 255, 0.16), transparent 34%);
        opacity: 0.48;
    }

    :root[data-theme="dark"] .register-vapor::after,
    :root[data-theme="dark"] .register-vapor span {
        background:
            radial-gradient(circle at 18% 50%, rgba(71, 121, 255, 0.22), transparent 34%),
            radial-gradient(circle at 58% 48%, rgba(148, 163, 255, 0.2), transparent 38%),
            radial-gradient(circle at 88% 52%, rgba(216, 180, 254, 0.18), transparent 34%);
        opacity: 0.48;
    }

    :root[data-theme="dark"] .trust-panel,
    :root[data-theme="dark"] .register-card {
        background: rgba(12, 22, 38, 0.78);
        border-color: rgba(91, 157, 255, 0.22);
        box-shadow: 0 28px 60px rgba(0, 0, 0, 0.38);
    }

    :root[data-theme="dark"] .brand-link,
    :root[data-theme="dark"] .trust-icon,
    :root[data-theme="dark"] .section-icon,
    :root[data-theme="dark"] .input-icon,
    :root[data-theme="dark"] .signin-note a {
        color: #7DB7FF;
    }

    :root[data-theme="dark"] .age-badge {
        background: #112B4F;
        border-color: #2F4562;
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .age-badge strong,
    :root[data-theme="dark"] .upload-icon {
        background: #2F7CF6;
    }

    :root[data-theme="dark"] .trust-panel p,
    :root[data-theme="dark"] .form-head p,
    :root[data-theme="dark"] .field-note,
    :root[data-theme="dark"] .signin-note,
    :root[data-theme="dark"] .strength-text,
    :root[data-theme="dark"] .check-row {
        color: #B7C6DA;
    }

    :root[data-theme="dark"] .trust-item,
    :root[data-theme="dark"] .section-icon,
    :root[data-theme="dark"] .trust-icon {
        background: rgba(17, 43, 79, 0.62);
        border-color: #26384F;
    }

    :root[data-theme="dark"] .secure-pill {
        background: rgba(20, 83, 45, 0.34);
        border-color: rgba(134, 239, 172, 0.34);
        color: #BBF7D0;
    }

    :root[data-theme="dark"] .form-section {
        border-top-color: #26384F;
    }

    :root[data-theme="dark"] .field label,
    :root[data-theme="dark"] .upload-card strong {
        color: #EEF5FF;
    }

    :root[data-theme="dark"] .input-wrap input,
    :root[data-theme="dark"] .input-wrap textarea,
    :root[data-theme="dark"] .password-toggle {
        background: rgba(8, 17, 31, 0.74);
        border-color: #2F4562;
        color: #E5EEF9;
    }

    :root[data-theme="dark"] .input-wrap input::placeholder,
    :root[data-theme="dark"] .input-wrap textarea::placeholder {
        color: #8EA2BC;
    }

    :root[data-theme="dark"] .upload-card {
        background:
            radial-gradient(circle at 50% 0%, rgba(91, 157, 255, 0.14), transparent 42%),
            rgba(8, 17, 31, 0.64);
        border-color: #3D5D87;
        color: #B7C6DA;
    }

    :root[data-theme="dark"] .upload-card:hover,
    :root[data-theme="dark"] .upload-card.is-dragging {
        background: #112B4F;
        border-color: #7DB7FF;
    }

    :root[data-theme="dark"] .verification-callout {
        background: rgba(124, 45, 18, 0.24);
        border-color: rgba(253, 186, 116, 0.34);
        color: #FED7AA;
    }

    :root[data-theme="dark"] .verification-callout strong {
        color: #FFEDD5;
    }

    :root[data-theme="dark"] .strength-track {
        background: #26384F;
    }

    @media (max-width: 980px) {
        .register-wrap {
            grid-template-columns: 1fr;
        }

        .trust-panel {
            position: relative;
            top: auto;
        }
    }

    @media (max-width: 640px) {
        .register-shell {
            padding: 22px 12px;
        }

        .trust-panel,
        .register-card {
            border-radius: 20px;
            padding: 22px;
        }

        .trust-panel h1 {
            font-size: 30px;
        }

        .form-head {
            flex-direction: column;
        }

        .field-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="register-shell">
    <div class="register-vapor" aria-hidden="true"><span></span></div>

    <div class="register-wrap">
        <aside class="trust-panel">
            <a href="{{ route('home') }}" class="brand-link">Puffcart</a>

            <div class="age-badge">
                <strong>18+</strong>
                Verification Required
            </div>

            <h1>Create a secure Puffcart account</h1>
            <p>Submit your details once, verify your age, and shop authentic vape products with a protected checkout experience.</p>

            <div class="trust-list">
                <div class="trust-item">
                    <span class="trust-icon">@include('partials.icons.shield')</span>
                    <div>
                        <h3>Manual ID Review</h3>
                        <p>Your valid ID is reviewed before account approval.</p>
                    </div>
                </div>

                <div class="trust-item">
                    <span class="trust-icon">@include('partials.icons.lock')</span>
                    <div>
                        <h3>Private by Design</h3>
                        <p>Verification data is used for age checks, fraud prevention, and account security.</p>
                    </div>
                </div>

                <div class="trust-item">
                    <span class="trust-icon">@include('partials.icons.sparkle')</span>
                    <div>
                        <h3>Premium Blue Experience</h3>
                        <p>A clean Puffcart flow built for confidence from sign-up to delivery.</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="register-card">
            <div class="form-head">
                <div>
                    <h2>Create Account</h2>
                    <p>Complete all sections so our team can verify your account.</p>
                </div>
                <span class="secure-pill">@include('partials.icons.lock') Secure review</span>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/register" enctype="multipart/form-data">
                @csrf

                <section class="form-section">
                    <div class="section-title-row">
                        <span class="section-icon">@include('partials.icons.user')</span>
                        <h3>Personal Information</h3>
                    </div>

                    <div class="field-grid">
                        <div class="field">
                            <label for="name">Full Name</label>
                            <div class="input-wrap">
                                <span class="input-icon">@include('partials.icons.user')</span>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" autocomplete="name" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="username">Username</label>
                            <div class="input-wrap">
                                <span class="input-icon">@include('partials.icons.at')</span>
                                <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Choose a username" autocomplete="username" required>
                            </div>
                        </div>

                        <div class="field full">
                            <label for="address">Address</label>
                            <div class="input-wrap">
                                <span class="input-icon">@include('partials.icons.map')</span>
                                <textarea id="address" name="address" placeholder="House no., street, barangay, city/province" required>{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-title-row">
                        <span class="section-icon">@include('partials.icons.mail')</span>
                        <h3>Contact Details</h3>
                    </div>

                    <div class="field-grid">
                        <div class="field">
                            <label for="email">Email Address</label>
                            <div class="input-wrap">
                                <span class="input-icon">@include('partials.icons.mail')</span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="contact_number">Contact Number</label>
                            <div class="input-wrap">
                                <span class="input-icon">@include('partials.icons.phone')</span>
                                <input
                                    id="contact_number"
                                    type="tel"
                                    name="contact_number"
                                    value="{{ old('contact_number') }}"
                                    placeholder="09123456789"
                                    maxlength="11"
                                    minlength="11"
                                    pattern="[0-9]{11}"
                                    inputmode="numeric"
                                    autocomplete="tel"
                                    required
                                >
                            </div>
                            <span class="field-note">Use an 11-digit PH mobile number, digits only.</span>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-title-row">
                        <span class="section-icon">@include('partials.icons.badge')</span>
                        <h3>Age Verification</h3>
                    </div>

                    <div class="field-grid">
                        <div class="field">
                            <label for="date_of_birth">Date of Birth</label>
                            <div class="input-wrap">
                                <span class="input-icon">@include('partials.icons.calendar')</span>
                                <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                            </div>
                            <span class="field-note">You must be at least 18 years old to create an account.</span>
                        </div>

                        <div class="field">
                            <label for="valid_id">Upload Valid ID</label>
                            <label class="upload-card" id="uploadCard" for="valid_id">
                                <span class="upload-icon">@include('partials.icons.upload')</span>
                                <strong id="uploadLabel">Drag and drop your valid ID</strong>
                                <span class="field-note">or browse JPG, PNG, or PDF up to 5MB.</span>
                                <input id="valid_id" type="file" name="valid_id" accept=".jpg,.jpeg,.png,.pdf" required>
                            </label>
                        </div>

                        <div class="field full">
                            <div class="verification-callout">
                                <span class="trust-icon">@include('partials.icons.badge')</span>
                                <div>
                                    <strong>18+ age-restricted products</strong>
                                    This website is only for adults 18 years old and above. Your uploaded ID will be reviewed for verification.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-title-row">
                        <span class="section-icon">@include('partials.icons.lock')</span>
                        <h3>Account Security</h3>
                    </div>

                    <div class="field-grid">
                        <div class="field">
                            <label for="password">Password</label>
                            <div class="input-wrap has-toggle">
                                <span class="input-icon">@include('partials.icons.lock')</span>
                                <input id="password" type="password" name="password" placeholder="Create strong password" autocomplete="new-password" required>
                                <button class="password-toggle" type="button" data-toggle-password="password">Show</button>
                            </div>
                            <div class="strength" aria-live="polite">
                                <span class="strength-track"><span class="strength-bar" id="strengthBar"></span></span>
                                <span class="strength-text" id="strengthText">Minimum 10 characters with uppercase, lowercase, number, and symbol.</span>
                            </div>
                        </div>

                        <div class="field">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-wrap has-toggle">
                                <span class="input-icon">@include('partials.icons.lock')</span>
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" required>
                                <button class="password-toggle" type="button" data-toggle-password="password_confirmation">Show</button>
                            </div>
                        </div>

                        <div class="field full">
                            <label for="captcha">Security Check</label>
                            <div class="input-wrap">
                                <span class="input-icon">@include('partials.icons.shield')</span>
                                <input id="captcha" type="text" name="captcha" placeholder="Enter {{ session('captcha_question') }}" maxlength="6" minlength="6" autocomplete="off" required>
                            </div>
                            <span class="field-note">Type the 6-character code shown in the placeholder before submitting.</span>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <label class="check-row">
                        <input type="checkbox" name="age_confirmed" value="1" required>
                        <span>I confirm that I am 18 years old or above.</span>
                    </label>

                    <label class="check-row">
                        <input type="checkbox" name="privacy_consent" value="1" required>
                        <span>I consent to Puffcart / CloudPuffs collecting and reviewing my uploaded ID for age verification, fraud prevention, and account security.</span>
                    </label>

                    <button type="submit" class="submit-btn">Submit for Verification</button>

                    <p class="signin-note">
                        Already have an account?
                        <a href="/login">Sign In</a>
                    </p>
                </section>
            </form>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const uploadInput = document.getElementById('valid_id');
        const uploadCard = document.getElementById('uploadCard');
        const uploadLabel = document.getElementById('uploadLabel');
        const password = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        if (uploadInput && uploadCard && uploadLabel) {
            ['dragenter', 'dragover'].forEach((eventName) => {
                uploadCard.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    uploadCard.classList.add('is-dragging');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                uploadCard.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    uploadCard.classList.remove('is-dragging');
                });
            });

            uploadInput.addEventListener('change', () => {
                const file = uploadInput.files && uploadInput.files[0];
                uploadLabel.textContent = file ? file.name : 'Drag and drop your valid ID';
            });
        }

        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.togglePassword);
                if (!input) {
                    return;
                }

                const showing = input.type === 'text';
                input.type = showing ? 'password' : 'text';
                button.textContent = showing ? 'Show' : 'Hide';
            });
        });

        if (password && strengthBar && strengthText) {
            password.addEventListener('input', () => {
                const value = password.value;
                let score = 0;

                if (value.length >= 10) score++;
                if (/[a-z]/.test(value)) score++;
                if (/[A-Z]/.test(value)) score++;
                if (/[0-9]/.test(value)) score++;
                if (/[^A-Za-z0-9]/.test(value)) score++;

                const states = [
                    { label: 'Password strength: very weak', width: '12%', color: '#EF4444' },
                    { label: 'Password strength: weak', width: '28%', color: '#F97316' },
                    { label: 'Password strength: fair', width: '48%', color: '#EAB308' },
                    { label: 'Password strength: good', width: '70%', color: '#0EA5E9' },
                    { label: 'Password strength: strong', width: '86%', color: '#22C55E' },
                    { label: 'Password strength: excellent', width: '100%', color: '#16A34A' },
                ];

                const state = states[score];
                strengthBar.style.width = state.width;
                strengthBar.style.background = state.color;
                strengthText.textContent = value ? state.label : 'Minimum 10 characters with uppercase, lowercase, number, and symbol.';
            });
        }
    });
</script>

@endsection
