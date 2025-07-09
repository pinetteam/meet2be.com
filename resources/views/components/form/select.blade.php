{{-- Meet2Be: Select dropdown component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Standard select dropdown with consistent styling --}}

@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'options' => [],
    'optionGroups' => [],
    'wrapperClass' => ''
])

@php
    $selectedValue = old($name, $value);
    
    // Generate field ID
    $fieldId = $name . '_' . uniqid();
    
    // Check for errors
    $hasError = $errors->has($name);
    
    // Build classes
    $baseClasses = 'block w-full rounded-md shadow-sm transition-colors duration-150 dark:bg-gray-700 dark:text-white';
    
    if ($hasError) {
        $borderClasses = 'border-red-300 dark:border-red-400 focus:border-red-500 focus:ring-red-500';
    } else {
        $borderClasses = 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500';
    }
    
    if ($disabled) {
        $stateClasses = 'bg-gray-50 dark:bg-gray-800 cursor-not-allowed opacity-60';
    } else {
        $stateClasses = 'bg-white';
    }
    
    $finalClasses = trim("$baseClasses $borderClasses $stateClasses pl-3 pr-10 py-2 text-sm");
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass"
    :field-id="$fieldId">
    
    <div class="relative">
        <select
            name="{{ $name }}"
            id="{{ $fieldId }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class'])->merge(['class' => $finalClasses]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            
            @if(count($optionGroups) > 0)
                @foreach($optionGroups as $group)
                    <optgroup label="{{ $group['label'] }}">
                        @foreach($group['options'] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected($selectedValue == $optionValue)>
                                {{ $optionLabel }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            @else
                @foreach($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected($selectedValue == $optionValue)>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            @endif
        </select>
        
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <i class="fas fa-chevron-down text-gray-400"></i>
        </div>
    </div>
    
</x-form.base.field-wrapper> 