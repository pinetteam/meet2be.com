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
    'currencies' => null,
    'showSymbol' => true,
    'showCode' => true
])

@php
    $label = $label ?? __('common.labels.currency');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $currencies = $currencies ?? \App\Models\System\Currency::where('is_active', true)->orderBy('name')->get();
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
        
        @foreach($currencies as $currency)
            <option value="{{ $currency->id }}" @if($value == $currency->id) selected @endif>
                {{ $currency->name }}
                @if($showCode)
                    ({{ $currency->code }})
                @endif
                @if($showSymbol && $currency->symbol)
                    - {{ $currency->symbol }}
                @endif
            </option>
        @endforeach
        
    </x-form.base.select-base>
    
</x-form.base.field-wrapper> 