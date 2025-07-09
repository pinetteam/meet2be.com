{{-- Meet2Be: Radio button component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Radio button for single selection --}}

@props([
    'name',
    'label' => null,
    'value' => '',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => ''
])

@php
    $sizeClasses = [
        'sm' => 'h-3.5 w-3.5',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5'
    ];
    
    $labelSizeClasses = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-base'
    ];
    
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];
    $currentLabelSize = $labelSizeClasses[$size] ?? $labelSizeClasses['md'];
    
    $isChecked = old($name) ? old($name) == $value : $checked;
@endphp

<div class="flex items-center {{ $wrapperClass }}">
    <input 
        type="radio"
        name="{{ $name }}"
        id="{{ $name }}_{{ $value }}"
        value="{{ $value }}"
        @if($model) x-model="{{ $model }}" @elseif($isChecked) checked @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="{{ $currentSize }} border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-blue-600 dark:checked:border-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
        {{ $attributes }}>
        
    @if($label)
        <label for="{{ $name }}_{{ $value }}" class="ml-2 block {{ $currentLabelSize }} text-gray-700 dark:text-gray-300 {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
            {{ $label }}
        </label>
    @endif
</div> 