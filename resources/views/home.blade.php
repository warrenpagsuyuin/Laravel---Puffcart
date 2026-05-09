@extends('layouts.app')

@section('title', 'Home')

@section('content')

<style>
    body {
        background: #ffffff;
    }

    .site {
        background: #ffffff;
        min-height: 100vh;
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

    .hero {
        min-height: 560px;
        padding: 80px 40px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
        border-bottom: 1px solid var(--border);
    }

    .hero-content h1 {
        font-size: 52px;
        line-height: 1.1;
        color: var(--text-primary);
        margin-bottom: 16px;
    }

    .hero-content .tagline {
        font-size: 18px;
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 24px;
    }

    .hero-content p {
        font-size: 16px;
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 32px;
        max-width: 500px;
    }

    .hero-actions {
        display: flex;
        gap: 16px;
        margin-bottom: 48px;
    }

    .btn-primary {
        padding: 12px 28px;
        background: var(--primary);
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
        background: var(--primary-hover);
        box-shadow: var(--shadow-md);
        color: white;
    }

    .btn-secondary {
        padding: 12px 28px;
        background: var(--bg-light);
        color: var(--primary);
        border: 1px solid var(--border);
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
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .hero-stats {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 24px;
    }

    .stat-item {
        padding: 16px;
        background: var(--bg-light);
        border-radius: var(--radius);
        text-align: center;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
        display: block;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .hero-visual {
        background: linear-gradient(135deg, var(--primary-light) 0%, #ffffff 100%);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        position: relative;
    }

    .hero-emoji {
        font-size: 120px;
        filter: drop-shadow(0 4px 12px rgba(0, 102, 255, 0.15));
    }

    .feature-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: var(--primary);
        color: white;
        padding: 8px 12px;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
    }

    .section {
        padding: 60px 40px;
        border-bottom: 1px solid var(--border);
    }

    .section-label {
        font-size: 12px;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .section-title {
        font-size: 36px;
        color: var(--text-primary);
        margin-bottom: 8px;
        font-weight: 700;
    }

    .section-desc {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 40px;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .category-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 32px 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }

    .category-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .category-card h3 {
        font-size: 16px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .category-card p {
        font-size: 13px;
        color: var(--text-muted);
    }

    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
    }

    .products-header a {
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .product-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }

    .product-image {
        background: var(--bg-light);
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        border-bottom: 1px solid var(--border);
        position: relative;
    }

    .product-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: var(--primary);
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
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .product-card h3 {
        font-size: 15px;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .product-meta {
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 12px;
    }

    .product-rating {
        color: var(--primary);
        font-size: 12px;
        margin-bottom: 12px;
    }

    .product-price {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
    }

    .banner {
        margin: 40px 40px;
        padding: 48px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
        border-radius: var(--radius-lg);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 40px;
    }

    .banner h2 {
        font-size: 32px;
        margin-bottom: 12px;
        color: white;
    }

    .banner p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
    }

    .banner-btn {
        padding: 12px 28px;
        background: white;
        color: var(--primary);
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
        background: var(--bg-light);
        padding: 60px 40px;
        border-top: 1px solid var(--border);
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 40px;
    }

    .footer h3 {
        font-size: 14px;
        color: var(--text-primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
    }

    .footer p,
    .footer a {
        color: var(--text-secondary);
        display: block;
        margin-bottom: 10px;
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s ease;
    }

    .footer a:hover {
        color: var(--primary);
    }

    .footer-bottom {
        border-top: 1px solid var(--border);
        padding: 24px 40px;
        color: var(--text-muted);
        font-size: 12px;
        text-align: center;
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
            <a href="/">Home</a>
            <a href="/shop">Shop</a>
            <a href="/cart">Cart</a>
            <a href="/login">Login</a>
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
                        @if($availableFlavors->isNotEmpty())
                            <div class="product-meta">Flavors: {{ $availableFlavors->pluck('name')->implode(', ') }}</div>
                        @endif
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
