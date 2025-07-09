{{-- Meet2Be: Timezone select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Timezone selector with UTC offset display --}}

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
    'showOffset' => true,
    'groupByRegion' => true
])

@php
    $label = $label ?? __('common.labels.timezone');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $timezones = $timezones ?? \App\Models\System\Timezone::where('is_active', true)->orderBy('offset')->get();
    
    // Group timezones by region if enabled
    $groupedTimezones = [];
    if ($groupByRegion) {
        foreach ($timezones as $timezone) {
            $parts = explode('/', $timezone->name);
            $region = $parts[0] ?? 'Other';
            $groupedTimezones[$region][] = $timezone;
        }
        ksort($groupedTimezones);
    }
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
    searchable>
    
    @if($groupByRegion)
        @foreach($groupedTimezones as $region => $regionTimezones)
            <optgroup label="{{ $region }}">
                @foreach($regionTimezones as $timezone)
                    <option value="{{ $timezone->id }}">
                        {{ $timezone->name }}
                        @if($showOffset)
                            (UTC {{ $timezone->offset >= 0 ? '+' : '' }}{{ $timezone->offset }})
                        @endif
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    @else
        @foreach($timezones as $timezone)
            <option value="{{ $timezone->id }}">
                {{ $timezone->name }}
                @if($showOffset)
                    (UTC {{ $timezone->offset >= 0 ? '+' : '' }}{{ $timezone->offset }})
                @endif
            </option>
        @endforeach
    @endif
    
</x-form.select> 