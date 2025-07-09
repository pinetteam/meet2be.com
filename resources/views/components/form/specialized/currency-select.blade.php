{{-- Meet2Be: Currency select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Currency selector with symbol display --}}

@props([
    'name' => 'currency_id',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'currencies' => null,
    'showSymbol' => true,
    'showCode' => true
])

@php
    $label = $label ?? __('common.labels.currency');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $currencies = $currencies ?? \App\Models\System\Currency::where('is_active', true)->orderBy('name')->get();
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
    
    @foreach($currencies as $currency)
        <option value="{{ $currency->id }}">
            {{ $currency->name }}
            @if($showCode)
                ({{ $currency->code }})
            @endif
            @if($showSymbol && $currency->symbol)
                - {{ $currency->symbol }}
            @endif
        </option>
    @endforeach
    
</x-form.select> 