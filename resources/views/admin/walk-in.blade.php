@extends('layouts.admin')

@section('title', 'Walk-In Order')
@section('page-title', 'Walk-In Order')

@push('styles')
<style>
    .walkin-shell {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 20px;
        align-items: start;
    }

    .search-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0 14px;
        margin-bottom: 14px;
        transition: border-color var(--transition), box-shadow var(--transition);
    }

    .search-bar:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .search-bar svg {
        width: 16px;
        height: 16px;
        color: var(--text-muted);
        flex-shrink: 0;
    }

    .search-bar input {
        border: none;
        box-shadow: none;
        min-height: 42px;
        padding: 0;
        font-size: 14px;
        background: transparent;
    }

    .search-bar input:focus {
        border: none;
        box-shadow: none;
        outline: none;
    }

    .category-tabs {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .cat-tab {
        padding: 7px 14px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: var(--bg-white);
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background var(--transition), color var(--transition),
                    border-color var(--transition), transform 0.1s ease;
        user-select: none;
    }

    .cat-tab:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
    }

    .cat-tab.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }

    .product-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 10px;
        cursor: pointer;
        transition: box-shadow var(--transition), transform var(--transition),
                    border-color var(--transition);
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: relative;
        overflow: hidden;
    }

    .product-thumb {
        width: 100%;
        aspect-ratio: 1 / 0.72;
        border-radius: var(--radius);
        background: var(--bg-light);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .product-thumb-placeholder {
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
    }

    .product-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        transform: translateY(-2px);
        border-color: var(--primary);
    }

    .product-card:active {
        transform: translateY(0);
        box-shadow: var(--shadow-sm);
    }

    .product-card.out-of-stock {
        opacity: 0.5;
        pointer-events: none;
    }

    .product-card .prod-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.3;
    }

    .product-card .prod-price {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--primary);
    }

    .product-card .prod-stock {
        font-size: 11px;
        color: var(--text-muted);
    }

    .product-card .add-ripple {
        position: absolute;
        inset: 0;
        background: var(--primary-light);
        opacity: 0;
        transition: opacity 0.25s ease;
        pointer-events: none;
        border-radius: var(--radius-lg);
    }

    .product-card.flash .add-ripple { opacity: 1; }

    .order-panel {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 88px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .order-panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .order-panel-header h2 { font-size: 16px; }

    .order-items {
        padding: 12px 16px;
        min-height: 120px;
        max-height: 280px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .order-empty {
        color: var(--text-muted);
        font-size: 13px;
        text-align: center;
        padding: 28px 0;
    }

    .order-line {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 8px;
        padding: 8px 4px;
        border-radius: var(--radius);
        transition: background var(--transition);
        animation: lineIn 0.18s ease;
    }

    @keyframes lineIn {
        from { opacity: 0; transform: translateX(8px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .order-line:hover { background: var(--bg-light); }

    .order-line-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .order-line-price { font-size: 12px; color: var(--text-muted); }

    .order-line-variant {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 2px;
        line-height: 1.35;
    }

    .order-line-controls {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }

    .qty-btn {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        border: 1px solid var(--border);
        background: var(--bg-white);
        color: var(--text-primary);
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background var(--transition), border-color var(--transition), transform 0.1s;
        line-height: 1;
    }

    .qty-btn:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
        transform: scale(1.1);
    }

    .qty-btn:active { transform: scale(0.95); }

    .qty-btn.remove:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: var(--danger);
    }

    .qty-display {
        font-size: 13px;
        font-weight: 700;
        min-width: 22px;
        text-align: center;
        color: var(--text-primary);
    }

    .order-total-row {
        padding: 12px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-light);
    }

    .order-total-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); }

    .order-total-amount {
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        transition: transform 0.15s ease;
    }

    .order-total-amount.bump { transform: scale(1.12); }

    .checkout-form {
        padding: 16px 20px 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        border-top: 1px solid var(--border);
    }

    .checkout-form label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        display: block;
        margin-bottom: 4px;
    }

    .checkout-form input {
        font-size: 13.5px;
        min-height: 38px;
        padding: 8px 11px;
    }

    .payment-options { display: flex; gap: 6px; }

    .pay-opt {
        flex: 1;
        padding: 8px 6px;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        background: var(--bg-white);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        transition: all var(--transition);
        color: var(--text-secondary);
    }

    .pay-opt:hover { border-color: var(--primary); color: var(--primary); }
    .pay-opt.selected { background: var(--primary); border-color: var(--primary); color: #fff; }

    .mobile-field {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.25s ease, opacity 0.2s ease, margin 0.2s ease;
        margin-top: 0;
    }

    .mobile-field.visible { max-height: 80px; opacity: 1; margin-top: 2px; }

    .proceed-btn {
        width: 100%;
        min-height: 44px;
        font-size: 14px;
        font-weight: 700;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        transition: background var(--transition), transform 0.1s ease, box-shadow var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 4px;
    }

    .proceed-btn:hover:not(:disabled) {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0,102,255,0.25);
    }

    .proceed-btn:active:not(:disabled) { transform: translateY(0); box-shadow: none; }
    .proceed-btn:disabled { opacity: 0.45; cursor: not-allowed; }

    .no-results {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .variant-modal {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, 0.45);
    }

    .variant-modal.visible { display: flex; }

    .variant-dialog {
        width: min(460px, 100%);
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
        overflow: hidden;
    }

    .variant-head {
        padding: 16px;
        display: grid;
        grid-template-columns: 92px 1fr;
        gap: 14px;
        border-bottom: 1px solid var(--border);
    }

    .variant-head .product-thumb { aspect-ratio: 1 / 1; }

    .variant-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.25;
    }

    .variant-price {
        margin-top: 6px;
        font-size: 16px;
        font-weight: 800;
        color: var(--primary);
    }

    .variant-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .variant-field label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        margin-bottom: 5px;
    }

    .variant-field select {
        width: 100%;
        min-height: 42px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 8px 10px;
        background: var(--bg-white);
        color: var(--text-primary);
        font-size: 14px;
    }

    .variant-actions {
        padding: 14px 16px 16px;
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        border-top: 1px solid var(--border);
    }

    /* ── Tracking timeline ── */
    .tracking-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .tracking-row {
        display: grid;
        grid-template-columns: 180px 1fr auto;
        align-items: start;
        gap: 12px;
        padding: 13px 10px;
        border-top: 1px solid var(--border);
        font-size: 14px;
        transition: background var(--transition);
    }

    .tracking-row:hover { background: var(--bg-light); }
    .tracking-row:first-child { border-top: none; }

    @media (max-width: 900px) {
        .walkin-shell { grid-template-columns: 1fr; }
        .order-panel { position: static; }
        .tracking-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- ── POS section ── --}}
<div class="walkin-shell">

    {{-- LEFT: Product browser --}}
    <div class="product-browser">
        <div class="search-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="search-input" placeholder="Search products…" autocomplete="off">
        </div>

        <div class="category-tabs">
            <button class="cat-tab active" data-cat="all">All</button>
            <button class="cat-tab" data-cat="Devices">Devices</button>
            <button class="cat-tab" data-cat="E-Liquids">E-Liquids</button>
            <button class="cat-tab" data-cat="Coils & Pods">Coils &amp; Pods</button>
            <button class="cat-tab" data-cat="Accessories">Accessories</button>
        </div>

        <div class="product-grid" id="product-grid">
            @forelse($products as $product)
                <div class="product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-stock="{{ $product->available_stock }}"
                     data-cat="{{ $product->category_name }}">
                    <div class="add-ripple"></div>
                    <div class="product-thumb">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        @else
                            <span class="product-thumb-placeholder">PC</span>
                        @endif
                    </div>
                    <div class="prod-name">{{ $product->name }}</div>
                    <div class="prod-price">₱{{ number_format($product->price, 2) }}</div>
                    <div class="prod-stock">{{ $product->available_stock }} in stock</div>
                </div>
            @empty
                <div class="no-results">No products available.</div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: Order panel --}}
    <div class="order-panel">
        <div class="order-panel-header">
            <h2>Current Order</h2>
            <button class="btn btn-secondary" id="clear-btn"
                    style="min-height:32px;padding:5px 10px;font-size:12px;">Clear</button>
        </div>

        <div class="order-items" id="order-items">
            <div class="order-empty" id="order-empty">No items added yet.</div>
        </div>

        <div class="order-total-row">
            <span class="order-total-label">Total</span>
            <span class="order-total-amount" id="order-total">₱0.00</span>
        </div>

        <form class="checkout-form" id="checkout-form"
              method="POST" action="{{ route('admin.walk-in.checkout') }}">
            @csrf
            <div id="order-items-input"></div>

            <div>
                <label>Customer Name</label>
                <input type="text" name="customer_name"
                       placeholder="e.g. Juan dela Cruz"
                       value="{{ old('customer_name') }}" required>
            </div>

            <div>
                <label>Email Address</label>
                <input type="email" name="customer_email"
                       placeholder="e.g. juan@email.com"
                       value="{{ old('customer_email') }}" required>
            </div>

            <div>
                <label>Payment Method</label>
                <div class="payment-options">
                    <div class="pay-opt selected" data-pay="cash">Cash</div>
                    <div class="pay-opt" data-pay="gcash">GCash</div>
                    <div class="pay-opt" data-pay="maya">Maya</div>
                </div>
                <input type="hidden" name="payment_method"
                       id="payment-method-input" value="cash">
            </div>

            <div class="mobile-field" id="mobile-field">
                <label>Mobile Number</label>
                <input type="text" name="mobile_number"
                       id="mobile-number-input"
                       placeholder="e.g. 09171234567"
                       value="{{ old('mobile_number') }}">
            </div>

            <button type="submit" class="proceed-btn" id="proceed-btn" disabled>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Proceed Checkout
            </button>
        </form>
    </div>

</div>

{{-- ── Walk-In Order History ── --}}
<div class="variant-modal" id="variant-modal" aria-hidden="true">
    <div class="variant-dialog" role="dialog" aria-modal="true" aria-labelledby="variant-title">
        <div class="variant-head">
            <div class="product-thumb" id="variant-thumb"></div>
            <div>
                <div class="variant-title" id="variant-title"></div>
                <div class="variant-price" id="variant-price"></div>
                <div class="prod-stock" id="variant-stock"></div>
            </div>
        </div>
        <div class="variant-body">
            <div class="variant-field" id="variant-flavor-field">
                <label for="variant-flavor-select" id="variant-flavor-label">Flavor</label>
                <select id="variant-flavor-select"></select>
            </div>
            <div class="variant-field" id="variant-color-field">
                <label for="variant-color-select">Battery Color</label>
                <select id="variant-color-select"></select>
            </div>
        </div>
        <div class="variant-actions">
            <button type="button" class="btn btn-secondary" id="variant-cancel">Cancel</button>
            <button type="button" class="btn btn-primary" id="variant-add">Add to Order</button>
        </div>
    </div>
</div>

<section class="panel" style="margin-top: 24px;">
    <div class="section-title">
        <h2>Walk-In Order History</h2>
    </div>

    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Payment</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_email }}</td>
                        <td>{{ strtoupper($order->payment_method) }}</td>
                        <td>
                            @foreach($order->items as $item)
                                <div style="font-size:12px;color:var(--text-secondary);">
                                    {{ $item->product_name }}
                                    @if($item->selected_flavor || $item->selected_battery_color)
                                        <div style="font-size:11px;color:var(--text-muted);">
                                            @if($item->selected_flavor)
                                                Flavor: {{ $item->selected_flavor }}
                                            @endif
                                            @if($item->selected_battery_color)
                                                {{ $item->selected_flavor ? ' / ' : '' }}Battery Color: {{ $item->selected_battery_color }}
                                            @endif
                                        </div>
                                    @endif
                                    <span class="badge badge-gray" style="font-size:10px;">× {{ $item->quantity }}</span>
                                </div>
                            @endforeach
                        </td>
                        <td>₱{{ number_format($order->total, 2) }}</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'completed' => 'badge-green',
                                    'cancelled' => 'badge-red',
                                    default     => 'badge-gray',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at?->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted">No walk-in orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection

@push('scripts')
@php
    $walkInProducts = $products->mapWithKeys(function ($product) {
        return [(string) $product->id => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'stock' => $product->available_stock,
            'type' => $product->product_type,
            'image' => $product->image_url,
            'flavors' => $product->availableFlavorOptions->map(fn ($option) => [
                'id' => $option->id,
                'name' => $option->name,
                'stock' => $option->stock,
            ])->values(),
            'colors' => $product->availableColorOptions->map(fn ($option) => [
                'id' => $option->id,
                'name' => $option->name,
                'stock' => $option->stock,
            ])->values(),
        ]];
    });
@endphp
<script>
(function () {
    return;

    const cart  = {};
    const cards = document.querySelectorAll('.product-card');

    const orderItemsEl  = document.getElementById('order-items');
    const orderEmptyEl  = document.getElementById('order-empty');
    const orderTotalEl  = document.getElementById('order-total');
    const orderInputsEl = document.getElementById('order-items-input');
    const proceedBtn    = document.getElementById('proceed-btn');
    const clearBtn      = document.getElementById('clear-btn');
    const searchInput   = document.getElementById('search-input');
    const payOpts       = document.querySelectorAll('.pay-opt');
    const payInput      = document.getElementById('payment-method-input');
    const mobileField   = document.getElementById('mobile-field');
    const mobileInput   = document.getElementById('mobile-number-input');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const id    = card.dataset.id;
            const name  = card.dataset.name;
            const price = parseFloat(card.dataset.price);
            const stock = parseInt(card.dataset.stock);

            if (cart[id]) {
                if (cart[id].qty >= cart[id].stock) return;
                cart[id].qty++;
            } else {
                cart[id] = { name, price, stock, qty: 1 };
            }

            card.classList.add('flash');
            setTimeout(() => card.classList.remove('flash'), 250);

            renderCart();
        });
    });

    function renderCart() {
        const ids = Object.keys(cart).filter(id => cart[id].qty > 0);

        orderEmptyEl.style.display = ids.length ? 'none' : 'block';
        orderItemsEl.querySelectorAll('.order-line').forEach(el => el.remove());

        let total = 0;

        ids.forEach(id => {
            const item     = cart[id];
            const subtotal = item.price * item.qty;
            total += subtotal;

            const line = document.createElement('div');
            line.className = 'order-line';
            line.innerHTML = `
                <div>
                    <div class="order-line-name" title="${escHtml(item.name)}">${escHtml(item.name)}</div>
                    <div class="order-line-price">₱${fmt(item.price)} × ${item.qty} = ₱${fmt(subtotal)}</div>
                </div>
                <div class="order-line-controls">
                    <button class="qty-btn remove" data-id="${id}" title="Remove one">−</button>
                    <span class="qty-display">${item.qty}</span>
                    <button class="qty-btn add" data-id="${id}" title="Add one">+</button>
                </div>`;
            orderItemsEl.appendChild(line);
        });

        orderItemsEl.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                const id = btn.dataset.id;
                if (btn.classList.contains('add')) {
                    if (cart[id].qty < cart[id].stock) cart[id].qty++;
                } else {
                    cart[id].qty--;
                    if (cart[id].qty <= 0) delete cart[id];
                }
                renderCart();
            });
        });

        const newTotal = '₱' + fmt(total);
        if (orderTotalEl.textContent !== newTotal) {
            orderTotalEl.textContent = newTotal;
            orderTotalEl.classList.add('bump');
            setTimeout(() => orderTotalEl.classList.remove('bump'), 200);
        }

        orderInputsEl.innerHTML = '';
        ids.forEach((id, i) => {
            orderInputsEl.innerHTML += `
                <input type="hidden" name="items[${i}][id]"  value="${id}">
                <input type="hidden" name="items[${i}][qty]" value="${cart[id].qty}">`;
        });

        proceedBtn.disabled = ids.length === 0;
    }

    clearBtn.addEventListener('click', () => {
        Object.keys(cart).forEach(k => delete cart[k]);
        renderCart();
    });

    searchInput.addEventListener('input', filterProducts);

    document.querySelectorAll('.cat-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            filterProducts();
        });
    });

    function filterProducts() {
        const query     = searchInput.value.toLowerCase().trim();
        const activeCat = document.querySelector('.cat-tab.active')?.dataset.cat || 'all';
        let visible     = 0;

        cards.forEach(card => {
            const name        = card.dataset.name.toLowerCase();
            const cat         = card.dataset.cat || '';
            const matchSearch = !query || name.includes(query);
            const matchCat    = activeCat === 'all' || cat === activeCat;
            const show        = matchSearch && matchCat;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        let noResults = document.getElementById('no-results-msg');
        if (!visible) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.id = 'no-results-msg';
                noResults.className = 'no-results';
                noResults.textContent = 'No products found.';
                document.getElementById('product-grid').appendChild(noResults);
            }
        } else {
            noResults?.remove();
        }
    }

    payOpts.forEach(opt => {
        opt.addEventListener('click', () => {
            payOpts.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
            const method = opt.dataset.pay;
            payInput.value = method;
            const needsMobile = method === 'gcash' || method === 'maya';
            mobileField.classList.toggle('visible', needsMobile);
            mobileInput.required = needsMobile;
        });
    });

    function fmt(n)     { return n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    function escHtml(s) { return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
})();
</script>
<script>
(function () {
    const products = @json($walkInProducts);

    const cart = {};
    const orderItems = document.getElementById('order-items');
    const orderEmpty = document.getElementById('order-empty');
    const orderTotal = document.getElementById('order-total');
    const orderInputs = document.getElementById('order-items-input');
    const proceedBtn = document.getElementById('proceed-btn');
    const modal = document.getElementById('variant-modal');
    const modalThumb = document.getElementById('variant-thumb');
    const modalTitle = document.getElementById('variant-title');
    const modalPrice = document.getElementById('variant-price');
    const modalStock = document.getElementById('variant-stock');
    const flavorField = document.getElementById('variant-flavor-field');
    const flavorLabel = document.getElementById('variant-flavor-label');
    const flavorSelect = document.getElementById('variant-flavor-select');
    const colorField = document.getElementById('variant-color-field');
    const colorSelect = document.getElementById('variant-color-select');
    let activeProduct = null;

    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', event => {
            event.preventDefault();
            event.stopImmediatePropagation();

            const product = products[card.dataset.id];
            if (!product) return;

            card.classList.add('flash');
            setTimeout(() => card.classList.remove('flash'), 250);

            if (needsOptions(product)) {
                openModal(product);
                return;
            }

            addProduct(product);
        }, true);
    });

    document.getElementById('clear-btn').addEventListener('click', event => {
        event.preventDefault();
        event.stopImmediatePropagation();
        Object.keys(cart).forEach(key => delete cart[key]);
        render();
    }, true);

    document.getElementById('variant-cancel').addEventListener('click', closeModal);
    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });

    document.getElementById('variant-add').addEventListener('click', () => {
        if (!activeProduct) return;

        const product = activeProduct;
        const isBattery = product.type === 'battery';
        const isBundle = product.type === 'bundle';

        if (isBattery) {
            const color = product.colors.find(option => option.id === Number(flavorSelect.value));
            if (!color) return;
            addProduct(product, null, color);
            closeModal();
            return;
        }

        const flavor = product.flavors.find(option => option.id === Number(flavorSelect.value)) || null;
        const color = product.colors.find(option => option.id === Number(colorSelect.value)) || null;

        if (product.flavors.length && !flavor) return;
        if (isBundle && !color) return;

        addProduct(product, flavor, color);
        closeModal();
    });

    function needsOptions(product) {
        return product.type === 'battery' || product.type === 'bundle' || product.flavors.length > 0 || product.colors.length > 0;
    }

    function openModal(product) {
        const isBattery = product.type === 'battery';
        activeProduct = product;
        modalTitle.textContent = product.name;
        modalPrice.textContent = '₱' + money(product.price);
        modalStock.textContent = `${product.stock} in stock`;
        modalThumb.innerHTML = product.image
            ? `<img src="${attr(product.image)}" alt="${attr(product.name)}">`
            : '<span class="product-thumb-placeholder">PC</span>';

        flavorLabel.textContent = isBattery ? 'Battery Color' : 'Flavor';
        flavorField.style.display = (isBattery || product.flavors.length) ? '' : 'none';
        colorField.style.display = product.type === 'bundle' ? '' : 'none';
        fillSelect(flavorSelect, isBattery ? product.colors : product.flavors, isBattery ? 'Choose color' : 'Choose flavor');
        fillSelect(colorSelect, product.colors, 'Choose battery color');
        modal.classList.add('visible');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        activeProduct = null;
        modal.classList.remove('visible');
        modal.setAttribute('aria-hidden', 'true');
    }

    function fillSelect(select, options, label) {
        select.innerHTML = `<option value="">${label}</option>`;
        options.forEach(option => {
            select.innerHTML += `<option value="${option.id}">${html(option.name)} (${option.stock} left)</option>`;
        });
    }

    function addProduct(product, flavor = null, color = null) {
        const key = [product.id, flavor?.id || 0, color?.id || 0].join(':');
        const stock = Math.min(...[product.stock, flavor?.stock, color?.stock].filter(Number.isFinite));

        if (!cart[key]) {
            cart[key] = { id: product.id, name: product.name, price: product.price, type: product.type, flavor, color, stock, qty: 0 };
        }

        if (cart[key].qty < cart[key].stock) {
            cart[key].qty++;
            render();
        }
    }

    function render() {
        const keys = Object.keys(cart).filter(key => cart[key].qty > 0);
        orderEmpty.style.display = keys.length ? 'none' : 'block';
        orderItems.querySelectorAll('.order-line').forEach(line => line.remove());

        let total = 0;
        keys.forEach(key => {
            const item = cart[key];
            const subtotal = item.price * item.qty;
            const variants = [
                item.flavor ? `Flavor: ${item.flavor.name}` : '',
                item.color ? `Battery Color: ${item.color.name}` : '',
            ].filter(Boolean).join(' / ');
            total += subtotal;

            const line = document.createElement('div');
            line.className = 'order-line';
            line.innerHTML = `
                <div>
                    <div class="order-line-name" title="${html(item.name)}">${html(item.name)}</div>
                    ${variants ? `<div class="order-line-variant">${html(variants)}</div>` : ''}
                    <div class="order-line-price">₱${money(item.price)} × ${item.qty} = ₱${money(subtotal)}</div>
                </div>
                <div class="order-line-controls">
                    <button type="button" class="qty-btn remove" data-key="${key}" title="Remove one">−</button>
                    <span class="qty-display">${item.qty}</span>
                    <button type="button" class="qty-btn add" data-key="${key}" title="Add one">+</button>
                </div>`;
            orderItems.appendChild(line);
        });

        orderItems.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', () => {
                const item = cart[button.dataset.key];
                if (!item) return;

                if (button.classList.contains('add') && item.qty < item.stock) item.qty++;
                if (button.classList.contains('remove')) item.qty--;
                if (item.qty <= 0) delete cart[button.dataset.key];
                render();
            });
        });

        orderTotal.textContent = '₱' + money(total);
        orderInputs.innerHTML = '';
        keys.forEach((key, index) => {
            const item = cart[key];
            const primaryOption = item.flavor?.id || (item.type === 'battery' ? item.color?.id || '' : '');
            const batteryColor = item.type === 'battery' ? '' : item.color?.id || '';
            orderInputs.innerHTML += `
                <input type="hidden" name="items[${index}][id]" value="${item.id}">
                <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                <input type="hidden" name="items[${index}][product_flavor_id]" value="${primaryOption}">
                <input type="hidden" name="items[${index}][battery_color_id]" value="${batteryColor}">`;
        });
        proceedBtn.disabled = keys.length === 0;
    }

    function money(value) {
        return Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function html(value) {
        return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function attr(value) {
        return html(value).replace(/'/g, '&#039;');
    }
})();
</script>
@endpush
