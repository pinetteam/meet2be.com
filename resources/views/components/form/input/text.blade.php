{{-- Meet2Be: Text input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Standard text input with all features --}}

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
    'autocomplete' => null,
    'maxlength' => null,
    'pattern' => null,
    'model' => null,
    'size' => 'md',
    'icon' => null,
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
        type="text"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :autofocus="$autofocus"
        :autocomplete="$autocomplete"
        :maxlength="$maxlength"
        :pattern="$pattern"
        :model="$model"
        :size="$size"
        :icon="$icon"
        :prefix="$prefix"
        :suffix="$suffix"
        {{ $attributes }} />
        
</x-form.base.field-wrapper> 