{{-- Meet2Be: Main input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Routes to appropriate input type component --}}

@props([
    'type' => 'text',
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'wrapperClass' => ''
])

@php
    // Map input types to their autocomplete values
    $autocompleteMap = [
        'email' => 'email',
        'tel' => 'tel',
        'url' => 'url',
        'password' => 'current-password',
        'new-password' => 'new-password',
        'search' => 'off',
        'number' => 'off',
        'date' => 'off',
        'time' => 'off',
        'datetime-local' => 'off',
    ];
    
    $autocomplete = $autocompleteMap[$type] ?? 'off';
    
    // For password type, check if it's a new password field
    if ($type === 'password' && (str_contains($name, 'new') || str_contains($name, 'confirm'))) {
        $autocomplete = 'new-password';
    }
    
    // Generate field ID
    $fieldId = $name . '_' . uniqid();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass"
    :field-id="$fieldId">
    
    <x-form.base.input-base
        :type="$type"
        :name="$name"
        :id="$fieldId"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :autocomplete="$autocomplete"
        {{ $attributes }}
    />
    
</x-form.base.field-wrapper> 