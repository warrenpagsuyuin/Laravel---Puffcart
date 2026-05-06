@extends('layouts.admin')

@section('title', 'Inventory')
@section('page-title', 'Inventory')
@section('page-subtitle', 'Update stock levels and monitor low-stock products')

@section('content')
    <div class="grid grid-3">
        <div class="stat-card">
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-value">{{ number_format($lowStockCount) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Showing</div>
            <div class="stat-value">{{ number_format($products->count()) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Filter</div>
            <div class="stat-value" style="font-size:22px;">{{ request()->boolean('low_stock') ? 'Low Stock' : 'All Products' }}</div>
        </div>
    </div>

    <section class="panel" style="margin-top:16px;">
        <div class="section-title">
            <h2>Stock Levels</h2>
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="actions">
                <input name="search" value="{{ request('search') }}" placeholder="Search product, SKU, category" style="width:260px;">
                <label class="checkbox-row" style="font-weight:700;">
                    <input type="checkbox" name="low_stock" value="1" @checked(request()->boolean('low_stock'))>
                    Low stock only
                </label>
                <button class="btn btn-primary" type="submit">Filter</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Reorder Level</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <div class="muted">{{ $product->sku ?: 'No SKU' }}</div>
                            </td>
                            <td>{{ $product->category_name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->reorder_level ?? 5 }}</td>
                            <td>
                                <span class="badge {{ $product->is_low_stock ? 'badge-red' : 'badge-green' }}">
                                    {{ $product->is_low_stock ? 'Restock' : 'OK' }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.inventory.update', $product) }}" class="actions">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="stock" min="0" value="{{ $product->stock }}" style="width:96px;">
                                    <input type="number" name="reorder_level" min="0" value="{{ $product->reorder_level ?? 5 }}" style="width:96px;">
                                    <button class="btn btn-secondary" type="submit">Save</button>
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
@endsection
