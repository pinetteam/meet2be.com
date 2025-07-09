{{-- Meet2Be: Alpine.js Input Component with Error Handling --}}
{{-- Author: Meet2Be Development Team --}}

@props([
    'type' => 'text',
    'name',
    'label' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'model' => null,
    'errors' => 'errors',
    'clearError' => true,
    'colspan' => null
])

@php
    $wrapperClass = $colspan ? "md:col-span-{$colspan}" : '';
    $model = $model ?? "form.{$name}";
@endphp

<div class="{{ $wrapperClass }}">
    <div class="space-y-1">
        @if($label)
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ $label }}
                @if($required)
                    <span class="text-red-500 ml-0.5">*</span>
                @endif
            </label>
        @endif
        
        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            x-model="{{ $model }}"
            @if($clearError)
                @input="clearError('{{ $name }}')"
            @endif
            :class="{
                'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500': !{{ $errors }}.{{ $name }},
                'border-red-300 dark:border-red-400 focus:border-red-500 focus:ring-red-500': {{ $errors }}.{{ $name }}
            }"
            class="block w-full rounded-md shadow-sm transition-colors duration-150 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            {{ $attributes }}
        />
        
        <p x-show="{{ $errors }}.{{ $name }}" 
           x-text="{{ $errors }}.{{ $name }} ? {{ $errors }}.{{ $name }}[0] : ''" 
           class="text-sm text-red-600 dark:text-red-400"></p>
           
        @if($hint)
            <p x-show="!{{ $errors }}.{{ $name }}" 
               class="text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
        @endif
    </div>
</div> 