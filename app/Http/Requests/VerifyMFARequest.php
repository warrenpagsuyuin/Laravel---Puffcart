<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyMFARequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mfa_code' => 'required|string|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'mfa_code.required' => 'MFA code is required.',
            'mfa_code.digits' => 'MFA code must be 6 digits.',
        ];
    }
}
