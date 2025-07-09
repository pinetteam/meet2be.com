{{-- Meet2Be: Date format select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Date format selector with preview --}}

@props([
    'name' => 'date_format',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'wrapperClass' => ''
])

@php
    $label = $label ?? __('settings.fields.date_format');
    $placeholder = $placeholder ?? __('common.select');
    
    // Common date formats with preview
    $now = now();
    $options = [
        'Y-m-d' => __('settings.date_formats.iso8601') . ' (' . $now->format('Y-m-d') . ')',
        'd/m/Y' => __('settings.date_formats.european') . ' (' . $now->format('d/m/Y') . ')',
        'm/d/Y' => __('settings.date_formats.us') . ' (' . $now->format('m/d/Y') . ')',
        'd.m.Y' => __('settings.date_formats.european_dot') . ' (' . $now->format('d.m.Y') . ')',
        'd-m-Y' => __('settings.date_formats.european_dash') . ' (' . $now->format('d-m-Y') . ')',
        'M j, Y' => __('settings.date_formats.long') . ' (' . $now->format('M j, Y') . ')',
        'F j, Y' => __('settings.date_formats.full') . ' (' . $now->format('F j, Y') . ')',
        'j M Y' => __('settings.date_formats.compact') . ' (' . $now->format('j M Y') . ')',
        'd M Y' => __('settings.date_formats.medium') . ' (' . $now->format('d M Y') . ')',
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