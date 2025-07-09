{{-- Meet2Be: Reusable form select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Usage: <x-form.select name="country_id" label="Country" :options="$countries" /> --}}

@props([
    'name',
    'label',
    'options' => [],
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'hint' => '',
    'model' => null, // For Alpine.js x-model binding
    'optionValue' => 'id', // Field to use as option value
    'optionLabel' => 'name' // Field to use as option label
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <select 
        id="{{ $name }}"
        name="{{ $name }}"
        @if($model) x-model="{{ $model }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white' . ($disabled ? ' bg-gray-50 dark:bg-gray-600' : '')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $option)
            @php
                $optValue = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                $optLabel = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                $isSelected = old($name, $value) == $optValue;
            @endphp
            <option value="{{ $optValue }}" @if($isSelected) selected @endif>
                {{ $optLabel }}
            </option>
        @endforeach
    </select>
    
    @if($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div> 