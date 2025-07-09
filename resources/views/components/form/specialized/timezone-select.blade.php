{{-- Meet2Be: Timezone select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Grouped timezone selector --}}

@props([
    'name' => 'timezone_id',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'timezones' => null,
    'wrapperClass' => ''
])

@php
    $label = $label ?? __('settings.fields.timezone');
    $placeholder = $placeholder ?? __('common.select');
    
    // Get timezones grouped by region
    if (!$timezones) {
        $allTimezones = App\Models\System\Timezone::orderBy('name')->get();
        $optionGroups = [];
        
        foreach ($allTimezones as $timezone) {
            $parts = explode('/', $timezone->name);
            $region = $parts[0] ?? 'Other';
            
            // Find or create region group
            $groupIndex = null;
            foreach ($optionGroups as $index => $group) {
                if ($group['label'] === $region) {
                    $groupIndex = $index;
                    break;
                }
            }
            
            if ($groupIndex === null) {
                $optionGroups[] = [
                    'label' => $region,
                    'options' => []
                ];
                $groupIndex = count($optionGroups) - 1;
            }
            
            $displayName = $timezone->name . ' (UTC' . ($timezone->offset >= 0 ? '+' : '') . number_format($timezone->offset, 1) . ')';
            $optionGroups[$groupIndex]['options'][$timezone->id] = $displayName;
        }
    } else {
        // If timezones are provided, build option groups
        $optionGroups = [];
        foreach ($timezones as $timezone) {
            $parts = explode('/', $timezone->name);
            $region = $parts[0] ?? 'Other';
            
            $groupIndex = null;
            foreach ($optionGroups as $index => $group) {
                if ($group['label'] === $region) {
                    $groupIndex = $index;
                    break;
                }
            }
            
            if ($groupIndex === null) {
                $optionGroups[] = [
                    'label' => $region,
                    'options' => []
                ];
                $groupIndex = count($optionGroups) - 1;
            }
            
            $displayName = $timezone->name . ' (UTC' . ($timezone->offset >= 0 ? '+' : '') . number_format($timezone->offset, 1) . ')';
            $optionGroups[$groupIndex]['options'][$timezone->id] = $displayName;
        }
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
    :option-groups="$optionGroups"
    :wrapper-class="$wrapperClass"
    {{ $attributes }}
/> 