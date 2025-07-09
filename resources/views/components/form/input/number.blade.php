{{-- Meet2Be: Number input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Number input with optional min/max/step --}}

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
    'prefix' => null,
    'suffix' => null,
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
        type="number"
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
        :prefix="$prefix"
        :suffix="$suffix"
        {{ $attributes }} />
        
</x-form.base.field-wrapper> 