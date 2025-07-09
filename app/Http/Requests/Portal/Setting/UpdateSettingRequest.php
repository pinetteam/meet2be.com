<?php

namespace App\Http\Requests\Portal\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Tenant\Tenant;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Check is done via middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $tenant = $this->user()->tenant;
        
        return [
            // Basic Information
            'name' => 'required|string|max:200',
            'legal_name' => 'nullable|string|max:200',
            
            // Contact Information
            'email' => 'required|email|max:255',
            'phone' => ['nullable', 'string', 'regex:/^\+\d{1,4}\d{4,15}$/'],
            'website' => 'nullable|url|max:255',
            
            // Address Information
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country_id' => 'nullable|uuid|exists:system_countries,id',
            
            // Localization Settings
            'language_id' => 'required|uuid|exists:system_languages,id',
            'timezone_id' => 'required|uuid|exists:system_timezones,id',
            'date_format' => ['required', 'string', Rule::in(array_keys(Tenant::DATE_FORMATS))],
            'time_format' => ['required', 'string', Rule::in(array_keys(Tenant::TIME_FORMATS))],
            
            // Subscription & Limits (only for admins)
            'plan' => ['sometimes', 'string', Rule::in(array_keys(Tenant::PLANS))],
            'max_users' => 'sometimes|integer|min:1|max:10000',
            'max_storage_mb' => 'sometimes|integer|min:100|max:1000000',
            'max_events' => 'sometimes|integer|min:1|max:100000',
        ];
    }
    
    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'phone.regex' => __('validation.phone_format'),
            'date_format.in' => __('validation.invalid_date_format'),
            'time_format.in' => __('validation.invalid_time_format'),
        ];
    }
} 