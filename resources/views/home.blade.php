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
        overflow: visible;
        position: relative;
    }

    .vapor-bg {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }

    .vapor-bg::before,
    .vapor-bg::after,
    .vapor-layer {
        content: "";
        position: absolute;
        width: 58vw;
        height: 24vw;
        min-width: 420px;
        min-height: 170px;
        border-radius: 999px;
        filter: blur(28px);
        opacity: 0.5;
        transform: translate3d(0, 0, 0);
        animation: vaporDrift 18s ease-in-out infinite alternate;
    }

    .vapor-bg::before {
        left: -18%;
        top: 8%;
        background:
            radial-gradient(circle at 18% 50%, rgba(255, 255, 255, 0.92), transparent 34%),
            radial-gradient(circle at 56% 46%, rgba(207, 231, 255, 0.78), transparent 36%),
            radial-gradient(circle at 86% 58%, rgba(235, 246, 255, 0.72), transparent 32%);
    }

    .vapor-bg::after {
        right: -20%;
        bottom: 4%;
        background:
            radial-gradient(circle at 18% 56%, rgba(223, 241, 255, 0.68), transparent 34%),
            radial-gradient(circle at 52% 48%, rgba(255, 255, 255, 0.82), transparent 38%),
            radial-gradient(circle at 86% 50%, rgba(178, 214, 255, 0.46), transparent 34%);
        animation-duration: 22s;
        animation-delay: -6s;
    }

    .vapor-layer {
        right: 15%;
        top: 16%;
        width: 38vw;
        height: 15vw;
        min-width: 300px;
        min-height: 120px;
        background:
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.72), transparent 34%),
            radial-gradient(circle at 60% 48%, rgba(191, 224, 255, 0.58), transparent 38%),
            radial-gradient(circle at 90% 52%, rgba(234, 242, 255, 0.58), transparent 32%);
        animation-duration: 20s;
        animation-delay: -10s;
    }

    @keyframes vaporDrift {
        0% {
            transform: translate3d(-16px, 8px, 0) scale(1);
        }

        100% {
            transform: translate3d(42px, -18px, 0) scale(1.08);
        }
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
        position: relative;
        overflow: hidden;
    }

    .hero-content,
    .hero-visual {
        position: relative;
        z-index: 1;
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
        gap: 30px;
        width: min(100vw - 116px, 850px);
    }

    .stat-item {
        align-items: center;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #DCE7F5;
        border-radius: 8px;
        box-shadow: 0 22px 48px rgba(15, 23, 42, 0.08);
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 136px;
        padding: 26px 24px;
        text-align: center;
    }

    .stat-value {
        color: #0B63F6;
        display: block;
        font-size: 34px;
        font-weight: 800;
        letter-spacing: 0;
        line-height: 1;
        margin-bottom: 24px;
    }

    .stat-label {
        color: #667085;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.04em;
        line-height: 1;
        text-transform: uppercase;
    }

    .hero-visual {
        background:
            radial-gradient(circle at 50% 46%, rgba(11, 99, 246, 0.14), transparent 34%),
            linear-gradient(135deg, rgba(234, 242, 255, 0.9) 0%, rgba(255, 255, 255, 0.86) 58%, rgba(247, 251, 255, 0.88) 100%);
        border: 1px solid #DDE8F7;
        border-radius: var(--radius-lg);
        padding: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 22px 44px rgba(15, 23, 42, 0.08);
    }

    .hero-visual .vapor-bg::before,
    .hero-visual .vapor-bg::after,
    .hero-visual .vapor-layer {
        width: 420px;
        height: 160px;
        min-width: 0;
        min-height: 0;
        filter: blur(22px);
        opacity: 0.64;
    }

    .hero-product-card {
        display: grid;
        place-items: center;
        width: min(100%, 460px);
        min-height: 360px;
        padding: 28px;
        position: relative;
        z-index: 1;
        color: inherit;
        text-decoration: none;
        perspective: 1000px;
    }

    .hero-product-image {
        width: min(100%, 390px);
        aspect-ratio: 4 / 3;
        border-radius: 24px;
        background:
            linear-gradient(145deg, rgba(255, 255, 255, 0.94), rgba(237, 246, 255, 0.88)),
            radial-gradient(circle at 50% 50%, rgba(11, 99, 246, 0.14), transparent 58%);
        border: 1px solid rgba(155, 189, 251, 0.46);
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.92),
            0 26px 48px rgba(15, 23, 42, 0.12);
        display: grid;
        place-items: center;
        color: #0B63F6;
        font-size: 82px;
        margin: 0;
        overflow: visible;
        transform-style: preserve-3d;
        animation: heroProductFloat 8s ease-in-out infinite;
        position: relative;
    }

    .hero-product-image::before,
    .hero-product-image::after {
        content: "";
        position: absolute;
        border-radius: 18px;
        pointer-events: none;
    }

    .hero-product-image::before {
        inset: 24px 18px auto auto;
        width: 42%;
        height: 34%;
        background: rgba(11, 99, 246, 0.09);
        transform: translate3d(16px, -34px, -18px) rotate(-5deg);
    }

    .hero-product-image::after {
        inset: auto auto 22px 18px;
        width: 50%;
        height: 28%;
        background: rgba(94, 234, 212, 0.15);
        transform: translate3d(-18px, 28px, -16px) rotate(4deg);
    }

    .hero-product-image img {
        width: min(100%, 360px);
        height: min(100%, 280px) !important;
        object-fit: contain;
        display: block;
        padding: 12px;
        filter: drop-shadow(0 24px 26px rgba(15, 23, 42, 0.2));
        transform: translateZ(28px);
        position: relative;
        z-index: 1;
    }

    .hero-product-card::after {
        background: radial-gradient(ellipse at center, rgba(15, 23, 42, 0.16), transparent 66%);
        bottom: 30px;
        content: "";
        height: 38px;
        left: 16%;
        position: absolute;
        right: 16%;
        transform: rotateX(72deg);
        z-index: -1;
    }

    @keyframes heroProductFloat {
        0% {
            transform: translateY(0) rotate(-1deg);
        }

        50% {
            transform: translateY(-10px) rotate(1deg);
        }

        100% {
            transform: translateY(0) rotate(-1deg);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-product-image {
            animation: none;
        }
    }

    .hero-product-fallback {
        display: grid;
        place-items: center;
        width: 132px;
        height: 132px;
        border-radius: 28px;
        background: linear-gradient(135deg, #0B63F6, #4A93FF);
        color: #FFFFFF;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.05;
        text-align: center;
        box-shadow: 0 18px 28px rgba(11, 99, 246, 0.22);
    }

    .hero-product-kicker {
        color: #0B63F6;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .hero-product-card h3 {
        font-size: 18px;
        line-height: 1.28;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hero-product-card p {
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 16px;
        min-height: 62px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hero-product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: #0B63F6;
        font-weight: 800;
    }

    .hero-product-meta span:last-child {
        color: #64748B;
        font-size: 12px;
        font-weight: 700;
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
        display: flex;
        flex-direction: column;
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
        background:
            radial-gradient(circle at 50% 38%, rgba(11, 99, 246, 0.12), transparent 34%),
            linear-gradient(180deg, #F3F7FC 0%, #FFFFFF 100%);
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        border-bottom: 1px solid #E5E7EB;
        position: relative;
    }

    .product-image img {
        display: block;
        height: 100%;
        object-fit: contain;
        padding: 18px;
        width: 100%;
    }

    .product-image-fallback {
        align-items: center;
        background: linear-gradient(135deg, #0B63F6, #4A93FF);
        border-radius: 18px;
        color: #FFFFFF;
        display: inline-flex;
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 800;
        height: 96px;
        justify-content: center;
        letter-spacing: 0;
        width: 96px;
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
        flex: 1;
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
            radial-gradient(circle at 88% 14%, rgba(139, 92, 246, 0.12), transparent 24%),
            linear-gradient(180deg, #07111F 0%, #0F172A 48%, #08111F 100%);
    }

    :root[data-theme="dark"] .vapor-bg::before {
        background:
            radial-gradient(circle at 18% 50%, rgba(91, 157, 255, 0.34), transparent 34%),
            radial-gradient(circle at 56% 46%, rgba(168, 85, 247, 0.22), transparent 36%),
            radial-gradient(circle at 86% 58%, rgba(215, 233, 255, 0.18), transparent 32%);
        opacity: 0.42;
    }

    :root[data-theme="dark"] .vapor-bg::after {
        background:
            radial-gradient(circle at 18% 56%, rgba(71, 121, 255, 0.22), transparent 34%),
            radial-gradient(circle at 52% 48%, rgba(148, 163, 255, 0.2), transparent 38%),
            radial-gradient(circle at 86% 50%, rgba(216, 180, 254, 0.18), transparent 34%);
        opacity: 0.48;
    }

    :root[data-theme="dark"] .vapor-layer {
        background:
            radial-gradient(circle at 20% 50%, rgba(215, 233, 255, 0.18), transparent 34%),
            radial-gradient(circle at 60% 48%, rgba(80, 165, 255, 0.24), transparent 38%),
            radial-gradient(circle at 90% 52%, rgba(167, 139, 250, 0.2), transparent 32%);
        opacity: 0.5;
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
            linear-gradient(135deg, rgba(17, 43, 79, 0.88) 0%, rgba(18, 27, 43, 0.9) 58%, rgba(15, 23, 42, 0.92) 100%);
        border-color: #2F4562;
        box-shadow: 0 24px 48px rgba(0, 0, 0, 0.34);
    }

    :root[data-theme="dark"] .hero-product-card {
        box-shadow: none;
    }

    :root[data-theme="dark"] .hero-product-image {
        background:
            linear-gradient(145deg, rgba(18, 27, 43, 0.96), rgba(17, 43, 79, 0.82)),
            radial-gradient(circle at 50% 50%, rgba(125, 183, 255, 0.18), transparent 58%);
        border-color: #2F4562;
        box-shadow:
            inset 0 1px 0 rgba(215, 233, 255, 0.08),
            0 26px 48px rgba(0, 0, 0, 0.34);
        color: #D7E9FF;
    }

    :root[data-theme="dark"] .hero-product-image::before {
        background: rgba(125, 183, 255, 0.12);
    }

    :root[data-theme="dark"] .hero-product-image::after {
        background: rgba(94, 234, 212, 0.11);
    }

    :root[data-theme="dark"] .hero-product-kicker,
    :root[data-theme="dark"] .hero-product-meta {
        color: #7DB7FF;
    }

    :root[data-theme="dark"] .hero-product-meta span:last-child {
        color: #8EA2BC;
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

    .chatbot-bot-icon {
        display: none;
        width: 42px;
        height: 42px;
    }

    .chatbot-toggle-symbol {
        display: inline-flex;
        align-items: center;
        justify-content: center;
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

    .chatbot-widget.closed {
        width: 76px;
        height: 76px;
        border: none;
        border-radius: 50%;
        background: transparent;
        box-shadow: none;
        overflow: visible;
    }

    .chatbot-widget.closed .chatbot-header {
        width: 76px;
        height: 76px;
        padding: 0;
        border-radius: 50%;
        background: transparent;
        box-shadow: 0 18px 36px rgba(11, 99, 246, 0.28);
    }

    .chatbot-widget.closed .chatbot-title {
        display: none;
    }

    .chatbot-widget.closed .chatbot-toggle {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: #0B63F6;
        display: grid;
        place-items: center;
        padding: 0;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.16);
    }

    .chatbot-widget.closed .chatbot-toggle:hover {
        background: #084EC1;
        transform: translateY(-2px);
    }

    .chatbot-widget.closed .chatbot-bot-icon {
        display: block;
    }

    .chatbot-widget.closed .chatbot-toggle-symbol {
        display: none;
    }

    :root[data-theme="dark"] .chatbot-widget {
        background: #121B2B;
        border-color: #26384F;
        box-shadow: 0 18px 46px rgba(0, 0, 0, 0.38);
    }

    :root[data-theme="dark"] .chatbot-widget.closed {
        background: transparent;
        box-shadow: none;
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
            width: 100%;
        }

        .hero-visual {
            padding: 34px 18px;
            min-height: 320px;
        }

        .hero-product-card {
            min-height: 280px;
            padding: 16px;
        }

        .hero-product-image {
            width: min(100%, 330px);
            border-radius: 20px;
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

        .chatbot-widget.closed,
        .chatbot-widget.closed .chatbot-header,
        .chatbot-widget.closed .chatbot-toggle {
            width: 72px;
            height: 72px;
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
        <div class="vapor-bg" aria-hidden="true">
            <span class="vapor-layer"></span>
        </div>

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
                    <span class="stat-label">PRODUCTS</span>
                </div>

                <div class="stat-item">
                    <span class="stat-value">1,250+</span>
                    <span class="stat-label">CUSTOMERS</span>
                </div>

                <div class="stat-item">
                    <span class="stat-value">4.9★</span>
                    <span class="stat-label">RATING</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="vapor-bg" aria-hidden="true">
                <span class="vapor-layer"></span>
            </div>
            @php
                $heroProduct = $heroDeviceProduct ?? null;
            @endphp
            <a class="hero-product-card" href="{{ $heroProduct ? route('product.show', $heroProduct) : route('shop') }}">
                <div class="hero-product-image">
                    @if($heroProduct?->image_url)
                        <img src="{{ $heroProduct->image_url }}" alt="{{ $heroProduct->name }}">
                    @else
                        <span class="hero-product-fallback">E<br>Devices</span>
                    @endif
                </div>
            </a>
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
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                        @else
                            <span class="product-image-fallback">PC</span>
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

<div class="chatbot-widget closed" id="chatbotWidget">
    <div class="chatbot-header">
        <span class="chatbot-title">Puffcart Assistant</span>
        <button type="button" class="chatbot-toggle" id="chatbotToggle" aria-label="Open Puffcart Assistant">
            <svg class="chatbot-bot-icon" viewBox="0 0 64 64" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                <path d="M13 27C13 17.1 21.1 9 31 9H33C42.9 9 51 17.1 51 27V37C51 46.9 42.9 55 33 55H31C21.1 55 13 46.9 13 37V27Z" fill="white"/>
                <path d="M12 28C7.6 29.4 4.5 33.6 4.5 38.6C4.5 43.6 7.7 47.9 12.2 49.2C10.7 42.2 10.6 35.2 12 28Z" fill="white"/>
                <path d="M52 28C56.4 29.4 59.5 33.6 59.5 38.6C59.5 43.6 56.3 47.9 51.8 49.2C53.3 42.2 53.4 35.2 52 28Z" fill="white"/>
                <path d="M17 15C20.4 9.8 25.8 7 32 7C38.2 7 43.6 9.8 47 15" stroke="white" stroke-width="5" stroke-linecap="round"/>
                <path d="M24 34C25.8 31.8 29.2 31.8 31 34" stroke="#0B63F6" stroke-width="4" stroke-linecap="round"/>
                <path d="M37 34C38.8 31.8 42.2 31.8 44 34" stroke="#0B63F6" stroke-width="4" stroke-linecap="round"/>
                <path d="M28.5 42C30.4 44.3 33.6 44.3 35.5 42" stroke="#0B63F6" stroke-width="4" stroke-linecap="round"/>
                <path d="M14 49L9 55C12.9 54.5 15.8 53 18 50.5L14 49Z" fill="white"/>
            </svg>
            <span class="chatbot-toggle-symbol">-</span>
        </button>
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

            addMessage(cleanMessage, 'user');
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
            const toggleSymbol = toggle.querySelector('.chatbot-toggle-symbol');
            const isClosed = widget.classList.contains('closed');

            if (toggleSymbol) {
                toggleSymbol.textContent = isClosed ? '+' : '-';
            }

            toggle.setAttribute('aria-label', isClosed ? 'Open Puffcart Assistant' : 'Close Puffcart Assistant');
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
