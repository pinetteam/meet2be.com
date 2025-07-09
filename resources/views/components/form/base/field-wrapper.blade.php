{{-- Meet2Be: Base field wrapper component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Wrapper for all form fields with consistent styling --}}

@props([
    'name' => '',
    'label' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'wrapperClass' => '',
    'labelClass' => '',
    'errorClass' => '',
    'hintClass' => '',
    'fieldId' => null
])

@php
    // Generate a unique ID if not provided
    $fieldId = $fieldId ?? ($name ? $name . '_' . uniqid() : 'field_' . uniqid());
    
    // Get error from validation errors bag
    $error = $error ?? $errors->first($name);
@endphp

<div class="space-y-1 {{ $wrapperClass }}" {{ $attributes->only(['x-data', 'x-show', 'x-if']) }}>
    @if($label)
        <label 
            for="{{ $fieldId }}" 
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 {{ $labelClass }}"
        >
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif
    
    <div>
        {{ $slot }}
    </div>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400 {{ $errorClass }}">{{ $error }}</p>
    @elseif($hint)
        <p class="text-sm text-gray-500 dark:text-gray-400 {{ $hintClass }}">{{ $hint }}</p>
    @endif
</div> 