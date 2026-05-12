@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<style>
    body {
        background: #f6f8fb;
    }

    .store-nav {
        align-items: center;
        background: #ffffff;
        border-bottom: 1px solid #e3e8f0;
        display: flex;
        justify-content: space-between;
        min-height: 72px;
        padding: 0 48px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .logo {
        color: #0b66ff;
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 0;
    }

    .nav-links {
        align-items: center;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav-links a {
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 8px;
    }

    .nav-links a:hover {
        background: #eff6ff;
        color: #0b66ff;
    }

    .cart-shell {
        margin: 0 auto;
        max-width: 1180px;
        padding: 36px 24px 64px;
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 22px;
    }

    .cart-header h1 {
        color: #111827;
        font-size: 34px;
        line-height: 1.15;
        margin: 0 0 8px;
    }

    .muted {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.55;
    }

    .cart-layout {
        align-items: start;
        display: grid;
        gap: 24px;
        grid-template-columns: minmax(0, 1fr) 390px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #dfe5ef;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .panel-header {
        border-bottom: 1px solid #e8edf5;
        padding: 22px 26px 18px;
    }

    .panel-header h2 {
        color: #111827;
        font-size: 22px;
        margin: 0 0 6px;
    }

    .cart-list {
        display: grid;
    }

    .cart-row {
        align-items: center;
        border-bottom: 1px solid #e8edf5;
        display: grid;
        gap: 18px;
        grid-template-columns: 44px minmax(0, 1fr) 220px 132px;
        padding: 22px 26px;
    }

    .cart-row:last-child {
        border-bottom: 0;
    }

    .selection-control {
        align-items: center;
        display: inline-flex;
        gap: 10px;
    }

    .selection-control input,
    .item-check {
        accent-color: #0b66ff;
        height: 18px;
        width: 18px;
    }

    .select-all-row {
        align-items: center;
        border-bottom: 1px solid #e8edf5;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 26px;
    }

    .selected-count {
        color: #4b5563;
        font-size: 13px;
        font-weight: 800;
    }

    .item-name {
        color: #111827;
        display: inline-block;
        font-size: 16px;
        font-weight: 900;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .item-detail {
        display: grid;
        gap: 3px;
    }

    .quantity-form {
        align-items: center;
        display: grid;
        gap: 10px;
        grid-template-columns: 84px 1fr;
    }

    .quantity-form input {
        background: #ffffff;
        border: 1px solid #cfd7e3;
        border-radius: 8px;
        color: #111827;
        font-size: 14px;
        min-height: 46px;
        padding: 10px 12px;
        width: 100%;
    }

    .quantity-form input:focus {
        border-color: #0b66ff;
        box-shadow: 0 0 0 3px rgba(11, 102, 255, 0.12);
        outline: none;
    }

    .line-total {
        display: grid;
        gap: 10px;
        justify-items: end;
    }

    .line-price {
        color: #111827;
        font-size: 16px;
        font-weight: 900;
        white-space: nowrap;
    }

    .summary {
        display: grid;
        position: sticky;
        top: 96px;
    }

    .summary-body {
        display: grid;
        gap: 14px;
        padding: 22px 26px 26px;
    }

    .summary-row {
        align-items: center;
        display: flex;
        justify-content: space-between;
        gap: 18px;
    }

    .summary-row strong {
        color: #111827;
    }

    .total-row {
        border-top: 1px solid #d9dee8;
        font-size: 18px;
        font-weight: 900;
        margin-top: 4px;
        padding-top: 18px;
    }

    .checkout-note {
        background: #f8fafc;
        border: 1px solid #e3e8f0;
        border-radius: 8px;
        padding: 13px 14px;
    }

    .btn-primary,
    .btn-secondary,
    .btn-danger {
        align-items: center;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        font-size: 14px;
        font-weight: 900;
        justify-content: center;
        min-height: 44px;
        padding: 10px 14px;
        text-align: center;
    }

    .btn-primary {
        background: #0b66ff;
        border: 1px solid #0b66ff;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #0954d6;
        color: #ffffff;
    }

    .btn-secondary {
        background: #ffffff;
        border: 1px solid #cfd7e3;
        color: #111827;
    }

    .btn-secondary:hover {
        border-color: #0b66ff;
        color: #0b66ff;
    }

    .btn-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        width: 100%;
    }

    .notice {
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 18px;
        padding: 13px 15px;
    }

    .notice-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .notice-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .empty {
        align-items: center;
        display: grid;
        gap: 12px;
        justify-items: center;
        padding: 54px 28px;
        text-align: center;
    }

    .empty h2 {
        color: #111827;
        margin: 0;
    }

    @media (max-width: 920px) {
        .store-nav,
        .cart-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .store-nav {
            gap: 14px;
            padding: 16px 22px;
            position: static;
        }

        .cart-layout,
        .cart-row {
            grid-template-columns: 1fr;
        }

        .summary {
            position: static;
        }

        .quantity-form {
            grid-template-columns: 96px minmax(0, 160px);
        }

        .line-total {
            justify-items: stretch;
        }
    }
</style>

<nav class="store-nav">
    <a class="logo" href="{{ route('home') }}">Puffcart</a>
    <div class="nav-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('tracking') }}">Tracking</a>
        <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
    </div>
</nav>

<main class="cart-shell">
    <div class="cart-header">
        <div>
            <h1>Shopping Cart</h1>
            <p class="muted">Review item selections, update quantities, and proceed to secure checkout.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="notice notice-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="notice notice-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="notice notice-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($items->isEmpty())
        <section class="panel empty">
            <h2>Your cart is empty</h2>
            <p class="muted">Add products from the shop to start an order.</p>
            <a class="btn-primary" href="{{ route('shop') }}">Shop Products</a>
        </section>
    @else
        <div class="cart-layout">
            <section class="panel">
                <div class="panel-header">
                    <h2>Cart Items</h2>
                    <p class="muted">Select only the item(s) you want to checkout now.</p>
                </div>

                <div class="cart-list">
                    <div class="select-all-row">
                        <label class="selection-control" for="select_all_items">
                            <input id="select_all_items" type="checkbox" checked>
                            <span>Select all items</span>
                        </label>
                        <span class="selected-count" id="selectedCount">{{ $items->count() }} selected</span>
                    </div>

                    @foreach($items as $item)
                        <div
                            class="cart-row"
                            data-cart-row
                            data-subtotal="{{ (float) $item->subtotal }}"
                        >
                            <div>
                                <input
                                    class="item-check"
                                    type="checkbox"
                                    value="{{ $item->id }}"
                                    aria-label="Select {{ $item->product->name }} for checkout"
                                    checked
                                >
                            </div>

                            <div>
                                <a class="item-name" href="{{ route('product.show', $item->product) }}">{{ $item->product->name }}</a>
                                <div class="item-detail">
                                    <div class="muted">{{ $item->product->category_name }} / PHP {{ number_format($item->product->price, 2) }}</div>
                                    <div class="muted">Type: {{ $item->product_type_label }}</div>
                                    @if($item->flavor_label)
                                        <div class="muted">Flavor: {{ $item->flavor_label }}</div>
                                    @endif
                                    @if($item->battery_color_label)
                                        <div class="muted">Battery Color: {{ $item->battery_color_label }}</div>
                                    @endif
                                    @if($item->bundle_description)
                                        <div class="muted">{{ $item->bundle_description }}</div>
                                    @endif
                                    <div class="muted">{{ $item->available_stock }} available for this selection</div>
                                </div>
                            </div>

                            <form class="quantity-form" method="POST" action="{{ route('cart.update', $item) }}">
                                @csrf
                                @method('PATCH')
                                <input
                                    type="number"
                                    name="quantity"
                                    min="1"
                                    max="{{ max(1, $item->available_stock) }}"
                                    value="{{ $item->quantity }}"
                                    aria-label="Quantity for {{ $item->product->name }}"
                                >
                                <button class="btn-secondary" type="submit">Update</button>
                            </form>

                            <div class="line-total">
                                <div class="line-price">PHP {{ number_format($item->subtotal, 2) }}</div>
                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="panel summary">
                <div class="panel-header">
                    <h2>Order Summary</h2>
                    <p class="muted">Delivery fee is finalized at checkout.</p>
                </div>

                <div class="summary-body">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong id="selectedSubtotal">PHP {{ number_format($subtotal, 2) }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Delivery</span>
                        <strong id="selectedDelivery">{{ $deliveryFee > 0 ? 'PHP ' . number_format($deliveryFee, 2) : 'Free' }}</strong>
                    </div>
                    <div class="summary-row total-row">
                        <span>Total</span>
                        <strong id="selectedTotal">PHP {{ number_format($total, 2) }}</strong>
                    </div>
                    <div class="checkout-note">
                        <div class="muted" id="checkoutNote">Only selected items will move to checkout. Unselected items stay in your cart.</div>
                    </div>
                    <form id="selectedCheckoutForm" method="GET" action="{{ route('checkout') }}">
                        <div id="selectedCheckoutInputs"></div>
                        <button class="btn-primary" id="checkoutSelectedButton" type="submit" style="width:100%;">Checkout Selected</button>
                    </form>
                    <a class="btn-secondary" href="{{ route('shop') }}">Continue Shopping</a>
                </div>
            </aside>
        </div>
    @endif
</main>

@if(!$items->isEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checks = Array.from(document.querySelectorAll('.item-check'));
            const selectAll = document.getElementById('select_all_items');
            const selectedCount = document.getElementById('selectedCount');
            const selectedSubtotal = document.getElementById('selectedSubtotal');
            const selectedDelivery = document.getElementById('selectedDelivery');
            const selectedTotal = document.getElementById('selectedTotal');
            const checkoutNote = document.getElementById('checkoutNote');
            const checkoutButton = document.getElementById('checkoutSelectedButton');
            const checkoutInputs = document.getElementById('selectedCheckoutInputs');

            function money(amount) {
                return 'PHP ' + amount.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function refreshSelection() {
                const selected = checks.filter((check) => check.checked);
                const subtotal = selected.reduce((sum, check) => {
                    const row = check.closest('[data-cart-row]');
                    return sum + Number(row.dataset.subtotal || 0);
                }, 0);
                const delivery = subtotal > 0 && subtotal < 500 ? 50 : 0;
                const total = subtotal + delivery;

                selectedSubtotal.textContent = money(subtotal);
                selectedDelivery.textContent = delivery > 0 ? money(delivery) : 'Free';
                selectedTotal.textContent = money(total);
                selectedCount.textContent = selected.length + ' selected';
                checkoutButton.disabled = selected.length === 0;
                checkoutButton.style.opacity = selected.length === 0 ? '0.55' : '1';
                checkoutNote.textContent = selected.length === 0
                    ? 'Select at least one item before checkout.'
                    : 'Only selected items will move to checkout. Unselected items stay in your cart.';

                selectAll.checked = selected.length === checks.length;
                selectAll.indeterminate = selected.length > 0 && selected.length < checks.length;

                checkoutInputs.innerHTML = '';
                selected.forEach((check) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'cart_item_ids[]';
                    input.value = check.value;
                    checkoutInputs.appendChild(input);
                });
            }

            checks.forEach((check) => {
                check.addEventListener('change', refreshSelection);
            });

            selectAll.addEventListener('change', function () {
                checks.forEach((check) => {
                    check.checked = selectAll.checked;
                });
                refreshSelection();
            });

            refreshSelection();
        });
    </script>
@endif
@endsection
