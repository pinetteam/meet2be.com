{{-- Meet2Be: Text input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Standard text input field --}}

@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'prefix' => null,
    'suffix' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'inputClass' => '',
    'autocomplete' => null
])

@php
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
    
    // Determine autocomplete value based on field name if not provided
    if (!$autocomplete) {
        $autocompleteMap = [
            'email' => 'email',
            'password' => 'current-password',
            'new_password' => 'new-password',
            'phone' => 'tel',
            'website' => 'url',
            'address_line_1' => 'address-line1',
            'address_line_2' => 'address-line2',
            'city' => 'address-level2',
            'state' => 'address-level1',
            'postal_code' => 'postal-code',
            'country' => 'country',
            'name' => 'name',
            'first_name' => 'given-name',
            'last_name' => 'family-name',
            'organization_name' => 'organization',
            'legal_name' => 'organization',
        ];
        
        $autocomplete = $autocompleteMap[$name] ?? 'off';
    }
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <x-form.base.input-base
        :name="$name"
        :id="$fieldId"
        :type="$type"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :model="$model"
        :size="$size"
        :prefix="$prefix"
        :suffix="$suffix"
        :input-class="$inputClass"
        :autocomplete="$autocomplete"
        {{ $attributes }} />
    
</x-form.base.field-wrapper> 