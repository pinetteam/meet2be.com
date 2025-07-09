{{-- Meet2Be: Reusable form input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Usage: <x-form.input name="email" label="Email" type="email" required /> --}}

@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'hint' => '',
    'prefix' => '', // For URL inputs like "https://"
    'model' => null // For Alpine.js x-model binding
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    @if($prefix)
        {{-- Input with prefix --}}
        <div class="flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 sm:text-sm">
                {{ $prefix }}
            </span>
            <input 
                type="{{ $type }}"
                id="{{ $name }}"
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                @if($model) x-model="{{ $model }}" @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                {{ $attributes->merge(['class' => 'flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white' . ($disabled || $readonly ? ' bg-gray-50 dark:bg-gray-600' : '')]) }}
            >
        </div>
    @else
        {{-- Regular input --}}
        <input 
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($model) x-model="{{ $model }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white' . ($disabled || $readonly ? ' bg-gray-50 dark:bg-gray-600' : '')]) }}
        >
    @endif
    
    @if($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div> 