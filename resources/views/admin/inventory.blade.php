@extends('layouts.admin')

@section('title', 'Inventory')
@section('page-title', 'Inventory')
@section('page-subtitle', 'Update stock levels and monitor low-stock products')

@push('styles')
    <style>
        body {
            background: #f4f7fb;
        }

        .sidebar {
            background: #ffffff;
            border-right-color: #d9e2ec;
        }

        .brand {
            padding: 26px 24px;
        }

        .brand-name {
            color: #0b66ff;
            letter-spacing: 0;
        }

        .brand-subtitle,
        .page-subtitle,
        .muted {
            color: #64748b;
        }

        .sidebar-nav {
            gap: 8px;
            padding: 18px 20px;
        }

        .sidebar-link {
            border-radius: 8px;
            color: #475569;
            min-height: 44px;
            padding: 12px 14px;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: #eff6ff;
            color: #0b66ff;
        }

        .topbar {
            background: #ffffff;
            border-bottom-color: #d9e2ec;
            min-height: 86px;
            padding: 20px 36px;
        }

        .page-title {
            color: #0f172a;
            font-size: 28px;
            letter-spacing: 0;
        }

        .content {
            max-width: 1560px;
            padding: 28px 34px 44px;
        }

        .inventory-page {
            display: grid;
            gap: 20px;
        }

        .inventory-summary {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .inventory-metric {
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
            min-height: 104px;
            padding: 18px;
            position: relative;
        }

        .inventory-metric::before {
            background: #0b66ff;
            content: "";
            height: 3px;
            left: 20px;
            position: absolute;
            right: 20px;
            top: 0;
        }

        .inventory-metric.warning::before {
            background: #b45309;
        }

        .inventory-metric.success::before {
            background: #047857;
        }

        .metric-label {
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .metric-value {
            color: #0f172a;
            font-family: 'Poppins', sans-serif;
            font-size: 25px;
            font-weight: 700;
            line-height: 1.1;
            margin-top: 14px;
            overflow-wrap: anywhere;
        }

        .metric-note {
            color: #64748b;
            font-size: 13px;
            margin-top: 8px;
        }

        .inventory-toolbar,
        .inventory-panel {
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }

        .inventory-toolbar {
            align-items: center;
            display: grid;
            gap: 20px;
            grid-template-columns: minmax(220px, 0.8fr) minmax(0, 1.7fr);
            padding: 20px;
        }

        .toolbar-copy {
            display: grid;
            gap: 4px;
        }

        .toolbar-copy strong,
        .panel-heading h2 {
            color: #0f172a;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 700;
        }

        .toolbar-copy span,
        .panel-heading span {
            color: #64748b;
            font-size: 13px;
        }

        .toolbar-form {
            align-items: end;
            display: grid;
            gap: 12px;
            grid-template-columns: minmax(240px, 1fr) 190px 112px auto auto;
        }

        .field {
            display: grid;
            gap: 7px;
        }

        .field label,
        .checkbox-row {
            color: #334155;
            font-size: 13px;
            font-weight: 800;
        }

        .field input,
        .field select {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            min-height: 42px;
            padding: 9px 12px;
        }

        .field input.is-searching {
            background-image: linear-gradient(90deg, transparent, rgba(11, 102, 255, 0.08), transparent);
            background-size: 220% 100%;
            animation: searchPulse 0.9s ease infinite;
        }

        @keyframes searchPulse {
            0% {
                background-position: 160% 0;
            }

            100% {
                background-position: -60% 0;
            }
        }

        .field input:focus,
        .field select:focus,
        .variant-row input:focus,
        .product-threshold input:focus {
            border-color: #0b66ff;
            box-shadow: 0 0 0 3px #dbeafe;
            outline: none;
        }

        .checkbox-row {
            align-items: center;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            display: inline-flex;
            gap: 9px;
            min-height: 42px;
            padding: 0 13px;
            white-space: nowrap;
        }

        .checkbox-row input {
            accent-color: #0b66ff;
            min-height: auto;
            width: auto;
        }

        .btn {
            border-radius: 8px;
            min-height: 42px;
        }

        .btn-primary {
            background: #0b66ff;
            box-shadow: 0 8px 18px rgba(11, 102, 255, 0.15);
        }

        .btn-primary:hover {
            background: #0954d6;
        }

        .btn-secondary {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        .btn-secondary:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #0b66ff;
        }

        .inventory-panel {
            padding: 0;
            overflow: hidden;
        }

        .panel-heading {
            align-items: center;
            border-bottom: 1px solid #d9e2ec;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 22px 26px;
        }

        .panel-heading > div {
            display: grid;
            gap: 4px;
        }

        .table-meta {
            background: #f8fafc;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 10px;
            white-space: nowrap;
        }

        .inventory-table-wrap {
            overflow-x: auto;
        }

        .inventory-table {
            min-width: 1180px;
        }

        .inventory-table th {
            background: #f8fafc;
            border-bottom: 1px solid #d9e2ec;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0;
            padding: 13px 16px;
            text-transform: uppercase;
        }

        .inventory-table td {
            border-top: 0;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 14px;
            padding: 16px;
            vertical-align: middle;
        }

        .inventory-table tr:last-child td {
            border-bottom: 0;
        }

        .inventory-table tr:hover td {
            background: #fbfdff;
        }

        .inventory-list {
            display: grid;
            gap: 0;
        }

        .inventory-card {
            display: grid;
            gap: 28px;
            grid-template-columns: minmax(380px, 0.9fr) minmax(520px, 1.1fr);
            padding: 26px;
            border-bottom: 1px solid #e2e8f0;
        }

        .inventory-card:last-child {
            border-bottom: 0;
        }

        .inventory-card:hover {
            background: #fbfdff;
        }

        .product-overview {
            display: grid;
            gap: 18px;
            align-content: start;
        }

        .product-stat-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .product-stat {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
        }

        .product-stat span {
            color: #64748b;
            display: block;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .product-stat strong {
            color: #0f172a;
            font-size: 18px;
        }

        .product-admin-actions {
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            justify-content: space-between;
            padding: 12px;
        }

        .product-admin-actions span {
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.4;
        }

        .btn-danger-soft {
            background: #ffffff;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-weight: 800;
            padding: 0 14px;
            white-space: nowrap;
        }

        .btn-danger-soft:hover {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .variant-panel {
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            padding: 16px;
        }

        .variant-panel-title {
            align-items: center;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .variant-panel-title strong {
            color: #0f172a;
            font-size: 14px;
        }

        .variant-panel-title span {
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
        }

        .product-cell {
            align-items: center;
            display: grid;
            gap: 13px;
            grid-template-columns: 62px minmax(0, 1fr);
            min-width: 280px;
        }

        .product-thumb {
            align-items: center;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            color: #0b66ff;
            display: flex;
            font-size: 15px;
            font-weight: 900;
            height: 62px;
            justify-content: center;
            overflow: hidden;
            width: 62px;
        }

        .product-thumb img {
            display: block;
            height: 100%;
            object-fit: cover;
            width: 100%;
        }

        .product-copy {
            min-width: 0;
        }

        .product-name {
            color: #0f172a;
            display: block;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.35;
            margin-bottom: 4px;
        }

        .sku-line {
            color: #64748b;
            font-size: 13px;
        }

        .brand-line {
            color: #475569;
            font-size: 12px;
            font-weight: 800;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .stock-number {
            color: #0f172a;
            font-size: 18px;
            font-weight: 800;
        }

        .reorder-number {
            color: #334155;
            font-weight: 700;
        }

        .badge {
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            padding: 6px 9px;
        }

        .badge-green {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
        }

        .badge-red {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .variant-editor {
            display: grid;
            gap: 10px;
            min-width: 520px;
        }

        .variant-head,
        .variant-row {
            align-items: center;
            display: grid;
            gap: 10px;
            grid-template-columns: minmax(180px, 1fr) 90px 100px;
        }

        .variant-head {
            color: #64748b;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .variant-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px;
        }

        .variant-row.is-low {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .variant-name {
            color: #0f172a;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .variant-row input,
        .product-threshold input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            min-height: 38px;
            padding: 8px 10px;
            width: 100%;
        }

        .product-threshold {
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: grid;
            gap: 10px;
            grid-template-columns: minmax(140px, 1fr) 96px auto;
            padding: 9px;
        }

        .product-threshold label {
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
        }

        .empty-row {
            color: #64748b;
            padding: 28px;
            text-align: center;
        }

        .pagination {
            border-top: 1px solid #d9e2ec;
            margin-top: 0;
            padding: 18px 24px;
        }

        @media (max-width: 1180px) {
            .inventory-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .inventory-toolbar,
            .toolbar-form {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 860px) {
            .content {
                padding: 20px 16px 36px;
            }

            .inventory-summary {
                grid-template-columns: 1fr;
            }

            .panel-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .variant-editor {
                min-width: 0;
            }

            .variant-head {
                display: none;
            }

            .variant-row,
            .product-threshold {
                grid-template-columns: 1fr;
            }

            .inventory-card {
                grid-template-columns: 1fr;
            }

            .product-stat-grid {
                grid-template-columns: 1fr;
            }

            .product-admin-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .product-admin-actions .btn {
                width: 100%;
            }

            .product-cell {
                grid-template-columns: 52px minmax(0, 1fr);
            }

            .product-thumb {
                height: 52px;
                width: 52px;
            }

            .product-threshold .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $filterLabels = [
            'accessories' => 'Accessories',
            'coils-pods' => 'Coils & Pods',
            'devices' => 'Devices',
            'e-liquids' => 'E-liquids',
            'low_stock' => 'Low Stock',
        ];

        $activeFilter = request()->filled('filter')
            ? ($filterLabels[request('filter')] ?? request('filter'))
            : (request()->boolean('low_stock') ? 'Low Stock' : 'All Products');

        $visibleUnits = $products->getCollection()->sum('stock');
    @endphp

    <div class="inventory-page">
        <div class="inventory-summary">
            <div class="inventory-metric warning">
                <div class="metric-label">Low Stock Items</div>
                <div class="metric-value">{{ number_format($lowStockCount) }}</div>
                <div class="metric-note">Products at or below reorder level</div>
            </div>
            <div class="inventory-metric">
                <div class="metric-label">Products Found</div>
                <div class="metric-value">{{ number_format($products->total()) }}</div>
                <div class="metric-note">Matching the current filters</div>
            </div>
            <div class="inventory-metric success">
                <div class="metric-label">Units on Page</div>
                <div class="metric-value">{{ number_format($visibleUnits) }}</div>
                <div class="metric-note">Combined stock in this view</div>
            </div>
            <div class="inventory-metric">
                <div class="metric-label">Current View</div>
                <div class="metric-value">{{ $activeFilter }}</div>
                <div class="metric-note">Inventory segment being reviewed</div>
            </div>
        </div>

        <section class="inventory-toolbar">
            <div class="toolbar-copy">
                <strong>Stock Control</strong>
                <span>Filter the catalog and update stock thresholds from one workspace.</span>
            </div>

            <form method="GET" action="{{ route('admin.inventory.index') }}" class="toolbar-form" id="inventoryFilterForm">
                <div class="field">
                    <label for="inventory-search">Search Inventory</label>
                    <input id="inventory-search" name="search" value="{{ request('search') }}" placeholder="Product, SKU, flavor, category" autocomplete="off">
                </div>
                <div class="field">
                    <label for="inventory-filter">Category</label>
                    <select id="inventory-filter" name="filter">
                        <option value="">All categories</option>
                        <option value="accessories" @selected(request('filter') === 'accessories')>Accessories</option>
                        <option value="coils-pods" @selected(request('filter') === 'coils-pods')>Coils & Pods</option>
                        <option value="devices" @selected(request('filter') === 'devices')>Devices</option>
                        <option value="e-liquids" @selected(request('filter') === 'e-liquids')>E-liquids</option>
                        <option value="low_stock" @selected(request('filter') === 'low_stock')>Low Stock</option>
                    </select>
                </div>
                <div class="field">
                    <label for="inventory-per-page">Show</label>
                    <select id="inventory-per-page" name="per_page">
                        <option value="5" @selected((int) request('per_page', 5) === 5)>5 rows</option>
                        <option value="10" @selected((int) request('per_page', 5) === 10)>10 rows</option>
                    </select>
                </div>
                <label class="checkbox-row">
                    <input type="checkbox" name="low_stock" value="1" @checked(request()->boolean('low_stock'))>
                    Low stock only
                </label>
                <div class="actions">
                    @if(request()->filled('search') || request()->filled('filter') || request()->boolean('low_stock'))
                        <a class="btn btn-secondary" href="{{ route('admin.inventory.index') }}">Clear</a>
                    @endif
                </div>
            </form>
        </section>

        <section class="inventory-panel">
            <div class="panel-heading">
                <div>
                    <h2>Stock Levels</h2>
                    <span>Maintain product-level reorder points and per-flavor inventory.</span>
                </div>
                <div class="table-meta">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ number_format($products->total()) }}
                </div>
            </div>

            <div class="inventory-list">
                @forelse($products as $product)
                    <article class="inventory-card">
                        <div class="product-overview">
                            <div class="product-cell">
                                <div class="product-thumb">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    @else
                                        {{ strtoupper(substr($product->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="product-copy">
                                    <span class="product-name">{{ $product->name }}</span>
                                    <span class="sku-line">{{ $product->sku ?: 'No SKU assigned' }}</span>
                                    @if($product->brand)
                                        <div class="brand-line">{{ $product->brand }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="product-stat-grid">
                                <div class="product-stat">
                                    <span>Category</span>
                                    <strong>{{ $product->category_name }}</strong>
                                </div>
                                <div class="product-stat">
                                    <span>Total Stock</span>
                                    <strong>{{ number_format($product->stock) }}</strong>
                                </div>
                                <div class="product-stat">
                                    <span>Status</span>
                                    <span class="badge {{ $product->is_low_stock ? 'badge-red' : 'badge-green' }}">
                                        {{ $product->is_low_stock ? 'Restock' : 'Healthy' }}
                                    </span>
                                </div>
                            </div>

                            <div class="product-admin-actions">
                                <span>Remove this product from inventory and the active catalog.</span>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product from inventory? Products with order history will be removed from the active catalog instead.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger-soft" type="submit">Delete Product</button>
                                </form>
                            </div>
                        </div>

                        <div class="variant-panel">
                            <div class="variant-panel-title">
                                <strong>Variant Inventory</strong>
                                <span>Product reorder: {{ number_format($product->reorder_level ?? 5) }}</span>
                            </div>

                            <form method="POST" action="{{ route('admin.inventory.update', $product) }}" class="inline-stock-form">
                                @csrf
                                @method('PATCH')
                                <div class="variant-editor">
                                    @if($product->flavors->isNotEmpty())
                                        <div class="variant-head">
                                            <span>Variant</span>
                                            <span>Stock</span>
                                            <span>Reorder</span>
                                        </div>
                                    @endif

                                    @forelse($product->flavors as $index => $flavor)
                                        <div class="variant-row {{ $flavor->stock <= $flavor->reorder_level ? 'is-low' : '' }}">
                                            <input type="hidden" name="flavors[{{ $index }}][id]" value="{{ $flavor->id }}">
                                            <span class="variant-name">{{ $flavor->name }}</span>
                                            <input type="number" name="flavors[{{ $index }}][stock]" min="0" value="{{ $flavor->stock }}" aria-label="Stock for {{ $product->name }} {{ $flavor->name }}">
                                            <input type="number" name="flavors[{{ $index }}][reorder_level]" min="0" value="{{ $flavor->reorder_level }}" aria-label="Reorder level for {{ $product->name }} {{ $flavor->name }}">
                                        </div>
                                    @empty
                                        <div class="muted">No variants configured for this product.</div>
                                    @endforelse

                                    @if($product->flavors->isNotEmpty())
                                        <div class="product-threshold">
                                            <label for="product-reorder-{{ $product->id }}">Product reorder level</label>
                                            <input id="product-reorder-{{ $product->id }}" type="number" name="reorder_level" min="0" value="{{ $product->reorder_level ?? 5 }}" aria-label="Product reorder level for {{ $product->name }}">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-row">No products found.</div>
                @endforelse
            </div>

            <div class="pagination">
                {{ $products->links() }}
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('inventoryFilterForm');
            const search = document.getElementById('inventory-search');

            if (!form) {
                return;
            }

            let timer = null;

            function submitFilters(delay = 0) {
                window.clearTimeout(timer);

                if (search && delay > 0) {
                    search.classList.add('is-searching');
                }

                timer = window.setTimeout(() => {
                    form.requestSubmit();
                }, delay);
            }

            search?.addEventListener('input', () => submitFilters(450));

            form.querySelectorAll('select, input[type="checkbox"]').forEach((control) => {
                control.addEventListener('change', () => submitFilters());
            });
        });
    </script>
@endpush
