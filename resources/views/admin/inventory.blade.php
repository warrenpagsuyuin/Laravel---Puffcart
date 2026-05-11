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
            max-width: 1520px;
            padding: 32px 36px 48px;
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
            min-height: 118px;
            padding: 20px;
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
            font-size: 28px;
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
            grid-template-columns: minmax(240px, 1fr) 190px auto auto;
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
            min-width: 1080px;
        }

        .inventory-table th {
            background: #f8fafc;
            border-bottom: 1px solid #d9e2ec;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0;
            padding: 14px 16px;
            text-transform: uppercase;
        }

        .inventory-table td {
            border-top: 0;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 14px;
            padding: 18px 16px;
            vertical-align: top;
        }

        .inventory-table tr:last-child td {
            border-bottom: 0;
        }

        .inventory-table tr:hover td {
            background: #fbfdff;
        }

        .product-cell {
            min-width: 280px;
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
            min-width: 440px;
        }

        .variant-head,
        .variant-row {
            align-items: center;
            display: grid;
            gap: 10px;
            grid-template-columns: minmax(140px, 1fr) 86px 96px;
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
            padding: 8px;
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
            align-items: end;
            display: grid;
            gap: 10px;
            grid-template-columns: minmax(140px, 1fr) 96px auto;
            padding-top: 2px;
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

            <form method="GET" action="{{ route('admin.inventory.index') }}" class="toolbar-form">
                <div class="field">
                    <label for="inventory-search">Search Inventory</label>
                    <input id="inventory-search" name="search" value="{{ request('search') }}" placeholder="Product, SKU, flavor, category">
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
                <label class="checkbox-row">
                    <input type="checkbox" name="low_stock" value="1" @checked(request()->boolean('low_stock'))>
                    Low stock only
                </label>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Apply</button>
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

            <div class="inventory-table-wrap">
                <table class="admin-table inventory-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Total Stock</th>
                            <th>Reorder</th>
                            <th>Status</th>
                            <th>Variant Inventory</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <span class="product-name">{{ $product->name }}</span>
                                        <span class="sku-line">{{ $product->sku ?: 'No SKU assigned' }}</span>
                                    </div>
                                </td>
                                <td>{{ $product->category_name }}</td>
                                <td><span class="stock-number">{{ number_format($product->stock) }}</span></td>
                                <td><span class="reorder-number">{{ number_format($product->reorder_level ?? 5) }}</span></td>
                                <td>
                                    <span class="badge {{ $product->is_low_stock ? 'badge-red' : 'badge-green' }}">
                                        {{ $product->is_low_stock ? 'Restock' : 'Healthy' }}
                                    </span>
                                </td>
                                <td>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-row">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $products->links() }}
            </div>
        </section>
    </div>
@endsection
