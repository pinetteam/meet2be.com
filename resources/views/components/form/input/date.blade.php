{{-- Meet2Be: Date input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Date picker input --}}

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
    'min' => null,
    'max' => null,
    'model' => null,
    'size' => 'md',
    'icon' => 'fa-solid fa-calendar',
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
        type="date"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :autofocus="$autofocus"
        :min="$min"
        :max="$max"
        :model="$model"
        :size="$size"
        :icon="$icon"
        {{ $attributes }} />
        
</x-form.base.field-wrapper> 