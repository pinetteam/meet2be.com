{{-- Meet2Be: Date format select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Date format selector --}}

@props([
    'name' => 'date_format',
    'label' => null,
    'value' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'wrapperClass' => '',
    'fieldId' => null
])

@php
    use App\Models\Tenant\Tenant;
    
    $label = $label ?? __('settings.fields.date_format');
    $selectedValue = old($name, $value);
    
    // Get date formats from Tenant model
    $formats = Tenant::DATE_FORMATS;
    
    // Get current date for preview
    $currentDate = now();
    
    // Map formats to translation keys
    $formatLabels = [
        Tenant::DATE_FORMAT_DMY_SLASH => __('settings.date_formats.european'),
        Tenant::DATE_FORMAT_MDY_SLASH => __('settings.date_formats.us'),
        Tenant::DATE_FORMAT_YMD_DASH => __('settings.date_formats.iso8601'),
        Tenant::DATE_FORMAT_DMY_DOT => __('settings.date_formats.european_dot'),
        Tenant::DATE_FORMAT_DMY_DASH => __('settings.date_formats.european_dash'),
        Tenant::DATE_FORMAT_MDY_DASH => __('settings.date_formats.us_dash'),
        Tenant::DATE_FORMAT_SHORT_MONTH => __('settings.date_formats.short'),
        Tenant::DATE_FORMAT_FULL_MONTH => __('settings.date_formats.long'),
        Tenant::DATE_FORMAT_DAY_MONTH_YEAR => __('settings.date_formats.compact'),
        Tenant::DATE_FORMAT_DAY_MONTH_YEAR_PADDED => __('settings.date_formats.medium'),
    ];
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <x-form.base.select-base 
        :name="$name"
        :id="$fieldId"
        :value="$selectedValue"
        :required="$required"
        :disabled="$disabled"
        {{ $attributes }}>
        
        @foreach($formats as $format => $display)
            <option value="{{ $format }}" @if($selectedValue == $format) selected @endif>
                {{ $formatLabels[$format] ?? $display }} - {{ $currentDate->format($format) }}
            </option>
        @endforeach
        
    </x-form.base.select-base>
    
</x-form.base.field-wrapper> 