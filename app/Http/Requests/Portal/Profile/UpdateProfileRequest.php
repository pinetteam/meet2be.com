<?php

namespace App\Http\Requests\Portal\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;
        $tenantId = $this->user()->tenant_id;
        
        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
                Rule::unique('users')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })->ignore($userId)
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })->ignore($userId)
            ],
            'first_name' => 'required|string|min:2|max:50|regex:/^[\p{L}\s\-\'\.]+$/u',
            'last_name' => 'required|string|min:2|max:50|regex:/^[\p{L}\s\-\'\.]+$/u',
            'phone' => 'nullable|string|max:20|regex:/^[\d\s\-\+\(\)]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Kullanıcı adı zorunludur.',
            'username.min' => 'Kullanıcı adı en az 3 karakter olmalıdır.',
            'username.max' => 'Kullanıcı adı en fazla 50 karakter olabilir.',
            'username.regex' => 'Kullanıcı adı sadece harf, rakam, nokta, alt çizgi ve tire içerebilir.',
            'username.unique' => 'Bu kullanıcı adı zaten kullanımda.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.max' => 'E-posta adresi en fazla 100 karakter olabilir.',
            'email.unique' => 'Bu e-posta adresi zaten kullanımda.',
            'first_name.required' => 'Ad zorunludur.',
            'first_name.min' => 'Ad en az 2 karakter olmalıdır.',
            'first_name.max' => 'Ad en fazla 50 karakter olabilir.',
            'first_name.regex' => 'Ad sadece harf, boşluk, tire, kesme işareti ve nokta içerebilir.',
            'last_name.required' => 'Soyad zorunludur.',
            'last_name.min' => 'Soyad en az 2 karakter olmalıdır.',
            'last_name.max' => 'Soyad en fazla 50 karakter olabilir.',
            'last_name.regex' => 'Soyad sadece harf, boşluk, tire, kesme işareti ve nokta içerebilir.',
            'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
        ];
    }
} 