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
            'phone' => 'nullable|string|max:30',
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
            'name.required' => __('validation.custom.tenant.name_required'),
            'email.required' => __('validation.custom.tenant.email_required'),
            'email.email' => __('validation.custom.tenant.email_invalid'),
            'phone.regex' => __('validation.custom.tenant.phone_format'),
            'website.regex' => __('validation.custom.tenant.website_format'),
            'language_id.required' => __('validation.custom.tenant.language_required'),
            'timezone_id.required' => __('validation.custom.tenant.timezone_required'),
            'date_format.required' => __('validation.custom.tenant.date_format_required'),
            'date_format.in' => __('validation.custom.tenant.date_format_invalid'),
            'time_format.required' => __('validation.custom.tenant.time_format_required'),
            'time_format.in' => __('validation.custom.tenant.time_format_invalid'),
        ];
    }
} 