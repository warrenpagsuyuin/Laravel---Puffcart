@extends('layouts.admin')

@section('title', 'Inventory')
@section('page-title', 'Inventory')
@section('page-subtitle', 'Update stock levels and monitor low-stock products')

@push('styles')
    <style>
        .flavor-stock-editor {
            display: grid;
            gap: 8px;
            min-width: 360px;
        }

        .flavor-stock-row {
            align-items: center;
            display: grid;
            gap: 8px;
            grid-template-columns: minmax(120px, 1fr) 78px 98px;
        }

        .flavor-stock-row span {
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 700;
        }

        .flavor-stock-row input {
            min-height: 34px;
            padding: 7px 9px;
        }

        .inventory-actions {
            align-items: center;
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        @media (max-width: 860px) {
            .flavor-stock-row {
                grid-template-columns: 1fr;
            }

            .flavor-stock-editor {
                min-width: 0;
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
    @endphp

    <div class="admin-stack">
        <div class="metric-strip">
            <div class="metric-card">
                <div class="metric-label">Low Stock Items</div>
                <div class="metric-value">{{ number_format($lowStockCount) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Showing</div>
                <div class="metric-value">{{ number_format($products->count()) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Current View</div>
                <div class="metric-value">{{ $activeFilter }}</div>
            </div>
        </div>

        <section class="admin-toolbar">
            <div class="admin-toolbar-title">
                <strong>Stock Control</strong>
                <span>Review quantities, thresholds, and restock status</span>
            </div>

            <form method="GET" action="{{ route('admin.inventory.index') }}" class="toolbar-form">
                <input class="search-control" name="search" value="{{ request('search') }}" placeholder="Search product, SKU, category">
                <select class="filter-control" name="filter">
                    <option value="">All categories</option>
                    <option value="accessories" @selected(request('filter') === 'accessories')>Accessories</option>
                    <option value="coils-pods" @selected(request('filter') === 'coils-pods')>Coils & Pods</option>
                    <option value="devices" @selected(request('filter') === 'devices')>Devices</option>
                    <option value="e-liquids" @selected(request('filter') === 'e-liquids')>E-liquids</option>
                    <option value="low_stock" @selected(request('filter') === 'low_stock')>Low Stock</option>
                </select>
                <label class="checkbox-row">
                    <input type="checkbox" name="low_stock" value="1" @checked(request()->boolean('low_stock'))>
                    Low stock only
                </label>
                <button class="btn btn-secondary btn-compact" type="submit">Apply</button>
                @if(request()->filled('search') || request()->filled('filter') || request()->boolean('low_stock'))
                    <a class="btn btn-secondary btn-compact" href="{{ route('admin.inventory.index') }}">Clear</a>
                @endif
            </form>
        </section>

        <section class="panel">
            <div class="section-title">
                <h2>Stock Levels</h2>
            </div>

            <div class="table-wrap">
                <table class="admin-table compact-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Total Stock</th>
                            <th>Product Reorder</th>
                            <th>Status</th>
                            <th>Flavor Inventory</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <span>
                                            <strong>{{ $product->name }}</strong>
                                            <div class="muted">{{ $product->sku ?: 'No SKU' }}</div>
                                            {{-- flavor summary removed from list view to reduce clutter --}}
                                        </span>
                                    </div>
                                </td>
                                <td>{{ $product->category_name }}</td>
                                <td><strong>{{ $product->stock }}</strong></td>
                                <td>{{ $product->reorder_level ?? 5 }}</td>
                                <td>
                                    <span class="badge {{ $product->is_low_stock ? 'badge-red' : 'badge-green' }}">
                                        {{ $product->is_low_stock ? 'Restock' : 'OK' }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.inventory.update', $product) }}" class="inline-stock-form">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flavor-stock-editor">
                                            @foreach($product->flavors as $index => $flavor)
                                                <div class="flavor-stock-row">
                                                    <input type="hidden" name="flavors[{{ $index }}][id]" value="{{ $flavor->id }}">
                                                    <span>{{ $flavor->name }}</span>
                                                    <input type="number" name="flavors[{{ $index }}][stock]" min="0" value="{{ $flavor->stock }}" aria-label="Stock for {{ $product->name }} {{ $flavor->name }}">
                                                    <input type="number" name="flavors[{{ $index }}][reorder_level]" min="0" value="{{ $flavor->reorder_level }}" aria-label="Reorder level for {{ $product->name }} {{ $flavor->name }}">
                                                </div>
                                            @endforeach

                                            <div class="inventory-actions">
                                                <input type="number" name="reorder_level" min="0" value="{{ $product->reorder_level ?? 5 }}" aria-label="Product reorder level for {{ $product->name }}">
                                                <button class="btn btn-primary btn-compact" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="muted">No products found.</td>
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
