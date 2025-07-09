{{-- Meet2Be: Radio group component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Group of radio buttons with consistent styling --}}

@props([
    'name' => '',
    'label' => null,
    'options' => [],
    'selected' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'inline' => false,
    'wrapperClass' => ''
])

@php
    // Get selected value
    $selectedValue = old($name, $selected);
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div class="{{ $inline ? 'flex flex-wrap gap-4' : 'space-y-2' }}">
        @foreach($options as $value => $optionLabel)
            <x-form.radio
                :name="$name"
                :value="$value"
                :label="$optionLabel"
                :checked="$selectedValue == $value"
                :disabled="$disabled"
                :model="$model"
                :size="$size"
                wrapper-class="{{ $inline ? '' : '' }}"
            />
        @endforeach
    </div>
    
</x-form.base.field-wrapper> 