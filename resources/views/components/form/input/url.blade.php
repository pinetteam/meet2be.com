{{-- Meet2Be: URL input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- URL input with validation --}}

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
    'icon' => null,
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
        type="url"
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