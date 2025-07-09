{{-- Meet2Be: Radio group component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Group of radio buttons with label and error handling --}}

@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => '',
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'inline' => false,
    'hint' => null,
    'wrapperClass' => '',
    'optionValue' => 'id',
    'optionLabel' => 'name'
])

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div class="{{ $inline ? 'flex flex-wrap gap-4' : 'space-y-2' }}">
        @foreach($options as $option)
            @php
                $optValue = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                $optLabel = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                $isChecked = old($name, $value) == $optValue;
            @endphp
            
            <x-form.radio
                :name="$name"
                :value="$optValue"
                :label="$optLabel"
                :checked="$isChecked"
                :disabled="$disabled"
                :model="$model"
                :size="$size" />
        @endforeach
        
        @if($slot->isNotEmpty())
            {{ $slot }}
        @endif
    </div>
    
</x-form.base.field-wrapper> 