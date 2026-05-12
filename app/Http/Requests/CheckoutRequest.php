<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'delivery_address' => 'required|string|min:10|max:1000',
            'delivery_phone' => 'required|digits:11',
            'payment_method' => 'required|in:gcash,maya,cod,bank_transfer',
            'promo_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'cart_item_ids' => 'nullable|array',
            'cart_item_ids.*' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'delivery_address.required' => 'Delivery address is required.',
            'delivery_address.min' => 'Delivery address must be at least 10 characters.',
            'delivery_phone.digits' => 'Delivery phone must be exactly 11 digits.',
            'payment_method.in' => 'Please choose a valid payment method.',
        ];
    }
}
