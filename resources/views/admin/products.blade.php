@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage catalog items, prices, stock, and product content')

@section('actions')
    <button id="new-product-btn" class="btn btn-primary" type="button">Add Product</button>
@endsection

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

        .product-console {
            display: grid;
            gap: 20px;
        }

        .catalog-summary {
            display: grid;
            gap: 16px;
            grid-template-columns: 1.3fr minmax(220px, 0.45fr) minmax(260px, 0.55fr);
        }

        .summary-tile {
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
            min-height: 112px;
            padding: 20px;
            position: relative;
        }

        .summary-tile::before {
            background: #0b66ff;
            content: "";
            height: 3px;
            left: 20px;
            position: absolute;
            right: 20px;
            top: 0;
        }

        .summary-tile:nth-child(2)::before {
            background: #b45309;
        }

        .summary-tile:nth-child(3)::before {
            background: #047857;
        }

        .summary-tile strong {
            color: #0f172a;
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.1;
            margin-top: 14px;
            overflow-wrap: anywhere;
        }

        .summary-tile span {
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .admin-toolbar {
            align-items: center;
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
            display: grid;
            gap: 20px;
            grid-template-columns: minmax(220px, 0.8fr) minmax(0, 1.7fr);
            padding: 20px;
        }

        .admin-toolbar-title {
            gap: 4px;
        }

        .admin-toolbar-title strong,
        .catalog-head h2,
        .section-title h2 {
            color: #0f172a;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 700;
        }

        .admin-toolbar-title span {
            color: #64748b;
            font-size: 13px;
        }

        .toolbar-form {
            align-items: center;
            display: grid;
            gap: 12px;
            grid-template-columns: minmax(260px, 1fr) 220px 190px;
            justify-content: stretch;
        }

        .toolbar-form .search-control,
        .toolbar-form .filter-control {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            max-width: none;
            min-height: 44px;
            min-width: 0;
            padding: 10px 12px;
            width: 100%;
        }

        .toolbar-form .search-control:focus,
        .toolbar-form .filter-control:focus {
            border-color: #0b66ff;
            box-shadow: 0 0 0 3px #dbeafe;
            outline: none;
        }

        #product-form-section {
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
            padding: 24px;
        }

        #product-form-section .section-title {
            border-bottom: 1px solid #e2e8f0;
            margin: -4px 0 22px;
            padding-bottom: 16px;
        }

        .form-grid {
            gap: 16px;
        }

        .form-group {
            gap: 7px;
        }

        .form-group label,
        .checkbox-row {
            color: #334155;
            font-size: 13px;
            font-weight: 800;
        }

        input,
        textarea,
        select {
            border-color: #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #0b66ff;
            box-shadow: 0 0 0 3px #dbeafe;
        }

        .flavor-inventory {
            background: #f8fafc;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
        }

        .flavor-inventory-head {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }

        .flavor-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
        }

        .catalog-panel {
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
            padding: 0;
            overflow: hidden;
        }

        .catalog-head {
            align-items: center;
            background: #ffffff;
            border-bottom: 1px solid #d9e2ec;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 22px 26px;
        }

        .catalog-head .muted {
            font-size: 13px;
            margin-top: 4px;
        }

        .catalog-list {
            display: grid;
        }

        .catalog-grid-head {
            align-items: center;
            background: #f8fafc;
            border-bottom: 1px solid #d9e2ec;
            color: #64748b;
            display: grid;
            font-size: 12px;
            font-weight: 800;
            gap: 18px;
            grid-template-columns: minmax(360px, 1.9fr) minmax(150px, 0.6fr) minmax(160px, 0.7fr) minmax(190px, 0.8fr) 86px;
            letter-spacing: 0;
            padding: 14px 22px;
            text-transform: uppercase;
        }

        .catalog-row {
            align-items: center;
            display: grid;
            gap: 18px;
            grid-template-columns: minmax(360px, 1.9fr) minmax(150px, 0.6fr) minmax(160px, 0.7fr) minmax(190px, 0.8fr) 86px;
            padding: 18px 22px;
        }

        .catalog-row + .catalog-row {
            border-top: 1px solid #e2e8f0;
        }

        .catalog-row:hover {
            background: #fbfdff;
        }

        .catalog-product {
            align-items: center;
            display: flex;
            gap: 16px;
            min-width: 0;
        }

        .catalog-image {
            align-items: center;
            background: #f8fafc;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            display: inline-flex;
            height: 64px;
            justify-content: center;
            overflow: hidden;
            width: 64px;
            flex: 0 0 64px;
        }

        .catalog-image img {
            max-height: 56px;
            max-width: 56px;
            object-fit: contain;
        }

        .catalog-image-fallback {
            color: #0f172a;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0;
        }

        .catalog-name {
            color: #0f172a;
            display: block;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .catalog-meta {
            color: #64748b;
            display: block;
            font-size: 13px;
            margin-top: 4px;
        }

        .catalog-label {
            color: #64748b;
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .catalog-value {
            color: #0f172a;
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-top: 2px;
        }

        .catalog-actions {
            align-items: center;
            display: flex;
            gap: 6px;
            justify-content: flex-end;
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

        .badge-gray {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
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

        .icon-btn {
            border-color: #d9e2ec;
            border-radius: 8px;
            color: #475569;
            height: 38px;
            width: 38px;
        }

        .icon-btn:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #0b66ff;
        }

        .icon-btn.danger {
            color: #b91c1c;
        }

        .icon-btn.danger:hover {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .empty-state {
            color: #64748b;
            padding: 28px 24px;
            text-align: center;
        }

        .conditional-field.is-hidden,
        [data-option-panel].is-hidden {
            display: none;
        }

        .flavor-inventory {
            display: grid;
            gap: 12px;
            padding: 14px;
        }

        .flavor-inventory-head {
            align-items: center;
            display: flex;
            gap: 12px;
            justify-content: space-between;
        }

        .flavor-rows {
            display: grid;
            gap: 10px;
        }

        .flavor-row {
            align-items: end;
            display: grid;
            gap: 10px;
            grid-template-columns: minmax(160px, 1fr) 110px 130px auto;
        }

        @media (max-width: 980px) {
            .content {
                padding: 20px 16px 36px;
            }

            .catalog-summary,
            .admin-toolbar,
            .toolbar-form,
            .catalog-row,
            .flavor-row {
                grid-template-columns: 1fr;
            }

            .catalog-grid-head {
                display: none;
            }

            .catalog-actions {
                justify-content: flex-start;
            }

            .catalog-row {
                padding: 18px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $makeCategoryFilter = function ($category) {
            if (!empty($category->slug)) {
                return $category->slug;
            }

            $slug = strtolower((string) $category->name);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim(preg_replace('/-+/', '-', $slug), '-');

            return $slug ?: 'uncategorized';
        };

        $filterLabels = [];

        foreach ($categories as $category) {
            $filterLabels[$makeCategoryFilter($category)] = $category->name;
        }

        $currentFilter = request()->filled('filter')
            ? ($filterLabels[request('filter')] ?? request('filter'))
            : 'All Categories';

        $visibleLowStock = $products->getCollection()->filter(fn ($product) => $product->is_low_stock)->count();
    @endphp

    <div class="product-console">
        <div class="catalog-summary">
            <div class="summary-tile">
                <span>Total Catalog</span>
                <strong>{{ number_format($products->total()) }}</strong>
            </div>
            <div class="summary-tile">
                <span>Visible Low Stock</span>
                <strong>{{ number_format($visibleLowStock) }}</strong>
            </div>
            <div class="summary-tile">
                <span>Current View</span>
                <strong style="font-size:18px;">{{ $currentFilter }}</strong>
            </div>
        </div>

        <section class="admin-toolbar">
            <div class="admin-toolbar-title">
                <strong>Catalog Workspace</strong>
                <span>Search, filter, and maintain product records</span>
            </div>

            <form id="product-filter-form" method="GET" action="{{ route('admin.products.index') }}" class="toolbar-form">
                <input class="search-control" name="search" value="{{ request('search') }}" placeholder="Search products or SKU">
                <select id="category-filter" class="filter-control" name="filter" aria-label="Filter products by category">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        @php
                            $categoryFilter = $makeCategoryFilter($category);
                        @endphp
                        <option value="{{ $categoryFilter }}" @selected(request('filter') === $categoryFilter)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select class="filter-control" name="nicotine_type" aria-label="Filter products by nicotine type">
                    <option value="">All nicotine types</option>
                    @foreach(\App\Models\Product::NICOTINE_TYPE_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected(request('nicotine_type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </section>

        <section id="product-form-section" class="panel" @if(!$editingProduct && !$errors->any() && empty($showProductForm)) style="display:none;" @endif>
            <div class="section-title">
                <h2>{{ $editingProduct ? 'Edit Product' : 'Add Product' }}</h2>
            </div>

            @php
                $flavorRows = old('flavors');
                $batteryColorRows = old('battery_colors');

                if ($flavorRows === null) {
                    $sourceFlavors = ($editingProduct?->flavors ?? collect())
                        ->where('option_type', \App\Models\ProductFlavor::TYPE_FLAVOR)
                        ->values();
                    $flavorRows = $sourceFlavors->isNotEmpty()
                        ? $sourceFlavors->map(fn ($flavor) => [
                            'id' => $flavor->id,
                            'name' => $flavor->name,
                            'stock' => $flavor->stock,
                            'reorder_level' => $flavor->reorder_level,
                        ])->values()->all()
                        : [[
                            'id' => null,
                            'name' => $editingProduct?->flavor ?: '',
                            'stock' => $editingProduct?->stock ?? 0,
                            'reorder_level' => $editingProduct?->reorder_level ?? 5,
                        ]];
                }

                if ($batteryColorRows === null) {
                    $sourceBatteryColors = ($editingProduct?->flavors ?? collect())
                        ->where('option_type', \App\Models\ProductFlavor::TYPE_COLOR)
                        ->values();
                    $batteryColorRows = $sourceBatteryColors->isNotEmpty()
                        ? $sourceBatteryColors->map(fn ($color) => [
                            'id' => $color->id,
                            'name' => $color->name,
                            'stock' => $color->stock,
                            'reorder_level' => $color->reorder_level,
                        ])->values()->all()
                        : [[
                            'id' => null,
                            'name' => '',
                            'stock' => 0,
                            'reorder_level' => $editingProduct?->reorder_level ?? 5,
                        ]];
                }
            @endphp

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
                        <label for="product_type">Product Type</label>
                        <select id="product_type" name="product_type" required>
                            @foreach(\App\Models\Product::TYPE_LABELS as $value => $label)
                                <option value="{{ $value }}" @selected(old('product_type', $editingProduct?->product_type ?? \App\Models\Product::TYPE_OTHER) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group conditional-field" data-product-field="e-liquid">
                        <label for="nicotine_type">Nicotine Type</label>
                        <select id="nicotine_type" name="nicotine_type">
                            <option value="">None</option>
                            @foreach(\App\Models\Product::NICOTINE_TYPE_LABELS as $value => $label)
                                <option value="{{ $value }}" @selected(old('nicotine_type', $editingProduct?->nicotine_type) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group conditional-field" data-product-field="e-liquid">
                        <label for="nicotine_strengths">Nicotine Strengths (comma separated)</label>
                        <input id="nicotine_strengths" name="nicotine_strengths" value="{{ old('nicotine_strengths', $editingProduct?->nicotine_strengths ? implode(', ', $editingProduct->nicotine_strengths) : '') }}" placeholder="e.g. 3mg, 6mg, 12mg">
                    </div>

                    <div class="form-group conditional-field" data-product-field="e-liquid">
                        <label for="volume_ml">Bottle Size (ml)</label>
                        <input id="volume_ml" type="number" min="1" max="1000" name="volume_ml" value="{{ old('volume_ml', $editingProduct?->volume_ml) }}" placeholder="e.g. 30, 60, 100">
                    </div>

                    <div class="form-group conditional-field" data-product-field="bundle">
                        <label for="bundle_pods">Bundle Pods / Flavor</label>
                        <input id="bundle_pods" name="bundle_pods" value="{{ old('bundle_pods', $editingProduct?->bundle_pods) }}" placeholder="Pods included, e.g. XROS 0.8 Ohm">
                    </div>

                    <div class="form-group conditional-field" data-product-field="bundle">
                        <label for="bundle_battery">Bundle Battery</label>
                        <input id="bundle_battery" name="bundle_battery" value="{{ old('bundle_battery', $editingProduct?->bundle_battery) }}" placeholder="Battery/device included">
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
                        <label for="reorder_level">Reorder Level</label>
                        <input id="reorder_level" type="number" min="0" name="reorder_level" value="{{ old('reorder_level', $editingProduct?->reorder_level ?? 5) }}">
                    </div>

                    <div class="form-group full" data-option-panel="flavors">
                        <div class="flavor-inventory" data-option-builder data-field-name="flavors" data-option-label="Flavor" data-placeholder="Mint, mango, tobacco">
                            <div class="flavor-inventory-head">
                                <div>
                                    <label>Flavor Inventory</label>
                                    <div class="muted" style="font-size:12px;">Customers can only choose flavors with stock above zero.</div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-compact" data-add-flavor>Add Flavor</button>
                            </div>

                            <div class="flavor-rows" data-flavor-rows>
                                @foreach($flavorRows as $index => $flavor)
                                    <div class="flavor-row" data-flavor-row>
                                        <input type="hidden" name="flavors[{{ $index }}][id]" value="{{ $flavor['id'] ?? '' }}">
                                        <div class="form-group">
                                            <label>Flavor</label>
                                            <input name="flavors[{{ $index }}][name]" value="{{ $flavor['name'] ?? '' }}" required placeholder="Mint, mango, tobacco">
                                        </div>
                                        <div class="form-group">
                                            <label>Stock</label>
                                            <input type="number" min="0" name="flavors[{{ $index }}][stock]" value="{{ $flavor['stock'] ?? 0 }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Reorder Level</label>
                                            <input type="number" min="0" name="flavors[{{ $index }}][reorder_level]" value="{{ $flavor['reorder_level'] ?? ($editingProduct?->reorder_level ?? 5) }}" required>
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-compact" data-remove-flavor>Remove</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group full" data-option-panel="battery-colors">
                        <div class="flavor-inventory" data-option-builder data-field-name="battery_colors" data-option-label="Color" data-placeholder="Black, gold, titanium grey">
                            <div class="flavor-inventory-head">
                                <div>
                                    <label>Battery Color Inventory</label>
                                    <div class="muted" style="font-size:12px;">Use this for battery products and pods + battery bundles.</div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-compact" data-add-flavor>Add Color</button>
                            </div>

                            <div class="flavor-rows" data-flavor-rows>
                                @foreach($batteryColorRows as $index => $color)
                                    <div class="flavor-row" data-flavor-row>
                                        <input type="hidden" name="battery_colors[{{ $index }}][id]" value="{{ $color['id'] ?? '' }}">
                                        <div class="form-group">
                                            <label>Color</label>
                                            <input name="battery_colors[{{ $index }}][name]" value="{{ $color['name'] ?? '' }}" required placeholder="Black, gold, titanium grey">
                                        </div>
                                        <div class="form-group">
                                            <label>Stock</label>
                                            <input type="number" min="0" name="battery_colors[{{ $index }}][stock]" value="{{ $color['stock'] ?? 0 }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Reorder Level</label>
                                            <input type="number" min="0" name="battery_colors[{{ $index }}][reorder_level]" value="{{ $color['reorder_level'] ?? ($editingProduct?->reorder_level ?? 5) }}" required>
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-compact" data-remove-flavor>Remove</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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

                        <label class="checkbox-row">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingProduct?->is_active ?? true))>
                            Active in catalog
                        </label>
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

        <section class="panel catalog-panel">
            <div class="catalog-head">
                <div>
                    <h2>Product List</h2>
                    <div class="muted" style="font-size:12px;">{{ number_format($products->count()) }} shown on this page</div>
                </div>
            </div>

            <div class="catalog-list">
                <div class="catalog-grid-head" aria-hidden="true">
                    <span>Product</span>
                    <span>Brand</span>
                    <span>Type</span>
                    <span>Stock Status</span>
                    <span>Actions</span>
                </div>

                @forelse($products as $product)
                    <article class="catalog-row">
                        <div class="catalog-product">
                            <span class="catalog-image">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                @else
                                    @php
                                        $productInitials = strtoupper(substr((string) $product->name, 0, 2));
                                    @endphp
                                    <span class="catalog-image-fallback">{{ $productInitials }}</span>
                                @endif
                            </span>
                            <span style="min-width:0;">
                                @php
                                    $typeSuffix = '';
                                    if ($product->product_type === \App\Models\Product::TYPE_BATTERY) {
                                        $typeSuffix = '(Battery)';
                                    } elseif ($product->product_type === \App\Models\Product::TYPE_PODS) {
                                        $typeSuffix = '(POD ONLY)';
                                    } elseif ($product->product_type === \App\Models\Product::TYPE_BUNDLE) {
                                        $typeSuffix = '(Bundle)';
                                    } elseif ($product->product_type === \App\Models\Product::TYPE_E_LIQUID) {
                                        $typeSuffix = '(E-Liquid)';
                                    }
                                @endphp

                                <span class="catalog-name">
                                    {{ $product->name }}@if($typeSuffix && stripos($product->name, $typeSuffix) === false) {{ ' ' . $typeSuffix }}@endif
                                </span>
                                <span class="catalog-meta">{{ $product->sku ?: 'No SKU' }} / {{ $product->category_name }}</span>
                            </span>
                        </div>

                        <div>
                            <span class="catalog-label">Brand</span>
                            <span class="catalog-value">{{ $product->brand ?: 'Unbranded' }}</span>
                        </div>

                        <div>
                            <span class="catalog-label">Type</span>
                            <span class="catalog-value">{{ $product->product_type_label }}</span>
                            @if($product->nicotine_profile)
                                <span class="catalog-meta">Nicotine: {{ $product->nicotine_profile }}</span>
                            @endif
                            @if($product->volume_label)
                                <span class="catalog-meta">Bottle size: {{ $product->volume_label }}</span>
                            @endif
                            <span class="catalog-meta">PHP {{ number_format($product->price, 2) }}</span>
                            {{-- Flavors and colors removed from list view to reduce clutter --}}
                            {{-- Bundle description removed from list view to keep the TYPE column concise --}}
                        </div>

                        <div>
                            <span class="catalog-label">Stock Status</span>
                            <span style="display:flex;gap:6px;flex-wrap:wrap;margin-top:4px;">
                                <span class="badge {{ $product->stock <= ($product->reorder_level ?? 5) ? 'badge-red' : 'badge-green' }}">{{ $product->stock }} in stock</span>
                                <span class="badge {{ $product->is_active ? 'badge-green' : 'badge-gray' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                            </span>
                            {{-- Per-option list removed; keep stock badges above. --}}
                        </div>

                        <div class="catalog-actions">
                            <a href="{{ route('admin.products.edit', $product) }}" class="icon-btn" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn danger" title="Delete">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M3 6h18" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 11v6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 11v6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">No products found.</div>
                @endforelse
            </div>

            <div class="pagination" style="padding:0 16px 16px;">
                {{ $products->links() }}
            </div>
        </section>
    </div>

    <script>
        (function(){
            const btn = document.getElementById('new-product-btn');
            const section = document.getElementById('product-form-section');
            const filter = document.getElementById('category-filter');
            const filterForm = document.getElementById('product-filter-form');
            const productType = document.getElementById('product_type');
            const flavor = document.getElementById('flavor');
            const bundlePods = document.getElementById('bundle_pods');
            const bundleBattery = document.getElementById('bundle_battery');

            if(btn && section) {
                btn.addEventListener('click', function () {
                    if (section.style.display === 'none' || section.style.display === '') {
                        section.style.display = 'block';
                        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        section.style.display = 'none';
                    }
                });
            }

            if(filterForm) {
                filterForm.querySelectorAll('select').forEach(function (select) {
                    select.addEventListener('change', function () {
                        filterForm.submit();
                    });
                });
            }

            document.querySelectorAll('[data-option-builder]').forEach(function (builder) {
                const rows = builder.querySelector('[data-flavor-rows]');
                const addButton = builder.querySelector('[data-add-flavor]');
                const fieldName = builder.dataset.fieldName || 'flavors';
                const optionLabel = builder.dataset.optionLabel || 'Flavor';
                const placeholder = builder.dataset.placeholder || 'Mint, mango, tobacco';
                let nextIndex = rows ? rows.querySelectorAll('[data-flavor-row]').length : 0;

                function refreshRemoveButtons() {
                    if (!rows) return;

                    const buttons = rows.querySelectorAll('[data-remove-flavor]');
                    buttons.forEach(function (button) {
                        button.disabled = buttons.length <= 1;
                    });
                }

                function addFlavorRow() {
                    if (!rows) return;

                    const row = document.createElement('div');
                    row.className = 'flavor-row';
                    row.setAttribute('data-flavor-row', '');
                    row.innerHTML = `
                        <input type="hidden" name="${fieldName}[${nextIndex}][id]" value="">
                        <div class="form-group">
                            <label>${optionLabel}</label>
                            <input name="${fieldName}[${nextIndex}][name]" required placeholder="${placeholder}">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" min="0" name="${fieldName}[${nextIndex}][stock]" value="0" required>
                        </div>
                        <div class="form-group">
                            <label>Reorder Level</label>
                            <input type="number" min="0" name="${fieldName}[${nextIndex}][reorder_level]" value="5" required>
                        </div>
                        <button type="button" class="btn btn-secondary btn-compact" data-remove-flavor>Remove</button>
                    `;
                    rows.appendChild(row);
                    nextIndex += 1;
                    refreshRemoveButtons();
                }

                if (addButton) {
                    addButton.addEventListener('click', addFlavorRow);
                }

                if (rows) {
                    rows.addEventListener('click', function (event) {
                        if (!event.target.matches('[data-remove-flavor]')) return;

                        const row = event.target.closest('[data-flavor-row]');
                        if (row && rows.querySelectorAll('[data-flavor-row]').length > 1) {
                            row.remove();
                            refreshRemoveButtons();
                        }
                    });
                }

                refreshRemoveButtons();
            });

            function syncProductTypeFields() {
                if (!productType) return;

                const type = productType.value;
                const flavorWrap = document.querySelector('[data-product-field="flavor"]');
                const bundleWraps = document.querySelectorAll('[data-product-field="bundle"]');
                const flavorPanel = document.querySelector('[data-option-panel="flavors"]');
                const colorPanel = document.querySelector('[data-option-panel="battery-colors"]');

                function setPanel(panel, isVisible) {
                    if (!panel) return;

                    panel.classList.toggle('is-hidden', !isVisible);
                    panel.querySelectorAll('input, select, textarea').forEach((field) => {
                        field.disabled = !isVisible;
                    });
                }

                if (flavorWrap) {
                    flavorWrap.classList.toggle('is-hidden', type === 'battery' || type === 'other');
                }

                bundleWraps.forEach((wrap) => {
                    wrap.classList.toggle('is-hidden', type !== 'bundle');
                });

                setPanel(flavorPanel, type !== 'battery');
                setPanel(colorPanel, type === 'battery' || type === 'bundle');

                if (flavor) {
                    flavor.required = type === 'pods' || type === 'bundle';
                    if (type === 'battery' || type === 'other') {
                        flavor.value = '';
                    }
                }

                if (bundlePods) {
                    bundlePods.required = type === 'bundle';
                    if (type !== 'bundle') {
                        bundlePods.value = '';
                    }
                }

                if (bundleBattery) {
                    bundleBattery.required = type === 'bundle';
                    if (type !== 'bundle') {
                        bundleBattery.value = '';
                    }
                }
            }

            if (productType) {
                productType.addEventListener('change', syncProductTypeFields);
                syncProductTypeFields();
            }

                // Toggle nicotine fields when category is E-liquids
                const categorySelect = document.getElementById('category_id');
                const newCategoryInput = document.getElementById('category');
                const nicotineFields = document.querySelectorAll('[data-product-field="e-liquid"]');

                function syncNicotineFields() {
                    const selectedText = categorySelect?.options[categorySelect.selectedIndex]?.text || '';
                    const newCategoryText = newCategoryInput?.value || '';
                    const categoryIsELiquid = /e-?liquids?/i.test(selectedText) || /e-?liquids?/i.test(newCategoryText);

                    if (categoryIsELiquid && productType?.value === 'other') {
                        productType.value = 'e_liquid';
                        syncProductTypeFields();
                    }

                    const isELiquid = productType?.value === 'e_liquid' || categoryIsELiquid;

                    nicotineFields.forEach((el) => {
                        el.classList.toggle('is-hidden', !isELiquid);
                        el.querySelectorAll('input, select, textarea').forEach((field) => {
                            field.disabled = !isELiquid;
                        });
                    });
                }

                if (categorySelect) {
                    categorySelect.addEventListener('change', syncNicotineFields);
                }
                if (newCategoryInput) {
                    newCategoryInput.addEventListener('input', syncNicotineFields);
                }
                if (productType) {
                    productType.addEventListener('change', syncNicotineFields);
                }
                syncNicotineFields();
        })();
    </script>
@endsection
