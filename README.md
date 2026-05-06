# ⚡ Puffcart — A Laravel-Powered E-Commerce System for Vape Products

> **CS 402 — Open Source Programming With Framework (Laravel)**
> Pamantasan ng Lungsod ng Pasig (PLP)

---

## 👥 Project Members

| Name | Role |
|------|------|
| Alvis, David Andrei | Backend / Database |
| Pagsuyuin, Warren V. | Frontend / UI Design |
| Torralba, Xerxes Jan | Full Stack / Integration |

**Instructor:** Dawn Bernadette Menor
**Course:** CS 402 — Open Source Programming With Framework

---

## 📌 Project Overview

**Puffcart** is a full-featured, cyberpunk-themed e-commerce web platform built with the **Laravel 11** PHP framework for *CloudPuffs Vape Shop*. It replaces manual order-taking and social media messaging with a centralized digital platform for managing products, orders, inventory, payments, and sales analytics.

---

## 🚀 Features

### 👤 Authentication & Role Management
- Secure registration and login with **role-based access control** (Admin / Customer)
- **Age verification gate** (18+) before accessing the shop
- Session management and remember-me functionality

### 🛒 Customer Features
- Browse products by category, brand, and price range
- Product detail pages with specs, reviews & star ratings
- Shopping cart with quantity controls
- Checkout with promo code support
- Real-time **order tracking** with step-by-step progress
- Order history and profile management
- **AI Chatbot (VaultBot)** for product queries and order support

### 📦 Product Management (Admin)
- Add, edit, deactivate products with images, specs, badges (New / Hot / Sale)
- Category management
- Featured product controls

### 📋 Order Management (Admin)
- View and filter all orders by status
- Update order status: Pending → Processing → Packed → Out for Delivery → Completed / Cancelled
- Auto-generate order tracking updates on status change

### 📊 Inventory Management
- Stock level monitoring with reorder alerts
- Color-coded low-stock indicators
- Quantity updates after restocking

### 💳 Payment Management
- Supports: **GCash**, **Maya**, **Cash on Delivery**, **Bank Transfer**
- Payment status tracking: Pending → Paid → Refunded
- Receipt generation

### 📈 Admin Dashboard & Sales Reports
- Real-time statistics (total sales, orders, customers, low stock)
- Weekly sales bar chart
- Top-selling products ranking
- Payment method breakdown
- Export reports in **PDF** and **CSV**

### ⚡ Additional Features
- **WebSocket** real-time order notifications (Pusher)
- **Same-day delivery** support for Metro Manila
- Cyberpunk-themed UI with Orbitron font, neon grid background, and glitch effects

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend Framework | Laravel 11 (PHP 8.2) |
| Frontend Styling | Tailwind CSS + Custom Cyberpunk CSS |
| Database | MySQL 8.0 |
| Real-time | Pusher (WebSockets) |
| PDF Export | barryvdh/laravel-dompdf |
| Image Handling | intervention/image |
| Build Tool | Vite |
| Testing | PHPUnit / Laravel Test Suite |
| CI/CD | GitHub Actions |

---

## 📁 Project Structure

```
puffcart/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Dashboard, Products, Orders, Inventory, Reports
│   │   │   ├── Auth/            # Login, Register, Age Verification
│   │   │   └── Customer/        # Home, Shop, Cart, Orders, Profile
│   │   └── Middleware/
│   │       ├── AgeVerified.php
│   │       └── RoleMiddleware.php
│   └── Models/                  # User, Product, Order, Payment, etc.
├── database/
│   ├── migrations/              # All DB schema migrations
│   └── seeders/                 # Demo data seeder
├── resources/
│   └── views/
│       ├── layouts/             # app.blade.php, admin.blade.php
│       ├── admin/               # Dashboard, Products, Orders, Reports
│       ├── auth/                # Login, Register
│       ├── customer/            # Home, Shop, Cart, Checkout, Tracking
│       └── age-verify.blade.php
├── routes/
│   └── web.php
├── .github/
│   └── workflows/ci.yml         # GitHub Actions CI
└── README.md
```

---

## ⚙️ Installation & Setup

### Requirements
- PHP >= 8.2
- Composer
- Node.js >= 18 + npm
- MySQL 8.0

### Step-by-Step

```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/puffcart.git
cd puffcart

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure your database in .env
#    DB_DATABASE=puffcart
#    DB_USERNAME=your_username
#    DB_PASSWORD=your_password

# 7. Run migrations and seeders
php artisan migrate --seed

# 8. Link storage
php artisan storage:link

# 9. Build frontend assets
npm run dev

# 10. Start development server
php artisan serve
```

Visit: **http://localhost:8000**

### Default Accounts (after seeding)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@puffcart.ph | password |
| Customer | juan@example.com | password |

---

## 🗄 Database Schema

```
users              — id, name, email, password, role, phone, address, age_verified
categories         — id, name, slug, icon, color
products           — id, category_id, name, slug, brand, sku, price, stock, specs (JSON), badge
cart_items         — id, user_id, product_id, quantity
orders             — id, user_id, order_number, status, subtotal, total, payment_method
order_items        — id, order_id, product_id, product_name, price, quantity
order_tracking     — id, order_id, status, message, occurred_at
payments           — id, order_id, reference_number, method, amount, status, paid_at
product_reviews    — id, product_id, user_id, rating, comment
```

---

## 🌐 Routes Summary

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Homepage |
| GET | `/age-verify` | Age gate |
| GET | `/shop` | Product listing |
| GET | `/shop/{slug}` | Product detail |
| GET | `/cart` | Shopping cart |
| POST | `/cart/add` | Add to cart |
| GET | `/checkout` | Checkout page |
| POST | `/checkout` | Place order |
| GET | `/track/{order}` | Order tracking |
| GET | `/admin/dashboard` | Admin dashboard |
| GET | `/admin/orders` | Manage orders |
| PATCH | `/admin/orders/{id}/status` | Update order status |
| GET | `/admin/reports` | Sales reports |
| GET | `/admin/reports/export/pdf` | Export PDF |
| GET | `/admin/reports/export/csv` | Export CSV |

---

## 🔄 Git Workflow

```bash
# Feature branch workflow
git checkout -b feature/your-feature-name
git add .
git commit -m "feat: add your feature description"
git push origin feature/your-feature-name
# Open a Pull Request to main
```

### Commit Convention
- `feat:` — new feature
- `fix:` — bug fix
- `style:` — UI/CSS changes
- `refactor:` — code restructuring
- `docs:` — documentation
- `test:` — test additions

---

## 📸 Screenshots

> *(Add screenshots of your pages here after running the project)*

| Page | Description |
|------|-------------|
| Age Verification | Cyberpunk 18+ gate |
| Homepage | Hero, categories, featured products |
| Shop | Filter sidebar + product grid |
| Product Detail | Specs, reviews, add to cart |
| Cart & Checkout | Cart summary, payment method selection |
| Order Tracking | Step tracker + VaultBot AI chat |
| Admin Dashboard | Stats, charts, orders, inventory |

---

## 📜 License

This project is created for academic purposes at **Pamantasan ng Lungsod ng Pasig**.

---

*Built with ⚡ by the Puffcart Team — CS 402, PLP*
