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
    
    // Get time formats from Tenant model
    $formats = Tenant::TIME_FORMATS;
    
    // Get current time for preview
    $currentTime = now();
    
    // Time format examples
    $examples = [
        Tenant::TIME_FORMAT_12 => $currentTime->format('g:i A'),
        Tenant::TIME_FORMAT_24 => $currentTime->format('H:i'),
    ];
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <x-form.base.select 
        :name="$name"
        :value="$selectedValue"
        :required="$required"
        :disabled="$disabled"
        {{ $attributes }}>
        
        @foreach($formats as $format => $display)
            <option value="{{ $format }}" @if($selectedValue == $format) selected @endif>
                {{ __('settings.time_formats.' . $format) }} - {{ $examples[$format] ?? '' }}
            </option>
        @endforeach
        
    </x-form.base.select>
    
</x-form.base.field-wrapper> 