{{-- Meet2Be: Time format select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Time format selector with preview --}}

@props([
    'name' => 'time_format',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'wrapperClass' => ''
])

@php
    $label = $label ?? __('settings.fields.time_format');
    $placeholder = $placeholder ?? __('common.select');
    
    // Common time formats with preview
    $now = now();
    $options = [
        'H:i' => __('settings.time_formats.24_hour') . ' (' . $now->format('H:i') . ')',
        'H:i:s' => __('settings.time_formats.24_hour_seconds') . ' (' . $now->format('H:i:s') . ')',
        'h:i A' => __('settings.time_formats.12_hour') . ' (' . $now->format('h:i A') . ')',
        'h:i:s A' => __('settings.time_formats.12_hour_seconds') . ' (' . $now->format('h:i:s A') . ')',
        'g:i A' => __('settings.time_formats.12_hour_no_leading') . ' (' . $now->format('g:i A') . ')',
        'g:i:s A' => __('settings.time_formats.12_hour_no_leading_seconds') . ' (' . $now->format('g:i:s A') . ')',
    ];
@endphp

<x-form.select
    :name="$name"
    :label="$label"
    :value="$value"
    :placeholder="$placeholder"
    :hint="$hint"
    :required="$required"
    :disabled="$disabled"
    :options="$options"
    :wrapper-class="$wrapperClass"
    {{ $attributes }}
/> 