{{-- Meet2Be: Language select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Language selector with native name display --}}

@props([
    'name' => 'language_id',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'languages' => null,
    'showNativeName' => true,
    'wrapperClass' => ''
])

@php
    $label = $label ?? __('settings.fields.language');
    $placeholder = $placeholder ?? __('common.select');
    $languages = $languages ?? \App\Models\System\Language::where('is_active', true)->orderBy('name_en')->get();
    
    // Build options array
    $options = [];
    foreach ($languages as $language) {
        $displayName = $language->name_en;
        if ($showNativeName && $language->name_native) {
            $displayName .= ' (' . $language->name_native . ')';
        }
        $options[$language->id] = $displayName;
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
    :options="$options"
    :wrapper-class="$wrapperClass"
    {{ $attributes }}
/> 