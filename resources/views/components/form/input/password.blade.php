{{-- Meet2Be: Password input component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Password input with show/hide toggle --}}

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
    'autocomplete' => 'current-password',
    'minlength' => null,
    'maxlength' => null,
    'pattern' => null,
    'model' => null,
    'size' => 'md',
    'showToggle' => true,
    'wrapperClass' => ''
])

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div x-data="{ showPassword: false }" class="relative">
        <x-form.base.input-base
            :name="$name"
            x-bind:type="showPassword ? 'text' : 'password'"
            :value="$value"
            :placeholder="$placeholder"
            :required="$required"
            :disabled="$disabled"
            :readonly="$readonly"
            :autofocus="$autofocus"
            :autocomplete="$autocomplete"
            :minlength="$minlength"
            :maxlength="$maxlength"
            :pattern="$pattern"
            :model="$model"
            :size="$size"
            icon="fa-solid fa-lock"
            :input-class="$showToggle ? 'pr-10' : ''"
            {{ $attributes }} />
            
        @if($showToggle && !$disabled && !$readonly)
            <button type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    tabindex="-1">
                <i x-show="!showPassword" class="fa-solid fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}"></i>
                <i x-show="showPassword" class="fa-solid fa-eye-slash text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}"></i>
            </button>
        @endif
    </div>
    
</x-form.base.field-wrapper> 