{{-- Meet2Be: Base input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Foundation for all input types --}}

@props([
    'name' => '',
    'id' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'maxlength' => null,
    'pattern' => null,
    'model' => null,
    'size' => 'md',
    'icon' => null,
    'prefix' => null,
    'suffix' => null,
    'inputClass' => '',
    'error' => false
])

@php
    $sizes = [
        'sm' => 'px-2.5 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $hasPrefix = $prefix || $icon;
    
    // Use provided ID or generate one
    $inputId = $id ?? ($name ? $name . '_' . uniqid() : 'input_' . uniqid());
    
    // Determine if there's an error
    $hasError = $error || $errors->has($name) || ($attributes->has(':error') || $attributes->has('::error'));
    
    // Build base classes
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
    
    // Prefix/suffix padding
    $paddingClasses = $sizeClass;
    if ($hasPrefix) {
        $paddingClasses = preg_replace('/\bpl-\S+/', 'pl-10', $paddingClasses);
    }
    if ($suffix) {
        $paddingClasses = preg_replace('/\bpr-\S+/', 'pr-10', $paddingClasses);
    }
    
    // Combine all classes
    $finalClasses = trim("$baseClasses $borderClasses $stateClasses $paddingClasses $inputClass");
@endphp

<div class="relative">
    @if($hasPrefix)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            @if($icon)
                <i class="{{ $icon }} text-gray-400 {{ $size === 'sm' ? 'text-sm' : '' }}"></i>
            @else
                <span class="text-gray-500 dark:text-gray-400 {{ $size === 'sm' ? 'text-sm' : 'sm:text-sm' }}">
                    {{ $prefix }}
                </span>
            @endif
        </div>
    @endif
    
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $inputId }}"
        @if($model)
            x-model="{{ $model }}"
        @else
            value="{{ old($name, $value) }}"
        @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($autofocus) autofocus @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($pattern) pattern="{{ $pattern }}" @endif
        @if($attributes->has(':error') || $attributes->has('::error'))
            :class="{
                '{{ $finalClasses }}': !{{ $attributes->get(':error') ?? $attributes->get('::error') }},
                '{{ str_replace($borderClasses, 'border-red-300 dark:border-red-400 focus:border-red-500 focus:ring-red-500', $finalClasses) }}': {{ $attributes->get(':error') ?? $attributes->get('::error') }}
            }"
        @else
            class="{{ $finalClasses }}"
        @endif
        {{ $attributes->except(['class', ':error', '::error']) }}
    />
    
    @if($suffix)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 {{ $size === 'sm' ? 'text-sm' : 'sm:text-sm' }}">
                {{ $suffix }}
            </span>
        </div>
    @endif
</div> 