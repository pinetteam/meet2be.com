<?php

namespace App\Http\Requests\Site\Auth;

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
            'email' => 'required|email:rfc,dns|max:255',
            'password' => 'required|string|min:8',
            'remember' => 'nullable|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('site.common.email_required'),
            'email.email' => __('site.common.email_invalid'),
            'password.required' => __('site.common.password_required'),
            'password.min' => __('site.common.password_min')
        ];
    }
} 