{{-- Meet2Be: Select dropdown component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Standard select dropdown with consistent styling --}}

@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'size' => null,
    'autocomplete' => 'off'
])

@php
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint">
    
    <select 
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $fieldId }}"
        autocomplete="{{ $autocomplete }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($multiple) multiple @endif
        @if($size) size="{{ $size }}" @endif
        {{ $attributes->merge(['class' => 'block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white transition-colors duration-150' . ($disabled ? ' bg-gray-50 dark:bg-gray-600 cursor-not-allowed' : '')]) }}>
        
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $label)
            <option 
                value="{{ $value }}" 
                @if($multiple)
                    {{ in_array($value, old($name, $selected ?? [])) ? 'selected' : '' }}
                @else
                    {{ old($name, $selected) == $value ? 'selected' : '' }}
                @endif>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
</x-form.base.field-wrapper> 