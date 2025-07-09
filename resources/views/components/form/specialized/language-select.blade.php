{{-- Meet2Be: Language select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Language selector with native name display --}}

@props([
    'name' => 'language_id',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'languages' => null,
    'showNativeName' => true
])

@php
    $label = $label ?? __('common.labels.language');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $languages = $languages ?? \App\Models\System\Language::where('is_active', true)->orderBy('name_en')->get();
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
        
        @foreach($languages as $language)
            <option value="{{ $language->id }}" @if($value == $language->id) selected @endif>
                {{ $language->name_en }}
                @if($showNativeName && $language->name_native)
                    ({{ $language->name_native }})
                @endif
            </option>
        @endforeach
        
    </x-form.base.select-base>
    
</x-form.base.field-wrapper> 