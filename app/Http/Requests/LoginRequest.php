<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'remember' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Email or username is required.',
            'password.required' => 'Password is required.',
        ];
    }
}
