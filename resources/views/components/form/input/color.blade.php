{{-- Meet2Be: Color input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Color picker input --}}

@props([
    'name',
    'label' => null,
    'value' => '#000000',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => ''
])

@php
    $sizeClasses = [
        'sm' => 'h-8 w-16',
        'md' => 'h-10 w-20',
        'lg' => 'h-12 w-24'
    ];
    
    $currentSizeClasses = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <input 
        type="color"
        name="{{ $name }}"
        id="{{ $name }}"
        @if($model) x-model="{{ $model }}" @else value="{{ old($name, $value) }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="{{ $currentSizeClasses }} rounded-md border-gray-300 dark:border-gray-600 shadow-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
        {{ $attributes }} />
        
</x-form.base.field-wrapper> 