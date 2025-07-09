{{-- Meet2Be: Tel input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Basic telephone input --}}

@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'model' => null,
    'size' => 'md',
    'icon' => 'fa-solid fa-phone',
    'wrapperClass' => ''
])

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <x-form.base.input-base
        :name="$name"
        type="tel"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :autofocus="$autofocus"
        :model="$model"
        :size="$size"
        :icon="$icon"
        {{ $attributes }} />
        
</x-form.base.field-wrapper> 