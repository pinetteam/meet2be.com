{{-- Meet2Be: Range input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Range slider input --}}

@props([
    'name',
    'label' => null,
    'value' => '50',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'min' => '0',
    'max' => '100',
    'step' => '1',
    'model' => null,
    'showValue' => true,
    'size' => 'md',
    'wrapperClass' => ''
])

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div x-data="{ value: {{ $model ? '$wire.entangle(\'' . $model . '\')' : old($name, $value) }} }">
        <input 
            type="range"
            name="{{ $name }}"
            id="{{ $name }}"
            x-model="value"
            @if($required) required @endif
            @if($disabled) disabled @endif
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"
            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
            {{ $attributes }} />
            
        @if($showValue)
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                <span>{{ $min }}</span>
                <span x-text="value" class="font-medium text-gray-700 dark:text-gray-300"></span>
                <span>{{ $max }}</span>
            </div>
        @endif
    </div>
        
</x-form.base.field-wrapper> 