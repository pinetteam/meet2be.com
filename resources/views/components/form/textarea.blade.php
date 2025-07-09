{{-- Meet2Be: Textarea component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Multi-line text input with auto-resize option --}}

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
    'rows' => 4,
    'maxlength' => null,
    'model' => null,
    'autoResize' => false,
    'wrapperClass' => ''
])

@php
    $baseClasses = 'block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-150';
    
    if ($disabled || $readonly) {
        $baseClasses .= ' bg-gray-50 dark:bg-gray-600 cursor-not-allowed';
    }
    
    if ($errors->has($name)) {
        $baseClasses = str_replace('border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500', 'border-red-500 dark:border-red-400 focus:border-red-500 focus:ring-red-500', $baseClasses);
    }
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div @if($autoResize) x-data="textareaAutoResize()" @endif>
        <textarea 
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            @if($model) x-model="{{ $model }}" @else {{ old($name, $value) }} @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($autofocus) autofocus @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($autoResize) x-ref="textarea" @input="resize()" @endif
            {{ $attributes->merge(['class' => $baseClasses]) }}>@if(!$model){{ old($name, $value) }}@endif</textarea>
            
        @if($maxlength)
            <div class="mt-1 text-right">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    <span x-text="($refs.textarea?.value.length || 0)">0</span> / {{ $maxlength }}
                </span>
            </div>
        @endif
    </div>
    
</x-form.base.field-wrapper>

@if($autoResize)
@push('scripts')
<script>
// Meet2Be: Auto-resize textarea logic
function textareaAutoResize() {
    return {
        resize() {
            const textarea = this.$refs.textarea;
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        },
        init() {
            this.$nextTick(() => {
                this.resize();
            });
        }
    }
}
</script>
@endpush
@endif 