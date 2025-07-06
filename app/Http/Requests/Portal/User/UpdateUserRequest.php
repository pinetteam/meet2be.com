<?php

namespace App\Http\Requests\Portal\User;

use App\Models\User\User;
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
        $user = $this->route('user');
        
        return [
            'tenant_id' => ['required', 'uuid', 'exists:tenants,id'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9_-]+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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
        $data = [
            'username' => strtolower($this->username),
            'email' => strtolower($this->email),
            'first_name' => ucwords(strtolower($this->first_name)),
            'last_name' => ucwords(strtolower($this->last_name)),
        ];

        if (empty($this->password)) {
            unset($data['password']);
        }

        $this->merge($data);
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        return $validated;
    }
} 