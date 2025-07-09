{{-- Meet2Be: Timezone select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Grouped timezone selector with search --}}

@props([
    'name',
    'label' => null,
    'selected' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'timezones' => null,
    'searchPlaceholder' => null,
    'noResultsText' => null,
    'wrapperClass' => ''
])

@php
    $placeholder = $placeholder ?? __('common.select');
    $searchPlaceholder = $searchPlaceholder ?? __('common.search');
    $noResultsText = $noResultsText ?? __('common.no_results');
    
    // Get timezones grouped by region
    if (!$timezones) {
        $allTimezones = App\Models\System\Timezone::orderBy('name')->get();
        $timezones = [];
        
        foreach ($allTimezones as $timezone) {
            $parts = explode('/', $timezone->name);
            $region = $parts[0] ?? 'Other';
            
            if (!isset($timezones[$region])) {
                $timezones[$region] = [];
            }
            
            $timezones[$region][$timezone->id] = $timezone->name . ' (UTC' . ($timezone->offset >= 0 ? '+' : '') . number_format($timezone->offset, 1) . ')';
        }
    }
    
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
@endphp

<x-form.select.searchable
    :name="$name"
    :id="$fieldId"
    :label="$label"
    :options="$timezones"
    :selected="$selected"
    :placeholder="$placeholder"
    :hint="$hint"
    :required="$required"
    :disabled="$disabled"
    :model="$model"
    :search-placeholder="$searchPlaceholder"
    :no-results-text="$noResultsText"
    :grouped="true"
    :wrapper-class="$wrapperClass"
    autocomplete="off"
/> 