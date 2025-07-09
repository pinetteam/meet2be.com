{{-- Meet2Be: Timezone select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Timezone selector grouped by region --}}

@props([
    'name' => 'timezone_id',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'timezones' => null,
    'showOffset' => true
])

@php
    $label = $label ?? __('common.labels.timezone');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $timezones = $timezones ?? \App\Models\System\Timezone::where('is_active', true)->orderBy('region')->orderBy('offset')->get();
    
    // Group timezones by region
    $groupedTimezones = $timezones->groupBy('region');
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
    :wrapper-class="$wrapperClass"
    searchable
    grouped>
    
    @foreach($groupedTimezones as $region => $regionTimezones)
        <optgroup label="{{ ucfirst($region) }}">
            @foreach($regionTimezones as $timezone)
                <option value="{{ $timezone->id }}">
                    {{ $timezone->name }}
                    @if($showOffset)
                        (UTC{{ $timezone->offset >= 0 ? '+' : '' }}{{ number_format($timezone->offset, 2) }})
                    @endif
                </option>
            @endforeach
        </optgroup>
    @endforeach
    
</x-form.select> 