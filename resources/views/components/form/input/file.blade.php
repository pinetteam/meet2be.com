{{-- Meet2Be: File input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- File upload input --}}

@props([
    'name',
    'label' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'accept' => null,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => ''
])

@php
    $sizeClasses = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-base'
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
        type="file"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $name }}"
        @if($model) x-model="{{ $model }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($multiple) multiple @endif
        @if($accept) accept="{{ $accept }}" @endif
        class="block w-full {{ $currentSizeClasses }} text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:{{ $currentSizeClasses }} file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600 {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
        {{ $attributes }} />
        
</x-form.base.field-wrapper> 