{{-- Meet2Be: Time format select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Time format selector with preview --}}

@props([
    'name' => 'time_format',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null
])

@php
    $label = $label ?? __('common.labels.time_format');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    
    // Common time formats
    $formats = [
        'H:i' => __('settings.time_formats.24h'),
        'H:i:s' => __('settings.time_formats.24h_seconds'),
        'g:i A' => __('settings.time_formats.12h'),
        'g:i:s A' => __('settings.time_formats.12h_seconds'),
        'h:i A' => __('settings.time_formats.12h_leading'),
        'h:i:s A' => __('settings.time_formats.12h_leading_seconds'),
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