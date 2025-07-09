{{-- Meet2Be: Checkbox component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Single checkbox with label and consistent styling --}}

@props([
    'name' => '',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'hint' => null,
    'required' => false,
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
    $checkboxId = $attributes->get('id') ?? ($name ? $name . '_' . uniqid() : 'checkbox_' . uniqid());
    
    // Determine if checked
    $isChecked = old($name) ? old($name) == $value : $checked;
    
    // Determine if there's an error
    $hasError = $errors->has($name);
    
    // Build classes
    $baseClasses = 'rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:checked:bg-blue-600 transition-colors duration-150';
    
    if ($hasError) {
        $baseClasses = str_replace('border-gray-300 dark:border-gray-600', 'border-red-300 dark:border-red-400', $baseClasses);
        $baseClasses = str_replace('focus:border-blue-500 focus:ring-blue-500', 'focus:border-red-500 focus:ring-red-500', $baseClasses);
    }
    
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
                type="checkbox"
                name="{{ $name }}"
                id="{{ $checkboxId }}"
                value="{{ $value }}"
                @if($model)
                    x-model="{{ $model }}"
                @elseif($isChecked)
                    checked
                @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                {{ $attributes->except(['class'])->merge(['class' => $finalClasses]) }}
            />
        </div>
        
        @if($label)
            <div class="ml-3">
                <label for="{{ $checkboxId }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $labelSizeClass }} {{ $disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                    {{ $label }}
                    @if($required)
                        <span class="text-red-500 ml-0.5">*</span>
                    @endif
                </label>
                
                @if($hint)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $hint }}</p>
                @endif
            </div>
        @endif
    </div>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div> 