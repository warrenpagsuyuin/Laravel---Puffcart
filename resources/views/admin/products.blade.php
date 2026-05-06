@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage catalog items, prices, stock, and product content')

@section('actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">New Product</a>
@endsection

@section('content')
    <section class="panel" style="margin-bottom:16px;">
        <div class="section-title">
            <h2>{{ $editingProduct ? 'Edit Product' : 'Add Product' }}</h2>
        </div>

        <form method="POST" action="{{ $editingProduct ? route('admin.products.update', $editingProduct) : route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if($editingProduct)
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input id="name" name="name" value="{{ old('name', $editingProduct?->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input id="sku" name="sku" value="{{ old('sku', $editingProduct?->sku) }}">
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">Select existing category</option>
                        @foreach($categories as $category)
                            @if($category->id)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $editingProduct?->category_id) === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="category">New Category</label>
                    <input id="category" name="category" value="{{ old('category', $editingProduct?->category_name) }}" placeholder="Use when category is not listed">
                </div>

                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input id="brand" name="brand" value="{{ old('brand', $editingProduct?->brand) }}">
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $editingProduct?->price) }}" required>
                </div>

                <div class="form-group">
                    <label for="original_price">Original Price</label>
                    <input id="original_price" type="number" step="0.01" min="0" name="original_price" value="{{ old('original_price', $editingProduct?->original_price) }}">
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $editingProduct?->stock ?? 0) }}" required>
                </div>

                <div class="form-group">
                    <label for="reorder_level">Reorder Level</label>
                    <input id="reorder_level" type="number" min="0" name="reorder_level" value="{{ old('reorder_level', $editingProduct?->reorder_level ?? 5) }}">
                </div>

                <div class="form-group">
                    <label for="badge">Badge</label>
                    <select id="badge" name="badge">
                        @foreach(['none' => 'None', 'new' => 'New', 'hot' => 'Hot', 'sale' => 'Sale'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('badge', $editingProduct?->badge ?? 'none') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input id="image" type="file" name="image" accept="image/*">
                </div>

                <div class="form-group full">
                    <label for="tags">Tags</label>
                    <input id="tags" name="tags" value="{{ old('tags', $editingProduct?->tags ? implode(', ', $editingProduct->tags) : '') }}" placeholder="pod, refillable, menthol">
                </div>

                <div class="form-group full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description">{{ old('description', $editingProduct?->description) }}</textarea>
                </div>

                <div class="form-group full">
                    <label class="checkbox-row">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $editingProduct?->is_featured))>
                        Featured product
                    </label>

                    @if($editingProduct)
                        <label class="checkbox-row">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingProduct?->is_active ?? true))>
                            Active in catalog
                        </label>
                    @endif
                </div>
            </div>

            <div class="actions" style="margin-top:16px;">
                <button class="btn btn-primary" type="submit">{{ $editingProduct ? 'Update Product' : 'Add Product' }}</button>
                @if($editingProduct)
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                @endif
            </div>
        </form>
    </section>

    <section class="panel">
        <div class="section-title">
            <h2>Product List</h2>
            <form method="GET" action="{{ route('admin.products.index') }}" style="width:min(320px,100%);">
                <input name="search" value="{{ request('search') }}" placeholder="Search products">
            </form>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                            <td>{{ $product->brand ?: 'No brand' }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $product->stock <= ($product->reorder_level ?? 5) ? 'badge-red' : 'badge-green' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'badge-green' : 'badge-gray' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted">No products found.</td>
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
