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
    'wrapperClass' => ''
])

@php
    use App\Models\Tenant\Tenant;
    
    $label = $label ?? __('settings.fields.date_format');
    $selectedValue = old($name, $value);
    
    // Get date formats from Tenant model
    $formats = Tenant::DATE_FORMATS;
    
    // Get current date for preview
    $currentDate = now();
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
                {{ $display }} - {{ $currentDate->format($format) }}
            </option>
        @endforeach
        
    </x-form.base.select>
    
</x-form.base.field-wrapper> 