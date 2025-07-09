{{-- Meet2Be: Country select component with flags --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Specialized country selector with flag display --}}

@props([
    'name' => 'country_id',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'countries' => null, // Optional: provide countries collection
    'showFlag' => true,
    'showPhoneCode' => false
])

@php
    $label = $label ?? __('common.labels.country');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $countries = $countries ?? \App\Models\System\Country::where('is_active', true)->orderBy('name_en')->get();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <x-form.select.searchable
        :name="$name"
        :value="$value"
        :placeholder="$placeholder"
        :required="$required"
        :disabled="$disabled"
        :model="$model"
        :size="$size">
        
        @foreach($countries as $country)
            <option value="{{ $country->id }}" 
                    data-flag="{{ $country->iso2 }}"
                    data-phone-code="{{ $country->phone_code }}">
                @if($showFlag)
                    <span class="flag-icon flag-icon-{{ strtolower($country->iso2) }}"></span>
                @endif
                {{ $country->name_en }}
                @if($showPhoneCode && $country->phone_code)
                    (+{{ $country->phone_code }})
                @endif
            </option>
        @endforeach
        
    </x-form.select.searchable>
    
</x-form.base.field-wrapper>

@push('styles')
<style>
/* Meet2Be: Custom country select styles */
.country-select-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.country-flag {
    width: 20px;
    height: 15px;
    border-radius: 2px;
    object-fit: cover;
}

.country-flag-placeholder {
    width: 20px;
    height: 15px;
    border-radius: 2px;
    background-color: #e5e7eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 600;
    color: #6b7280;
}
</style>
@endpush 