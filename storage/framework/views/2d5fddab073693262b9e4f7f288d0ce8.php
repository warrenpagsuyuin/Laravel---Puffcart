

<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>

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

    .footer p, .footer a {
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

    @media (max-width: 1200px) {
        .products-grid, .categories-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .hero {
            grid-template-columns: 1fr;
        }

        .footer {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="site">

    <nav class="nav">
        <div class="logo">VapeVault</div>
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
            </div>
            <a href="/shop">View All Products →</a>
        </div>

        <div class="products-grid">
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
        </div>
    </section>

    <div class="banner">
        <div>
            <h2>Ready to Order?</h2>
            <p>Fast, secure checkout with GCash, Maya, Card, Bank Transfer, or Cash on Delivery. Same-day delivery in Metro Manila.</p>
        </div>
        <a href="/shop" class="banner-btn">Shop Now</a>
    </div>

    <footer class="footer">
        <div>
            <h3>VapeVault</h3>
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
        © 2026 VapeVault — CloudPuffs Shop. All rights reserved.
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PLPASIG\Downloads\puffcart-laravel\puffcart\resources\views/home.blade.php ENDPATH**/ ?>