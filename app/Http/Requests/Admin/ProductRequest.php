<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $isUpdating = $product instanceof Product;

        return [
            'name' => 'required|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product instanceof Product ? $product->id : null),
            ],
            'category_id' => 'nullable|exists:categories,id',
            'category' => 'nullable|string|max:120',
            'brand' => 'nullable|string|max:120',
            'product_type' => ['required', Rule::in(array_keys(Product::TYPE_LABELS))],
            'bundle_pods' => [
                Rule::requiredIf(fn () => $this->input('product_type') === 'bundle'),
                'nullable',
                'string',
                'max:160',
            ],
            'bundle_battery' => [
                Rule::requiredIf(fn () => $this->input('product_type') === 'bundle'),
                'nullable',
                'string',
                'max:160',
            ],
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'flavors' => [
                Rule::requiredIf(fn () => $this->input('product_type') !== Product::TYPE_BATTERY),
                'array',
                'min:1',
            ],
            'flavors.*.id' => 'nullable|integer|exists:product_flavors,id',
            'flavors.*.name' => 'required|string|max:120',
            'flavors.*.stock' => 'required|integer|min:0',
            'flavors.*.reorder_level' => 'nullable|integer|min:0',
            'battery_colors' => [
                Rule::requiredIf(fn () => in_array($this->input('product_type'), [Product::TYPE_BATTERY, Product::TYPE_BUNDLE], true)),
                'array',
                'min:1',
            ],
            'battery_colors.*.id' => 'nullable|integer|exists:product_flavors,id',
            'battery_colors.*.name' => 'required|string|max:120',
            'battery_colors.*.stock' => 'required|integer|min:0',
            'battery_colors.*.reorder_level' => 'nullable|integer|min:0',
            'badge' => 'nullable|in:none,new,hot,sale',
            'tags' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:3000',
            'nicotine_type' => ['nullable', Rule::in(array_keys(Product::NICOTINE_TYPE_LABELS))],
            'nicotine_strengths' => 'nullable|string|max:255',
            'volume_ml' => 'nullable|integer|min:1|max:1000',
            'image' => [
                Rule::requiredIf(! $isUpdating),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:2048',
            ],
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $flavors = collect($this->input('flavors', []))
                ->map(fn ($flavor) => mb_strtolower(trim((string) ($flavor['name'] ?? ''))))
                ->filter();
            $batteryColors = collect($this->input('battery_colors', []))
                ->map(fn ($color) => mb_strtolower(trim((string) ($color['name'] ?? ''))))
                ->filter();

            if ($flavors->duplicates()->isNotEmpty()) {
                $validator->errors()->add('flavors', 'Each flavor name must be unique for this product.');
            }

            if ($batteryColors->duplicates()->isNotEmpty()) {
                $validator->errors()->add('battery_colors', 'Each battery color must be unique for this product.');
            }
        });
    }
}
