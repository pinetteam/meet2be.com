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
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'showPreview' => true
])

@php
    $label = $label ?? __('common.labels.date_format');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $currentDate = now();
    
    // Common date formats
    $formats = [
        'Y-m-d' => 'ISO 8601 (2024-03-15)',
        'd/m/Y' => 'European (15/03/2024)',
        'm/d/Y' => 'US (03/15/2024)',
        'd.m.Y' => 'Dot notation (15.03.2024)',
        'd-m-Y' => 'Dash notation (15-03-2024)',
        'F j, Y' => 'Long format (March 15, 2024)',
        'M j, Y' => 'Medium format (Mar 15, 2024)',
        'j F Y' => 'Day first long (15 March 2024)',
        'j M Y' => 'Day first medium (15 Mar 2024)',
        'd/m/y' => 'Short year (15/03/24)',
        'Y年m月d日' => 'Japanese (2024年03月15日)',
        'd F' => 'Day Month (15 March)',
        'F Y' => 'Month Year (March 2024)'
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
                - {{ $currentDate->format($format) }}
            @endif
        </option>
    @endforeach
    
</x-form.select> 