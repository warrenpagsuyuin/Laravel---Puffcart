@extends('layouts.app')

@section('title', 'Home')

@section('content')

<style>
    body {
        background: #F8FAFC;
    }

    .site {
        background:
            radial-gradient(circle at 10% 8%, rgba(11, 99, 246, 0.08), transparent 28%),
            linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 42%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .nav {
        background: rgba(255, 255, 255, 0.94);
        border-bottom: 1px solid #E5E7EB;
        padding: 0 48px;
        min-height: 72px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        backdrop-filter: blur(16px);
    }

    .logo {
        font-family: 'Poppins', sans-serif;
        color: #0B63F6;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 0;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav a {
        color: #334155;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .nav a:hover {
        background: #EAF2FF;
        color: #0B63F6;
    }

    .hero {
        min-height: 560px;
        padding: 88px 48px 76px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 72px;
        align-items: center;
        border-bottom: 1px solid #E5E7EB;
    }

    .hero-content h1 {
        font-size: 52px;
        line-height: 1.1;
        color: #0F172A;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .hero-content .tagline {
        font-size: 14px;
        color: #0B63F6;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 24px;
    }

    .hero-content p {
        font-size: 16px;
        color: #374151;
        line-height: 1.75;
        margin-bottom: 32px;
        max-width: 560px;
    }

    .hero-actions {
        display: flex;
        gap: 16px;
        margin-bottom: 48px;
    }

    .btn-primary {
        padding: 12px 28px;
        background: #0B63F6;
        color: white;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #084EC1;
        box-shadow: 0 12px 24px rgba(11, 99, 246, 0.22);
        color: white;
    }

    .btn-secondary {
        padding: 12px 28px;
        background: #FFFFFF;
        color: #0B63F6;
        border: 1px solid #D6E0EE;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        border-color: #9BBDFB;
        background: #EAF2FF;
    }

    .hero-stats {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 24px;
    }

    .stat-item {
        padding: 18px 16px;
        background: rgba(255, 255, 255, 0.88);
        border: 1px solid #E4ECF7;
        border-radius: var(--radius);
        text-align: center;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #0B63F6;
        display: block;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    .hero-visual {
        background:
            radial-gradient(circle at 50% 46%, rgba(11, 99, 246, 0.14), transparent 34%),
            linear-gradient(135deg, #EAF2FF 0%, #FFFFFF 58%, #F7FBFF 100%);
        border: 1px solid #DDE8F7;
        border-radius: var(--radius-lg);
        padding: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        position: relative;
        box-shadow: 0 22px 44px rgba(15, 23, 42, 0.08);
    }

    .hero-emoji {
        font-size: 120px;
        filter: drop-shadow(0 14px 24px rgba(11, 99, 246, 0.2));
    }

    .feature-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #0B63F6;
        color: white;
        padding: 8px 12px;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
    }

    .section {
        padding: 60px 40px;
        border-bottom: 1px solid #E5E7EB;
    }

    .section-label {
        font-size: 12px;
        color: #0B63F6;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .section-title {
        font-size: 36px;
        color: #0F172A;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .section-desc {
        color: #4B5563;
        font-size: 16px;
        margin-bottom: 40px;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .category-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: var(--radius-lg);
        padding: 32px 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-card:hover {
        border-color: #9BBDFB;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.09);
        transform: translateY(-4px);
    }

    .category-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .category-card h3 {
        font-size: 16px;
        color: #0F172A;
        margin-bottom: 8px;
    }

    .category-card p {
        font-size: 13px;
        color: #6B7280;
    }

    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
    }

    .products-header a {
        color: #0B63F6;
        font-weight: 600;
        font-size: 14px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .product-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
    }

    .product-card:hover {
        border-color: #9BBDFB;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.09);
        transform: translateY(-4px);
    }

    .product-image {
        background: linear-gradient(180deg, #F3F7FC 0%, #FFFFFF 100%);
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        border-bottom: 1px solid #E5E7EB;
        position: relative;
    }

    .product-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #0B63F6;
        color: white;
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .product-body {
        padding: 20px;
    }

    .product-category {
        font-size: 11px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .product-card h3 {
        font-size: 15px;
        color: #0F172A;
        margin-bottom: 6px;
    }

    .product-meta {
        font-size: 13px;
        color: #4B5563;
        margin-bottom: 12px;
    }

    .product-rating {
        color: #0B63F6;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .product-price {
        font-size: 20px;
        font-weight: 700;
        color: #0B63F6;
    }

    .banner {
        margin: 40px 40px;
        padding: 48px;
        background:
            radial-gradient(circle at 86% 18%, rgba(255, 255, 255, 0.2), transparent 26%),
            linear-gradient(135deg, #0B63F6 0%, #0F3A8A 100%);
        border-radius: var(--radius-lg);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 40px;
        box-shadow: 0 20px 42px rgba(11, 99, 246, 0.22);
    }

    .banner h2 {
        font-size: 32px;
        margin-bottom: 12px;
        color: white;
    }

    .banner p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 16px;
    }

    .banner-btn {
        padding: 12px 28px;
        background: white;
        color: #0B63F6;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .banner-btn:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .footer {
        background: #F3F7FC;
        padding: 60px 40px;
        border-top: 1px solid #E5E7EB;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 40px;
    }

    .footer h3 {
        font-size: 14px;
        color: #0F172A;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
    }

    .footer p,
    .footer a {
        color: #4B5563;
        display: block;
        margin-bottom: 10px;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s ease;
    }

    .footer a:hover {
        color: #0B63F6;
    }

    .footer-bottom {
        border-top: 1px solid #E5E7EB;
        padding: 24px 40px;
        color: #6B7280;
        font-size: 12px;
        text-align: center;
    }

    :root[data-theme="dark"] body {
        background: #0B1220;
    }

    :root[data-theme="dark"] .site {
        background:
            radial-gradient(circle at 10% 8%, rgba(125, 183, 255, 0.12), transparent 28%),
            radial-gradient(circle at 88% 14%, rgba(94, 234, 212, 0.08), transparent 24%),
            linear-gradient(180deg, #0B1220 0%, #0F172A 48%, #0B1220 100%);
    }

    :root[data-theme="dark"] .nav {
        background: rgba(10, 16, 29, 0.94);
        border-bottom-color: #203047;
    }

    :root[data-theme="dark"] .logo,
    :root[data-theme="dark"] .section-label,
    :root[data-theme="dark"] .products-header a,
    :root[data-theme="dark"] .stat-value,
    :root[data-theme="dark"] .product-rating,
    :root[data-theme="dark"] .product-price {
        color: #7DB7FF;
    }

    :root[data-theme="dark"] .nav a {
        color: #CBD5E1;
    }

    :root[data-theme="dark"] .nav a:hover {
        background: #112B4F;
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .hero,
    :root[data-theme="dark"] .section {
        border-bottom-color: #26384F;
    }

    :root[data-theme="dark"] .hero-content h1,
    :root[data-theme="dark"] .section-title,
    :root[data-theme="dark"] .category-card h3,
    :root[data-theme="dark"] .product-card h3,
    :root[data-theme="dark"] .footer h3 {
        color: #F3F8FF;
    }

    :root[data-theme="dark"] .hero-content .tagline {
        color: #5EEAD4;
    }

    :root[data-theme="dark"] .hero-content p,
    :root[data-theme="dark"] .section-desc,
    :root[data-theme="dark"] .product-meta,
    :root[data-theme="dark"] .footer p,
    :root[data-theme="dark"] .footer a {
        color: #B7C6DA;
    }

    :root[data-theme="dark"] .btn-primary,
    :root[data-theme="dark"] .feature-badge,
    :root[data-theme="dark"] .product-badge {
        background: #2F7CF6;
    }

    :root[data-theme="dark"] .btn-primary:hover {
        background: #5B9DFF;
        box-shadow: 0 12px 24px rgba(47, 124, 246, 0.24);
    }

    :root[data-theme="dark"] .btn-secondary {
        background: #121B2B;
        color: #D7E9FF;
        border-color: #2F4562;
    }

    :root[data-theme="dark"] .btn-secondary:hover {
        background: #112B4F;
        border-color: #5B9DFF;
    }

    :root[data-theme="dark"] .stat-item,
    :root[data-theme="dark"] .category-card,
    :root[data-theme="dark"] .product-card {
        background: rgba(18, 27, 43, 0.92);
        border-color: #26384F;
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.24);
    }

    :root[data-theme="dark"] .stat-label,
    :root[data-theme="dark"] .category-card p,
    :root[data-theme="dark"] .product-category,
    :root[data-theme="dark"] .footer-bottom {
        color: #8EA2BC;
    }

    :root[data-theme="dark"] .hero-visual {
        background:
            radial-gradient(circle at 50% 46%, rgba(125, 183, 255, 0.16), transparent 34%),
            linear-gradient(135deg, #112B4F 0%, #121B2B 58%, #0F172A 100%);
        border-color: #2F4562;
        box-shadow: 0 24px 48px rgba(0, 0, 0, 0.34);
    }

    :root[data-theme="dark"] .hero-emoji {
        filter: drop-shadow(0 16px 26px rgba(125, 183, 255, 0.2));
    }

    :root[data-theme="dark"] .category-card:hover,
    :root[data-theme="dark"] .product-card:hover {
        border-color: #5B9DFF;
        box-shadow: 0 18px 38px rgba(0, 0, 0, 0.32);
    }

    :root[data-theme="dark"] .product-image {
        background: linear-gradient(180deg, #162235 0%, #111827 100%);
        border-bottom-color: #26384F;
    }

    :root[data-theme="dark"] .banner {
        background:
            radial-gradient(circle at 86% 18%, rgba(255, 255, 255, 0.14), transparent 26%),
            linear-gradient(135deg, #154FB4 0%, #071D46 100%);
        box-shadow: 0 22px 44px rgba(0, 0, 0, 0.34);
    }

    :root[data-theme="dark"] .banner-btn {
        background: #EAF2FF;
        color: #0F3A8A;
    }

    :root[data-theme="dark"] .footer {
        background: #0F172A;
        border-top-color: #26384F;
    }

    :root[data-theme="dark"] .footer a:hover {
        color: #7DB7FF;
    }

    :root[data-theme="dark"] .footer-bottom {
        border-top-color: #26384F;
        background: #0B1220;
    }

    .chatbot-widget {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 350px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.16);
        overflow: hidden;
        z-index: 9999;
        font-family: 'Inter', sans-serif;
    }

    .chatbot-header {
        background: var(--primary);
        color: white;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
    }

    .chatbot-header span {
        font-size: 14px;
    }

    .chatbot-toggle {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
    }

    .chatbot-body {
        height: 320px;
        padding: 14px;
        overflow-y: auto;
        background: #f8fafc;
    }

    .chatbot-message {
        margin-bottom: 10px;
        max-width: 85%;
        padding: 10px 12px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.4;
        word-wrap: break-word;
    }

    .chatbot-message.bot {
        background: #ffffff;
        color: #333;
        border: 1px solid #e5e7eb;
        margin-right: auto;
    }

    .chatbot-message.user {
        background: var(--primary);
        color: white;
        margin-left: auto;
    }

    .chatbot-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 10px 12px;
        background: white;
        border-top: 1px solid var(--border);
    }

    .chatbot-suggestions button {
        font-size: 11px;
        background: var(--primary-light);
        color: var(--primary);
        border: none;
        border-radius: 999px;
        padding: 6px 10px;
        cursor: pointer;
    }

    .chatbot-footer {
        display: flex;
        gap: 8px;
        padding: 12px;
        border-top: 1px solid var(--border);
        background: white;
    }

    .chatbot-footer input {
        flex: 1;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px;
        font-size: 13px;
    }

    .chatbot-footer button {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .chatbot-widget.closed .chatbot-body,
    .chatbot-widget.closed .chatbot-footer,
    .chatbot-widget.closed .chatbot-suggestions {
        display: none;
    }

    :root[data-theme="dark"] .chatbot-widget {
        background: #121B2B;
        border-color: #26384F;
        box-shadow: 0 18px 46px rgba(0, 0, 0, 0.38);
    }

    :root[data-theme="dark"] .chatbot-header,
    :root[data-theme="dark"] .chatbot-message.user,
    :root[data-theme="dark"] .chatbot-footer button {
        background: #2F7CF6;
    }

    :root[data-theme="dark"] .chatbot-body {
        background: #0F172A;
    }

    :root[data-theme="dark"] .chatbot-message.bot {
        background: #162235;
        color: #D7E3F4;
        border-color: #26384F;
    }

    :root[data-theme="dark"] .chatbot-suggestions,
    :root[data-theme="dark"] .chatbot-footer {
        background: #121B2B;
        border-top-color: #26384F;
    }

    :root[data-theme="dark"] .chatbot-suggestions button {
        background: #112B4F;
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .chatbot-footer input {
        background: #0F172A;
        border-color: #26384F;
        color: #E5EEF9;
    }

    :root[data-theme="dark"] .chatbot-footer input::placeholder {
        color: #8EA2BC;
    }

    @media (max-width: 1200px) {
        .products-grid,
        .categories-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .nav {
            padding: 16px 20px;
            flex-direction: column;
            gap: 16px;
        }

        .nav-links {
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .hero {
            grid-template-columns: 1fr;
            padding: 60px 20px;
            gap: 40px;
        }

        .hero-content h1 {
            font-size: 38px;
        }

        .hero-stats {
            grid-template-columns: 1fr;
        }

        .categories-grid,
        .products-grid {
            grid-template-columns: 1fr;
        }

        .category-image-wrap img {
            max-width: 72px;
            max-height: 72px;
            object-fit: contain;
            display: block;
            margin: 0 auto 12px;
        }

        .category-card .category-icon i {
            font-size: 48px;
            display: block;
            margin-bottom: 12px;
            color: #0b66ff;
        }

        .section {
            padding: 50px 20px;
        }

        .products-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .banner {
            margin: 30px 20px;
            padding: 32px 24px;
            flex-direction: column;
            align-items: flex-start;
        }

        .footer {
            grid-template-columns: 1fr;
            padding: 50px 20px;
        }

        .chatbot-widget {
            width: calc(100% - 32px);
            right: 16px;
            bottom: 16px;
        }
    }
</style>

<div class="site">
    <nav class="nav">
        <div class="logo">Puffcart</div>

        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('cart') }}">Cart</a>
            @auth
                <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="tagline">Premium Vaping Products</div>

            <h1>Your Trusted Vape Shop Online</h1>

            <p>
                Discover 200+ authentic devices, premium e-liquids, coils, and accessories from top brands worldwide.
                Same-day delivery available in Metro Manila. Secure checkout with multiple payment options.
            </p>

            <div class="hero-actions">
                <a href="/shop" class="btn-primary">Shop Now</a>
                <a href="/shop" class="btn-secondary">Browse Deals</a>
            </div>

            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-value">200+</span>
                    <span class="stat-label">Products</span>
                </div>

                <div class="stat-item">
                    <span class="stat-value">1,250+</span>
                    <span class="stat-label">Customers</span>
                </div>

                <div class="stat-item">
                    <span class="stat-value">4.9★</span>
                    <span class="stat-label">Rating</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="feature-badge">Featured</div>
            <div class="hero-emoji">💨</div>
        </div>
    </section>

    <section class="section">
        <div class="section-label">Shop Categories</div>
        <h2 class="section-title">Browse by Type</h2>
        <p class="section-desc">Find the products you need by category.</p>

        <div class="categories-grid">
            @if($categories->isNotEmpty())
                @foreach($categories as $category)
                    <a class="category-card" href="{{ route('shop', ['category' => $category->slug]) }}">
                        @php
                            $slug = $category->slug ?? null;
                            $localImage = null;
                            if ($slug) {
                                foreach (['png','jpg','jpeg','webp','svg'] as $ext) {
                                    $path = public_path("images/categories/{$slug}.{$ext}");
                                    if (file_exists($path)) {
                                        $localImage = asset("images/categories/{$slug}.{$ext}");
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if(!empty($localImage))
                            <div class="category-image-wrap">
                                <img src="{{ $localImage }}" alt="{{ $category->name }}">
                            </div>
                        @elseif(!empty($category->image_url))
                            <div class="category-image-wrap">
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                            </div>
                        @elseif(!empty($category->icon))
                            @php $icon = trim($category->icon); @endphp
                            @if(\Illuminate\Support\Str::startsWith($icon, ['http', '/', 'data:']))
                                <div class="category-image-wrap">
                                    <img src="{{ $icon }}" alt="{{ $category->name }}">
                                </div>
                            @elseif(\Illuminate\Support\Str::contains($icon, '<svg') || \Illuminate\Support\Str::startsWith($icon, '<'))
                                <div class="category-icon">{!! $icon !!}</div>
                            @elseif(mb_strlen($icon) <= 2)
                                <div class="category-icon">{{ $icon }}</div>
                            @else
                                <div class="category-icon"><i class="{{ $icon }}"></i></div>
                            @endif
                        @else
                            <div class="category-icon">📦</div>
                        @endif

                        <h3>{{ $category->name }}</h3>
                        <p>{{ $category->products_count ?? 'Browse products' }} products</p>
                    </a>
                @endforeach
            @else
                <div class="category-card">
                    <div class="category-icon">🔋</div>
                    <h3>Devices</h3>
                    <p>Pod systems, box mods & more</p>
                </div>

                <div class="category-card">
                    <div class="category-icon">🧪</div>
                    <h3>E-Liquids</h3>
                    <p>Premium flavors & nicotine levels</p>
                </div>

                <div class="category-card">
                    <div class="category-icon">⚙️</div>
                    <h3>Coils & Pods</h3>
                    <p>Replacement coils & pods</p>
                </div>

                <div class="category-card">
                    <div class="category-icon">🛠️</div>
                    <h3>Accessories</h3>
                    <p>Chargers, cases & more</p>
                </div>
            @endif
        </div>
    </section>

    <section class="section">
        <div class="products-header">
            <div>
                <div class="section-label">Top Picks</div>
                <h2 class="section-title">Featured Products</h2>
                <p class="section-desc">Popular items from Puffcart customers.</p>
            </div>

            <a href="/shop">View All Products →</a>
        </div>

        <div class="products-grid">
            @forelse($featuredProducts as $product)
                @php
                    $availableFlavors = $product->availableFlavors;
                @endphp
                <a class="product-card" href="{{ route('product.show', $product) }}">
                    <div class="product-image">
                        @if($product->badge && $product->badge !== 'none')
                            <span class="product-badge">{{ $product->badge }}</span>
                        @endif

                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="height:100%;width:100%;object-fit:cover;">
                        @else
                            PC
                        @endif
                    </div>

                    <div class="product-body">
                        <div class="product-category">{{ $product->category_name }}</div>
                        <h3>{{ $product->name }}</h3>
                        <div class="product-meta">{{ $product->brand ?: 'Puffcart' }}</div>
                        {{-- Flavors removed from featured product cards for cleaner layout --}}
                        <div class="product-rating">{{ number_format((float) $product->rating, 1) }} / 5 rating</div>
                        <div class="product-price">PHP {{ number_format($product->price, 2) }}</div>
                    </div>
                </a>
            @empty
            <div class="product-card">
                <div class="product-image">
                    <span class="product-badge">New</span>
                    💨
                </div>

                <div class="product-body">
                    <div class="product-category">Pod System</div>
                    <h3>XROS 4 Mini</h3>
                    <div class="product-meta">Vaporesso</div>
                    <div class="product-rating">★★★★★ (214 reviews)</div>
                    <div class="product-price">₱1,299</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <span class="product-badge" style="background: #ff6b6b;">Hot</span>
                    💨
                </div>

                <div class="product-body">
                    <div class="product-category">E-Liquid</div>
                    <h3>Lava Flow</h3>
                    <div class="product-meta">Naked 100</div>
                    <div class="product-rating">★★★★☆ (428 reviews)</div>
                    <div class="product-price">₱450</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <span class="product-badge" style="background: #ffa500;">Sale</span>
                    💨
                </div>

                <div class="product-body">
                    <div class="product-category">Box Mod</div>
                    <h3>DRAG S Pro</h3>
                    <div class="product-meta">VooPoo</div>
                    <div class="product-rating">★★★★★ (356 reviews)</div>
                    <div class="product-price">₱2,100</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <span class="product-badge">New</span>
                    💨
                </div>

                <div class="product-body">
                    <div class="product-category">Pod Mod</div>
                    <h3>Caliburn A3S</h3>
                    <div class="product-meta">Uwell</div>
                    <div class="product-rating">★★★★☆ (192 reviews)</div>
                    <div class="product-price">₱1,650</div>
                </div>
            </div>
            @endforelse
        </div>
    </section>

    <div class="banner">
        <div>
            <h2>Ready to Order?</h2>
            <p>
                Fast, secure checkout with GCash, Maya, Card, Bank Transfer, or Cash on Delivery.
                Same-day delivery in Metro Manila.
            </p>
        </div>

        <a href="/shop" class="banner-btn">Shop Now</a>
    </div>

    <footer class="footer">
        <div>
            <h3>Puffcart</h3>
            <p>Your trusted online vape destination offering premium products from top brands worldwide.</p>
            <p style="margin-top: 16px;">Quality products. Fast delivery. Secure payments.</p>
        </div>

        <div>
            <h3>Shop</h3>
            <a href="/shop">All Products</a>
            <a href="/shop">Devices</a>
            <a href="/shop">E-Liquids</a>
            <a href="/shop">Coils & Pods</a>
        </div>

        <div>
            <h3>Account</h3>
            <a href="/login">My Orders</a>
            <a href="/profile">Profile</a>
            <a href="/tracking">Tracking</a>
            <a href="/login">Support</a>
        </div>

        <div>
            <h3>Company</h3>
            <a href="#">About Us</a>
            <a href="#">Shipping Info</a>
            <a href="#">Returns</a>
            <a href="#">Contact</a>
        </div>
    </footer>

    <div class="footer-bottom">
        © 2026 Puffcart — CloudPuffs Shop. All rights reserved.
    </div>
</div>

<div class="chatbot-widget" id="chatbotWidget">
    <div class="chatbot-header">
        <span>Puffcart Assistant</span>
        <button type="button" class="chatbot-toggle" id="chatbotToggle">-</button>
    </div>

    <div class="chatbot-body" id="chatbotMessages">
        <div class="chatbot-message bot">
            Hi! I'm Puffcart Assistant. Ask me about delivery, payment, products, tracking, age verification, returns, or support.
        </div>
    </div>

    <div class="chatbot-suggestions">
        <button type="button" data-question="What payment methods do you accept?">Payment</button>
        <button type="button" data-question="How does delivery work?">Delivery</button>
        <button type="button" data-question="How can I track my order?">Tracking</button>
        <button type="button" data-question="Do I need age verification?">Age verification</button>
    </div>

    <form class="chatbot-footer" id="chatbotForm">
        <input type="text" id="chatbotInput" placeholder="Ask about Puffcart..." autocomplete="off">
        <button type="submit" id="chatbotSubmit">Send</button>
    </form>
</div>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const widget = document.getElementById('chatbotWidget');
        const toggle = document.getElementById('chatbotToggle');
        const form = document.getElementById('chatbotForm');
        const input = document.getElementById('chatbotInput');
        const submit = document.getElementById('chatbotSubmit');
        const messages = document.getElementById('chatbotMessages');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');

        if (!csrfMeta) {
            console.error('CSRF meta tag is missing from layouts/app.blade.php');
            return;
        }

        const csrfToken = csrfMeta.getAttribute('content');

        function addMessage(message, sender) {
            const bubble = document.createElement('div');
            bubble.classList.add('chatbot-message', sender);
            bubble.textContent = message;

            messages.appendChild(bubble);
            messages.scrollTop = messages.scrollHeight;
        }

        function setSending(isSending) {
            input.disabled = isSending;
            submit.disabled = isSending;
            submit.textContent = isSending ? 'Sending' : 'Send';
        }

        async function sendMessage(message) {
            const cleanMessage = message.trim();

            if (!cleanMessage) {
                return;
            }

            addMessage(cleanMessage, 'user');art
            input.value = '';
            setSending(true);

            try {
                const response = await fetch("{{ route('chatbot.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: cleanMessage,
                    }),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    addMessage(data.message || 'Sorry, the assistant could not process that message.', 'bot');
                    return;
                }

                addMessage(data.reply || 'I received your message, but I do not have a reply yet.', 'bot');
            } catch (error) {
                addMessage('Sorry, I could not connect to the assistant right now.', 'bot');
                console.error(error);
            } finally {
                setSending(false);
                input.focus();
            }
        }

        toggle.addEventListener('click', () => {
            widget.classList.toggle('closed');
            toggle.textContent = widget.classList.contains('closed') ? '+' : '-';
        });

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            sendMessage(input.value);
        });

        document.querySelectorAll('.chatbot-suggestions button').forEach((button) => {
            button.addEventListener('click', () => {
                sendMessage(button.dataset.question);
            });
        });

        console.log('Puffcart Assistant ready');
    });
</script>

@endsection
