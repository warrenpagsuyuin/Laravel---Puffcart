<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|max:255|unique:users,username',
            'date_of_birth' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'password' => [
                'required',
                'confirmed',
                Password::min(10)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'password_confirmation' => 'required|same:password',
            'age_confirmed' => 'required|accepted',
            'privacy_consent' => 'required|accepted',
            'captcha' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'date_of_birth.before_or_equal' => 'You must be at least 18 years old to register.',
            'valid_id.required' => 'A valid ID is required for age verification.',
            'age_confirmed.accepted' => 'You must confirm your age to register.',
            'privacy_consent.accepted' => 'You must accept the privacy policy to register.',
        ];
    }
}
