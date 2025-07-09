{{-- Meet2Be: Base select component with Atlassian design --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Foundation for all select components --}}

@props([
    'name',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'model' => null,
    'size' => 'md',
    'selectClass' => ''
])

@php
    // Meet2Be: Size classes
    $sizeClasses = [
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base'
    ];
    
    $baseClasses = 'block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-150';
    $currentSizeClasses = $sizeClasses[$size] ?? $sizeClasses['md'];
    
    if ($disabled) {
        $baseClasses .= ' bg-gray-50 dark:bg-gray-600 cursor-not-allowed';
    }
    
    $finalClasses = trim("$baseClasses $currentSizeClasses $selectClass");
@endphp

<select 
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    id="{{ $name }}"
    @if($model) x-model="{{ $model }}" @endif
    @if($required) required @endif
    @if($disabled) disabled @endif
    @if($multiple) multiple @endif
    {{ $attributes->merge(['class' => $finalClasses]) }}>
    
    @if($placeholder && !$multiple)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    {{ $slot }}
</select> 