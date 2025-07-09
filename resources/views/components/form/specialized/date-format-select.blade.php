{{-- Meet2Be: Date format select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Date format selector with preview --}}

@props([
    'name' => 'date_format',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null
])

@php
    $label = $label ?? __('common.labels.date_format');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    
    // Common date formats
    $formats = [
        'Y-m-d' => __('settings.date_formats.iso8601'),
        'd/m/Y' => __('settings.date_formats.european'),
        'm/d/Y' => __('settings.date_formats.us'),
        'd.m.Y' => __('settings.date_formats.european_dot'),
        'd-m-Y' => __('settings.date_formats.european_dash'),
        'M j, Y' => __('settings.date_formats.long'),
        'F j, Y' => __('settings.date_formats.full'),
        'j M Y' => __('settings.date_formats.compact'),
        'd M Y' => __('settings.date_formats.medium'),
    ];
    
    $now = now();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint">
    
    <x-form.base.select-base
        :name="$name"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :model="$model">
        
        @foreach($formats as $format => $description)
            <option value="{{ $format }}" @if($value == $format) selected @endif>
                {{ $description }} ({{ $now->format($format) }})
            </option>
        @endforeach
        
    </x-form.base.select-base>
    
</x-form.base.field-wrapper> 