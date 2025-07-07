<?php

namespace App\Http\Requests\Portal\Profile;

use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $section = $this->input('section', 'all');
        
        // Section bazlı validasyon kuralları
        switch ($section) {
            case 'personal':
                return [
                    'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-\.]+$/u'],
                    'last_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-\.]+$/u'],
                    'username' => [
                        'required',
                        'string',
                        'min:3',
                        'max:30',
                        'regex:/^[a-zA-Z0-9_]+$/',
                        Rule::unique(User::class)->ignore($this->user()->id)->where('tenant_id', $this->user()->tenant_id)
                    ],
                ];
                
            case 'contact':
                return [
                    'email' => [
                        'required',
                        'string',
                        'email:rfc,dns',
                        'max:150',
                        Rule::unique(User::class)->ignore($this->user()->id)->where('tenant_id', $this->user()->tenant_id)
                    ],
                    'phone' => ['nullable', 'string', 'digits_between:7,15'],
                    'phone_country_id' => ['nullable', 'required_with:phone', 'exists:system_countries,id'],
                ];
                
            case 'password':
                return [
                    'current_password' => ['required', 'string'],
                    'password' => [
                        'required',
                        'string',
                        'confirmed',
                        Password::min(8)
                            ->mixedCase()
                            ->numbers()
                            ->symbols()
                    ],
                ];
                
            default:
                // Tüm alanlar için kurallar
                return [
                    'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-\.]+$/u'],
                    'last_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L}\s\-\.]+$/u'],
                    'username' => [
                        'required',
                        'string',
                        'min:3',
                        'max:30',
                        'regex:/^[a-zA-Z0-9_]+$/',
                        Rule::unique(User::class)->ignore($this->user()->id)->where('tenant_id', $this->user()->tenant_id)
                    ],
                    'email' => [
                        'required',
                        'string',
                        'email:rfc,dns',
                        'max:150',
                        Rule::unique(User::class)->ignore($this->user()->id)->where('tenant_id', $this->user()->tenant_id)
                    ],
                    'phone' => ['nullable', 'string', 'digits_between:7,15'],
                    'phone_country_id' => ['nullable', 'required_with:phone', 'exists:system_countries,id'],
                ];
        }
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Ad alanı zorunludur.',
            'first_name.string' => 'Ad metin formatında olmalıdır.',
            'first_name.min' => 'Ad en az 2 karakter olmalıdır.',
            'first_name.max' => 'Ad en fazla 50 karakter olabilir.',
            'first_name.regex' => 'Ad sadece harf, boşluk, tire ve nokta içerebilir.',
            
            'last_name.required' => 'Soyad alanı zorunludur.',
            'last_name.string' => 'Soyad metin formatında olmalıdır.',
            'last_name.min' => 'Soyad en az 2 karakter olmalıdır.',
            'last_name.max' => 'Soyad en fazla 50 karakter olabilir.',
            'last_name.regex' => 'Soyad sadece harf, boşluk, tire ve nokta içerebilir.',
            
            'username.required' => 'Kullanıcı adı zorunludur.',
            'username.string' => 'Kullanıcı adı metin formatında olmalıdır.',
            'username.min' => 'Kullanıcı adı en az 3 karakter olmalıdır.',
            'username.max' => 'Kullanıcı adı en fazla 30 karakter olabilir.',
            'username.regex' => 'Kullanıcı adı sadece harf, rakam ve alt çizgi içerebilir.',
            'username.unique' => 'Bu kullanıcı adı zaten kullanılıyor.',
            
            'email.required' => 'E-posta adresi zorunludur.',
            'email.string' => 'E-posta adresi metin formatında olmalıdır.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.max' => 'E-posta adresi en fazla 150 karakter olabilir.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            
            'phone.string' => 'Telefon numarası metin formatında olmalıdır.',
            'phone.digits_between' => 'Telefon numarası 7-15 rakam arasında olmalıdır.',
            
            'phone_country_id.required_with' => 'Telefon numarası girildiğinde ülke seçimi zorunludur.',
            'phone_country_id.exists' => 'Geçersiz ülke seçimi.',
            
            'current_password.required' => 'Mevcut şifre alanı zorunludur.',
            'current_password.string' => 'Mevcut şifre metin formatında olmalıdır.',
            
            'password.required' => 'Yeni şifre alanı zorunludur.',
            'password.string' => 'Şifre metin formatında olmalıdır.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
            'password.min' => 'Şifre en az :min karakter olmalıdır.',
            'password.mixed' => 'Şifre en az bir büyük harf ve bir küçük harf içermelidir.',
            'password.numbers' => 'Şifre en az bir rakam içermelidir.',
            'password.symbols' => 'Şifre en az bir özel karakter içermelidir.',
        ];
    }
} 