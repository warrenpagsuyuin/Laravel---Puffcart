@php
    $flavorRows = old('flavors');

    if ($flavorRows === null) {
        $sourceFlavors = $editingProduct?->flavors ?? collect();
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
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
        <input id="name" name="name" value="{{ old('name', $editingProduct?->name) }}" required class="w-full border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>

    <div>
        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
        <input id="sku" name="sku" value="{{ old('sku', $editingProduct?->sku) }}" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <select id="category_id" name="category_id" class="w-full border-gray-200 rounded-md px-3 py-2 bg-white">
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

    <div>
        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">New Category</label>
        <input id="category" name="category" value="{{ old('category', $editingProduct?->category_name) }}" placeholder="Use when category is not listed" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
        <input id="brand" name="brand" value="{{ old('brand', $editingProduct?->brand) }}" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="product_type" class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
        <select id="product_type" name="product_type" required class="w-full border-gray-200 rounded-md px-3 py-2 bg-white">
            @foreach(\App\Models\Product::TYPE_LABELS as $value => $label)
                <option value="{{ $value }}" @selected(old('product_type', $editingProduct?->product_type ?? \App\Models\Product::TYPE_OTHER) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div data-nicotine-field>
        <label for="nicotine_type" class="block text-sm font-medium text-gray-700 mb-1">Nicotine Type</label>
        <select id="nicotine_type" name="nicotine_type" class="w-full border-gray-200 rounded-md px-3 py-2 bg-white">
            <option value="">None</option>
            @foreach(\App\Models\Product::NICOTINE_TYPE_LABELS as $value => $label)
                <option value="{{ $value }}" @selected(old('nicotine_type', $editingProduct?->nicotine_type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div data-nicotine-field>
        <label for="nicotine_strengths" class="block text-sm font-medium text-gray-700 mb-1">Nicotine Strengths</label>
        <input id="nicotine_strengths" name="nicotine_strengths" value="{{ old('nicotine_strengths', $editingProduct?->nicotine_strengths ? implode(', ', $editingProduct->nicotine_strengths) : '') }}" placeholder="e.g. 3mg, 6mg, 12mg" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div data-nicotine-field>
        <label for="volume_ml" class="block text-sm font-medium text-gray-700 mb-1">Bottle Size (ml)</label>
        <input id="volume_ml" type="number" min="1" max="1000" name="volume_ml" value="{{ old('volume_ml', $editingProduct?->volume_ml) }}" placeholder="e.g. 30, 60, 100" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="bundle_pods" class="block text-sm font-medium text-gray-700 mb-1">Bundle Pods / Flavor</label>
        <input id="bundle_pods" name="bundle_pods" value="{{ old('bundle_pods', $editingProduct?->bundle_pods) }}" placeholder="Required for bundles" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="bundle_battery" class="block text-sm font-medium text-gray-700 mb-1">Bundle Battery</label>
        <input id="bundle_battery" name="bundle_battery" value="{{ old('bundle_battery', $editingProduct?->bundle_battery) }}" placeholder="Required for bundles" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $editingProduct?->price) }}" required class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="original_price" class="block text-sm font-medium text-gray-700 mb-1">Original Price</label>
        <input id="original_price" type="number" step="0.01" min="0" name="original_price" value="{{ old('original_price', $editingProduct?->original_price) }}" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div>
        <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
        <input id="reorder_level" type="number" min="0" name="reorder_level" value="{{ old('reorder_level', $editingProduct?->reorder_level ?? 5) }}" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div class="md:col-span-2" data-flavor-builder data-show-for="pods,bundle,e_liquid,other">
        <div class="p-4 border rounded-lg bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 mb-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Flavor Inventory</label>
                    <p class="text-xs text-gray-500">Customers can only choose flavors with stock above zero.</p>
                </div>
                <button type="button" data-add-flavor class="btn btn-secondary">Add Flavor</button>
            </div>

            <div class="grid gap-3" data-flavor-rows>
            @foreach($flavorRows as $index => $flavor)
                <div class="grid grid-cols-1 md:grid-cols-[1fr_120px_140px_auto] gap-3 items-end" data-flavor-row>
                    <input type="hidden" name="flavors[{{ $index }}][id]" value="{{ $flavor['id'] ?? '' }}">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Flavor</label>
                        <input name="flavors[{{ $index }}][name]" value="{{ $flavor['name'] ?? '' }}" required placeholder="Mint, mango, tobacco" class="w-full border-gray-200 rounded-md px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Stock</label>
                        <input type="number" min="0" name="flavors[{{ $index }}][stock]" value="{{ $flavor['stock'] ?? 0 }}" required class="w-full border-gray-200 rounded-md px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Reorder Level</label>
                        <input type="number" min="0" name="flavors[{{ $index }}][reorder_level]" value="{{ $flavor['reorder_level'] ?? ($editingProduct?->reorder_level ?? 5) }}" required class="w-full border-gray-200 rounded-md px-3 py-2" />
                    </div>
                    <button type="button" data-remove-flavor class="btn btn-danger">Remove</button>
                </div>
            @endforeach
        </div>
        </div>
    </div>

    <div>
        <label for="badge" class="block text-sm font-medium text-gray-700 mb-1">Badge</label>
        <select id="badge" name="badge" class="w-full border-gray-200 rounded-md px-3 py-2 bg-white">
            @foreach(['none' => 'None', 'new' => 'New', 'hot' => 'Hot', 'sale' => 'Sale'] as $value => $label)
                <option value="{{ $value }}" @selected(old('badge', $editingProduct?->badge ?? 'none') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    

    <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
        <input id="image" type="file" name="image" accept="image/*" class="w-full" />
    </div>

    <div class="md:col-span-2">
        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
        <input id="tags" name="tags" value="{{ old('tags', $editingProduct?->tags ? implode(', ', $editingProduct->tags) : '') }}" placeholder="pod, refillable, menthol" class="w-full border-gray-200 rounded-md px-3 py-2" />
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea id="description" name="description" class="w-full border-gray-200 rounded-md px-3 py-2 min-h-[120px]">{{ old('description', $editingProduct?->description) }}</textarea>
    </div>

    <div class="md:col-span-2 flex items-start gap-6">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $editingProduct?->is_featured)) class="h-4 w-4" />
            <span>Featured product</span>
        </label>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="hidden" name="is_active" value="0" />
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingProduct?->is_active ?? true)) class="h-4 w-4" />
            <span>Active in catalog</span>
        </label>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-flavor-builder]').forEach(function (builder) {
                    if (builder.dataset.ready === '1') return;
                    builder.dataset.ready = '1';

                    // Toggle visibility based on product type select
                    const showFor = (builder.dataset.showFor || '').split(',').map(s => s.trim()).filter(Boolean);
                    const productType = document.getElementById('product_type');
                    function updateVisibility() {
                        const value = productType?.value || '';
                        if (showFor.includes(value)) {
                            builder.style.display = '';
                        } else {
                            builder.style.display = 'none';
                        }
                    }
                    // initialize visibility
                    if (productType) {
                        updateVisibility();
                        productType.addEventListener('change', updateVisibility);
                    }

                    const nicotineFields = document.querySelectorAll('[data-nicotine-field]');
                    function updateNicotineVisibility() {
                        const isELiquid = productType?.value === 'e_liquid';
                        nicotineFields.forEach(function (field) {
                            field.style.display = isELiquid ? '' : 'none';
                            field.querySelectorAll('input, select').forEach(function (input) {
                                input.disabled = !isELiquid;
                            });
                        });
                    }

                    if (productType) {
                        updateNicotineVisibility();
                        productType.addEventListener('change', updateNicotineVisibility);
                    }

                    const rows = builder.querySelector('[data-flavor-rows]');
                    const addButton = builder.querySelector('[data-add-flavor]');
                    let nextIndex = rows.querySelectorAll('[data-flavor-row]').length;

                    function refreshRemoveButtons() {
                        const buttons = rows.querySelectorAll('[data-remove-flavor]');
                        buttons.forEach(function (button) {
                            button.disabled = buttons.length <= 1;
                        });
                    }

                    function addRow() {
                        const row = document.createElement('div');
                        row.className = 'grid grid-cols-1 md:grid-cols-[1fr_120px_140px_auto] gap-3 items-end';
                        row.setAttribute('data-flavor-row', '');
                        row.innerHTML = `
                            <input type="hidden" name="flavors[${nextIndex}][id]" value="">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Flavor</label>
                                <input name="flavors[${nextIndex}][name]" required placeholder="Mint, mango, tobacco" class="w-full border-gray-200 rounded-md px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Stock</label>
                                <input type="number" min="0" name="flavors[${nextIndex}][stock]" value="0" required class="w-full border-gray-200 rounded-md px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Reorder Level</label>
                                <input type="number" min="0" name="flavors[${nextIndex}][reorder_level]" value="5" required class="w-full border-gray-200 rounded-md px-3 py-2" />
                            </div>
                            <button type="button" data-remove-flavor class="btn btn-secondary">Remove</button>
                        `;
                        rows.appendChild(row);
                        nextIndex += 1;
                        refreshRemoveButtons();
                    }

                    addButton.addEventListener('click', addRow);
                    rows.addEventListener('click', function (event) {
                        if (!event.target.matches('[data-remove-flavor]')) return;

                        const row = event.target.closest('[data-flavor-row]');
                        if (row && rows.querySelectorAll('[data-flavor-row]').length > 1) {
                            row.remove();
                            refreshRemoveButtons();
                        }
                    });

                    refreshRemoveButtons();
                });

                
            });
        </script>
    @endpush
@endonce
