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
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'showPreview' => true
])

@php
    $label = $label ?? __('common.labels.time_format');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $currentTime = now();
    
    // Common time formats
    $formats = [
        'H:i' => '24-hour (14:30)',
        'H:i:s' => '24-hour with seconds (14:30:45)',
        'g:i A' => '12-hour (2:30 PM)',
        'g:i:s A' => '12-hour with seconds (2:30:45 PM)',
        'h:i A' => '12-hour with leading zero (02:30 PM)',
        'h:i:s A' => '12-hour with seconds and leading zero (02:30:45 PM)',
        'G:i' => '24-hour no leading zero (14:30)',
        'H\hi' => 'French format (14h30)',
        'H:i \U\h\r' => 'German format (14:30 Uhr)'
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
    :model="$model"
    :size="$size"
    :wrapper-class="$wrapperClass">
    
    @foreach($formats as $format => $description)
        <option value="{{ $format }}">
            {{ $description }}
            @if($showPreview)
                - {{ $currentTime->format($format) }}
            @endif
        </option>
    @endforeach
    
</x-form.select> 