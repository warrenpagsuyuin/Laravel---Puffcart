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
            'delivery_phone' => 'required|string|max:30',
            'payment_method' => 'required|in:gcash,maya,cod,bank_transfer',
            'promo_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
