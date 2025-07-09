{{-- Meet2Be: Textarea component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Multi-line text input with consistent styling --}}

@props([
    'name' => '',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 4,
    'maxlength' => null,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'resize' => 'vertical'
])

@php
    // Size classes
    $sizes = [
        'sm' => 'px-2.5 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    // Resize classes
    $resizeClasses = [
        'none' => 'resize-none',
        'vertical' => 'resize-y',
        'horizontal' => 'resize-x',
        'both' => 'resize'
    ];
    
    $resizeClass = $resizeClasses[$resize] ?? $resizeClasses['vertical'];
    
    // Generate field ID
    $fieldId = $name . '_' . uniqid();
    
    // Determine if there's an error
    $hasError = $errors->has($name);
    
    // Build classes
    $baseClasses = 'block w-full rounded-md shadow-sm transition-colors duration-150 dark:bg-gray-700 dark:text-white';
    
    // Border and focus classes
    if ($hasError) {
        $borderClasses = 'border-red-300 dark:border-red-400 focus:border-red-500 focus:ring-red-500';
    } else {
        $borderClasses = 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500';
    }
    
    // State classes
    $stateClasses = '';
    if ($disabled) {
        $stateClasses = 'bg-gray-50 dark:bg-gray-800 cursor-not-allowed opacity-60';
    } elseif ($readonly) {
        $stateClasses = 'bg-gray-50 dark:bg-gray-800';
    } else {
        $stateClasses = 'bg-white';
    }
    
    // Combine all classes
    $finalClasses = trim("$baseClasses $borderClasses $stateClasses $sizeClass $resizeClass");
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass"
    :field-id="$fieldId">
    
    <textarea
        name="{{ $name }}"
        id="{{ $fieldId }}"
        rows="{{ $rows }}"
        @if($model)
            x-model="{{ $model }}"
        @else
            {{ $attributes->whereStartsWith('wire:model') }}
        @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes->except(['class', 'wire:model', 'wire:model.defer', 'wire:model.lazy'])->merge(['class' => $finalClasses]) }}
    >{{ $model ? '' : old($name, $value) }}</textarea>
    
</x-form.base.field-wrapper> 