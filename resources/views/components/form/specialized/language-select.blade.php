{{-- Meet2Be: Language select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Language selector with native names --}}

@props([
    'name' => 'language_id',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'languages' => null,
    'showNativeName' => true,
    'showCode' => false
])

@php
    $label = $label ?? __('common.labels.language');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $languages = $languages ?? \App\Models\System\Language::where('is_active', true)->orderBy('name_en')->get();
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
    
    @foreach($languages as $language)
        <option value="{{ $language->id }}">
            {{ $language->name_en }}
            @if($showNativeName && $language->name_native)
                ({{ $language->name_native }})
            @endif
            @if($showCode)
                - {{ strtoupper($language->code) }}
            @endif
        </option>
    @endforeach
    
</x-form.select> 