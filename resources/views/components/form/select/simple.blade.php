{{-- Meet2Be: Simple select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Basic select without search functionality --}}

@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'options' => [], // Array or Collection of options
    'optionValue' => 'id', // Field to use as option value
    'optionLabel' => 'name' // Field to use as option label
])

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <x-form.base.select-base
        :name="$name"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :multiple="$multiple"
        :model="$model"
        :size="$size"
        {{ $attributes }}>
        
        @if($slot->isNotEmpty())
            {{ $slot }}
        @else
            @foreach($options as $option)
                @php
                    $optValue = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                    $optLabel = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                    $isSelected = $multiple 
                        ? in_array($optValue, (array) old($name, $value)) 
                        : old($name, $value) == $optValue;
                @endphp
                <option value="{{ $optValue }}" @if($isSelected) selected @endif>
                    {{ $optLabel }}
                </option>
            @endforeach
        @endif
        
    </x-form.base.select-base>
    
</x-form.base.field-wrapper> 