<?php

namespace App\Http\Requests\Portal\User;

use App\Models\User\User;
use App\Services\TenantService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = TenantService::getCurrentTenantId();
        $userId = $this->route('user')->id;
        
        return [
            'username' => [
                'required', 
                'string', 
                'min:3', 
                'max:50', 
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::unique('users', 'username')
                    ->where('tenant_id', $tenantId)
                    ->ignore($userId)
            ],
            'email' => [
                'required', 
                'email:rfc,dns', 
                'max:255', 
                Rule::unique('users', 'email')
                    ->where('tenant_id', $tenantId)
                    ->ignore($userId)
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/u'],
            'last_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/u'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'status' => ['required', Rule::in(array_keys(User::STATUSES))],
            'type' => ['required', Rule::in(array_keys(User::TYPES))],
            'settings' => ['nullable', 'array'],
        ];
    }

    public function prepareForValidation(): void
    {
        $data = [
            'username' => strtolower($this->username),
            'email' => strtolower($this->email),
        ];

        if ($this->filled('password')) {
            $data['password'] = $this->password;
        } else {
            unset($data['password']);
        }

        $this->merge($data);
    }
    
    public function messages(): array
    {
        return [
            'username.required' => __('validation.required', ['attribute' => __('user.fields.username')]),
            'username.unique' => __('validation.unique', ['attribute' => __('user.fields.username')]),
            'email.required' => __('validation.required', ['attribute' => __('user.fields.email')]),
            'email.email' => __('validation.email', ['attribute' => __('user.fields.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('user.fields.email')]),
            'first_name.required' => __('validation.required', ['attribute' => __('user.fields.first_name')]),
            'last_name.required' => __('validation.required', ['attribute' => __('user.fields.last_name')]),
            'password.min' => __('validation.min.string', ['attribute' => __('user.fields.password'), 'min' => 8]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('user.fields.password')]),
        ];
    }
} 