<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'product_flavor_id' => 'required|integer|exists:product_flavors,id',
            'battery_color_id' => 'nullable|integer|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1|max:99',
            'selected_flavor' => 'nullable|string|max:120',
            'product_type' => ['nullable', Rule::in(['pods', 'battery', 'bundle', 'other'])],
            'intent' => ['nullable', Rule::in(['add_to_cart', 'buy_now'])],
        ];
    }
}
