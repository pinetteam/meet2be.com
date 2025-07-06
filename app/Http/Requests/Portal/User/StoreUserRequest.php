<?php

namespace App\Http\Requests\Portal\User;

use App\Models\User\User;
use App\Services\TenantService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = TenantService::getCurrentTenantId();
        
        return [
            'username' => [
                'required', 
                'string', 
                'min:3', 
                'max:50', 
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::unique('users', 'username')->where('tenant_id', $tenantId)
            ],
            'email' => [
                'required', 
                'email:rfc,dns', 
                'max:255', 
                Rule::unique('users', 'email')->where('tenant_id', $tenantId)
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/'],
            'last_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'status' => ['required', Rule::in(array_keys(User::STATUSES))],
            'type' => ['required', Rule::in(array_keys(User::TYPES))],
            'settings' => ['nullable', 'array'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'username' => strtolower($this->username),
            'email' => strtolower($this->email),
            'first_name' => ucwords(strtolower($this->first_name)),
            'last_name' => ucwords(strtolower($this->last_name)),
        ]);
    }
} 