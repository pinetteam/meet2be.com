{{-- Meet2Be: Base select component with Atlassian design --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Foundation for all select components --}}

@props([
    'name',
    'id' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'model' => null,
    'size' => 'md',
    'selectClass' => '',
    'error' => false
])

@php
    // Meet2Be: Size classes
    $sizeClasses = [
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base'
    ];
    
    // Determine if there's an error
    $hasError = $error || $errors->has($name);
    
    $baseClasses = 'block w-full rounded-md shadow-sm transition-colors duration-150';
    
    // Border and focus classes based on error state
    if ($hasError) {
        $borderClasses = 'border-red-300 dark:border-red-400 focus:border-red-500 focus:ring-red-500';
    } else {
        $borderClasses = 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500';
    }
    
    $currentSizeClasses = $sizeClasses[$size] ?? $sizeClasses['md'];
    
    if ($disabled) {
        $baseClasses .= ' bg-gray-50 dark:bg-gray-600 cursor-not-allowed';
    } else {
        $baseClasses .= ' dark:bg-gray-700 dark:text-white';
    }
    
    $finalClasses = trim("$baseClasses $borderClasses $currentSizeClasses $selectClass");
    
    // Use provided ID or fallback to name
    $selectId = $id ?? $name;
@endphp

<select 
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    id="{{ $selectId }}"
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