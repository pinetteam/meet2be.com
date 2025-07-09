{{-- Meet2Be: Time format select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Time format selector --}}

@props([
    'name' => 'time_format',
    'label' => null,
    'value' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'wrapperClass' => ''
])

@php
    use App\Models\Tenant\Tenant;
    
    $label = $label ?? __('settings.fields.time_format');
    $selectedValue = old($name, $value);
    
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
    
    // Get time formats from Tenant model
    $formats = Tenant::TIME_FORMATS;
    
    // Get current time for preview
    $currentTime = now();
    
    // Map formats to translation keys
    $formatLabels = [
        Tenant::TIME_FORMAT_24_HOUR => __('settings.time_formats.24_hour'),
        Tenant::TIME_FORMAT_24_HOUR_SECONDS => __('settings.time_formats.24_hour_seconds'),
        Tenant::TIME_FORMAT_12_HOUR => __('settings.time_formats.12_hour'),
        Tenant::TIME_FORMAT_12_HOUR_PADDED => __('settings.time_formats.12_hour_padded'),
        Tenant::TIME_FORMAT_12_HOUR_SECONDS => __('settings.time_formats.12_hour_seconds'),
        Tenant::TIME_FORMAT_12_HOUR_PADDED_SECONDS => __('settings.time_formats.12_hour_padded_seconds'),
    ];
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass"
    :field-id="$fieldId">
    
    <x-form.base.select-base 
        :name="$name"
        :id="$fieldId"
        :value="$selectedValue"
        :required="$required"
        :disabled="$disabled"
        {{ $attributes }}>
        
        @foreach($formats as $format => $display)
            <option value="{{ $format }}" @if($selectedValue == $format) selected @endif>
                {{ $formatLabels[$format] ?? $display }} - {{ $currentTime->format($format) }}
            </option>
        @endforeach
        
    </x-form.base.select-base>
    
</x-form.base.field-wrapper> 