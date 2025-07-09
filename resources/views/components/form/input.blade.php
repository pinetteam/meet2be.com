{{-- Meet2Be: Main input component router --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Routes to appropriate input type component --}}

@props([
    'type' => 'text'
])

@switch($type)
    @case('email')
        <x-form.input.email {{ $attributes }} />
        @break
        
    @case('password')
        <x-form.input.password {{ $attributes }} />
        @break
        
    @case('number')
        <x-form.input.number {{ $attributes }} />
        @break
        
    @case('url')
        <x-form.input.url {{ $attributes }} />
        @break
        
    @case('tel')
        <x-form.input.tel {{ $attributes }} />
        @break
        
    @case('date')
        <x-form.input.date {{ $attributes }} />
        @break
        
    @case('time')
        <x-form.input.time {{ $attributes }} />
        @break
        
    @case('datetime-local')
        <x-form.input.datetime {{ $attributes }} />
        @break
        
    @case('file')
        <x-form.input.file {{ $attributes }} />
        @break
        
    @case('color')
        <x-form.input.color {{ $attributes }} />
        @break
        
    @case('range')
        <x-form.input.range {{ $attributes }} />
        @break
        
    @case('search')
        <x-form.input.search {{ $attributes }} />
        @break
        
    @default
        <x-form.input.text {{ $attributes->merge(['type' => $type]) }} />
@endswitch 