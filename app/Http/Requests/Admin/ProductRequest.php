<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $product = $this->route('product');

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
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'badge' => 'nullable|in:none,new,hot,sale',
            'tags' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:3000',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];
    }
}
