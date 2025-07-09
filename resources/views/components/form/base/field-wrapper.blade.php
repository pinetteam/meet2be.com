{{-- Meet2Be: Base field wrapper component for consistent form styling --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Provides label, hint, error handling for all form fields --}}

@props([
    'name',
    'label' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'labelClass' => '',
    'wrapperClass' => ''
])

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ $labelClass }}">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    {{ $slot }}
    
    @if($hint && !$errors->has($name))
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
    
    @if($error && !$errors->has($name))
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div> 