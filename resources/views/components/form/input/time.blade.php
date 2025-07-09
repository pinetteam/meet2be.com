{{-- Meet2Be: Time input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Time picker input --}}

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
    'step' => null,
    'model' => null,
    'size' => 'md',
    'icon' => 'fa-solid fa-clock',
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
        type="time"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :autofocus="$autofocus"
        :min="$min"
        :max="$max"
        :step="$step"
        :model="$model"
        :size="$size"
        :icon="$icon"
        {{ $attributes }} />
        
</x-form.base.field-wrapper> 