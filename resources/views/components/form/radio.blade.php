{{-- Meet2Be: Radio button component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Single radio button with label --}}

@props([
    'name' => '',
    'label' => null,
    'value' => '',
    'checked' => false,
    'hint' => null,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => ''
])

@php
    // Size classes
    $sizes = [
        'sm' => 'h-3.5 w-3.5',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5'
    ];
    
    $labelSizes = [
        'sm' => 'text-sm',
        'md' => 'text-sm',
        'lg' => 'text-base'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $labelSizeClass = $labelSizes[$size] ?? $labelSizes['md'];
    
    // Generate unique ID
    $radioId = $attributes->get('id') ?? ($name && $value ? $name . '_' . $value . '_' . uniqid() : 'radio_' . uniqid());
    
    // Determine if checked
    $isChecked = old($name) ? old($name) == $value : $checked;
    
    // Build classes
    $baseClasses = 'border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:checked:bg-blue-600 transition-colors duration-150';
    
    if ($disabled) {
        $baseClasses .= ' cursor-not-allowed opacity-60';
    } else {
        $baseClasses .= ' cursor-pointer';
    }
    
    // Combine all classes
    $finalClasses = trim("$baseClasses $sizeClass");
@endphp

<div class="{{ $wrapperClass }}">
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input 
                type="radio"
                name="{{ $name }}"
                id="{{ $radioId }}"
                value="{{ $value }}"
                @if($model)
                    x-model="{{ $model }}"
                @elseif($isChecked)
                    checked
                @endif
                @if($disabled) disabled @endif
                {{ $attributes->except(['class'])->merge(['class' => $finalClasses]) }}
            />
        </div>
        
        @if($label)
            <div class="ml-3">
                <label for="{{ $radioId }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $labelSizeClass }} {{ $disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                    {{ $label }}
                </label>
                
                @if($hint)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $hint }}</p>
                @endif
            </div>
        @endif
    </div>
</div> 