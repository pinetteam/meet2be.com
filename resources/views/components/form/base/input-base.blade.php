{{-- Meet2Be: Base input component with Atlassian design standards --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Foundation for all input types --}}

@props([
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'autocomplete' => null,
    'min' => null,
    'max' => null,
    'step' => null,
    'pattern' => null,
    'maxlength' => null,
    'model' => null, // For Alpine.js x-model
    'inputClass' => '',
    'prefix' => null,
    'suffix' => null,
    'icon' => null, // Leading icon
    'trailingIcon' => null, // Trailing icon
    'size' => 'md', // sm, md, lg
    'state' => null // success, error, warning
])

@php
    // Meet2Be: Size classes
    $sizeClasses = [
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base'
    ];
    
    // Meet2Be: State classes
    $stateClasses = [
        'default' => 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500',
        'success' => 'border-green-500 dark:border-green-400 focus:border-green-500 focus:ring-green-500',
        'error' => 'border-red-500 dark:border-red-400 focus:border-red-500 focus:ring-red-500',
        'warning' => 'border-yellow-500 dark:border-yellow-400 focus:border-yellow-500 focus:ring-yellow-500'
    ];
    
    $currentState = $state ?? ($errors->has($name) ? 'error' : 'default');
    
    // Meet2Be: Build input classes
    $baseClasses = 'block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-white transition-colors duration-150';
    $currentSizeClasses = $sizeClasses[$size] ?? $sizeClasses['md'];
    $currentStateClasses = $stateClasses[$currentState];
    
    if ($disabled || $readonly) {
        $baseClasses .= ' bg-gray-50 dark:bg-gray-600 cursor-not-allowed';
    }
    
    // Add padding for icons
    if ($icon || $prefix) {
        $baseClasses .= ' pl-10';
    }
    if ($trailingIcon || $suffix) {
        $baseClasses .= ' pr-10';
    }
    
    $finalClasses = trim("$baseClasses $currentSizeClasses $currentStateClasses $inputClass");
@endphp

<div class="relative">
    {{-- Leading icon or prefix --}}
    @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="{{ $icon }} text-gray-400 dark:text-gray-500 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}"></i>
        </div>
    @elseif($prefix)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}">{{ $prefix }}</span>
        </div>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        @if($model) x-model="{{ $model }}" @else value="{{ old($name, $value) }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($autofocus) autofocus @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($min !== null) min="{{ $min }}" @endif
        @if($max !== null) max="{{ $max }}" @endif
        @if($step !== null) step="{{ $step }}" @endif
        @if($pattern) pattern="{{ $pattern }}" @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes->merge(['class' => $finalClasses]) }}
    >
    
    {{-- Trailing icon or suffix --}}
    @if($trailingIcon)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i class="{{ $trailingIcon }} text-gray-400 dark:text-gray-500 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}"></i>
        </div>
    @elseif($suffix)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}">{{ $suffix }}</span>
        </div>
    @endif
</div> 