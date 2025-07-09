{{-- Meet2Be: Loading Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Atlassian-style loading indicators --}}

@props([
    'type' => 'spinner', // spinner, skeleton, dots, bar
    'size' => 'md', // sm, md, lg
    'color' => 'blue', // blue, gray, white
    'text' => null,
    'overlay' => false,
    'fullscreen' => false
])

@php
    $sizeClasses = [
        'spinner' => [
            'sm' => 'h-4 w-4',
            'md' => 'h-8 w-8',
            'lg' => 'h-12 w-12'
        ],
        'dots' => [
            'sm' => 'space-x-1',
            'md' => 'space-x-2',
            'lg' => 'space-x-3'
        ]
    ];
    
    $colorClasses = [
        'blue' => 'text-blue-600 dark:text-blue-500',
        'gray' => 'text-gray-600 dark:text-gray-400',
        'white' => 'text-white'
    ];
    
    $dotSizes = [
        'sm' => 'h-1 w-1',
        'md' => 'h-2 w-2',
        'lg' => 'h-3 w-3'
    ];
@endphp

@if($fullscreen)
    <div class="fixed inset-0 z-50 bg-white dark:bg-gray-900 flex items-center justify-center">
        <div class="text-center">
            @include('components.ui.loading', ['type' => $type, 'size' => $size, 'color' => $color, 'text' => $text, 'fullscreen' => false])
        </div>
    </div>
@elseif($overlay)
    <div class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm flex items-center justify-center z-10 rounded-lg">
        @include('components.ui.loading', ['type' => $type, 'size' => $size, 'color' => $color, 'text' => $text, 'overlay' => false])
    </div>
@else
    <div class="inline-flex flex-col items-center justify-center">
        @if($type === 'spinner')
            <svg class="animate-spin {{ $sizeClasses['spinner'][$size] }} {{ $colorClasses[$color] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($type === 'dots')
            <div class="flex {{ $sizeClasses['dots'][$size] }}">
                <div class="{{ $dotSizes[$size] }} bg-current rounded-full animate-bounce {{ $colorClasses[$color] }}" style="animation-delay: 0ms"></div>
                <div class="{{ $dotSizes[$size] }} bg-current rounded-full animate-bounce {{ $colorClasses[$color] }}" style="animation-delay: 150ms"></div>
                <div class="{{ $dotSizes[$size] }} bg-current rounded-full animate-bounce {{ $colorClasses[$color] }}" style="animation-delay: 300ms"></div>
            </div>
        @elseif($type === 'skeleton')
            <div class="w-full space-y-3">
                <div class="skeleton h-4 w-3/4"></div>
                <div class="skeleton h-4 w-full"></div>
                <div class="skeleton h-4 w-5/6"></div>
            </div>
        @elseif($type === 'bar')
            <div class="w-full h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full bg-current {{ $colorClasses[$color] }} animate-loading-bar"></div>
            </div>
        @endif
        
        @if($text)
            <p class="mt-2 text-sm {{ $colorClasses[$color] }}">{{ $text }}</p>
        @endif
    </div>
@endif 