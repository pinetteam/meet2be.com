{{-- Meet2Be: Main select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Default select component with optional search --}}

@props([
    'searchable' => false
])

@if($searchable)
    <x-form.select.searchable {{ $attributes }} />
@else
    <x-form.select.simple {{ $attributes }} />
@endif 