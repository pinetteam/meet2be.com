{{-- Meet2Be: Search input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Search input with clear button --}}

@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'model' => null,
    'size' => 'md',
    'icon' => 'fa-solid fa-magnifying-glass',
    'showClear' => true,
    'wrapperClass' => ''
])

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div x-data="{ search: {{ $model ? '$wire.entangle(\'' . $model . '\')' : '\'' . old($name, $value) . '\'' }} }" class="relative">
        <x-form.base.input-base
            :name="$name"
            type="search"
            x-model="search"
            :placeholder="$placeholder"
            :required="$required"
            :disabled="$disabled"
            :readonly="$readonly"
            :autofocus="$autofocus"
            :size="$size"
            :icon="$icon"
            :input-class="$showClear ? 'pr-10' : ''"
            {{ $attributes }} />
            
        @if($showClear)
            <button type="button"
                    x-show="search.length > 0"
                    @click="search = ''"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    style="display: none;">
                <i class="fa-solid fa-xmark text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}"></i>
            </button>
        @endif
    </div>
        
</x-form.base.field-wrapper> 