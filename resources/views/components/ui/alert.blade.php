{{-- Meet2Be: Alert component with Atlassian design --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Global alert component for success, error, warning, and info messages --}}

@props([
    'type' => 'info',
    'title' => null,
    'message' => null,
    'dismissible' => true,
    'icon' => null,
    'list' => []
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'border' => 'border-green-200 dark:border-green-800',
            'text' => 'text-green-800 dark:text-green-200',
            'icon' => $icon ?? 'fa-check-circle',
            'iconColor' => 'text-green-400'
        ],
        'error' => [
            'bg' => 'bg-red-50 dark:bg-red-900/20',
            'border' => 'border-red-200 dark:border-red-800',
            'text' => 'text-red-800 dark:text-red-200',
            'icon' => $icon ?? 'fa-exclamation-circle',
            'iconColor' => 'text-red-400'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
            'border' => 'border-yellow-200 dark:border-yellow-800',
            'text' => 'text-yellow-800 dark:text-yellow-200',
            'icon' => $icon ?? 'fa-exclamation-triangle',
            'iconColor' => 'text-yellow-400'
        ],
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/20',
            'border' => 'border-blue-200 dark:border-blue-800',
            'text' => 'text-blue-800 dark:text-blue-200',
            'icon' => $icon ?? 'fa-info-circle',
            'iconColor' => 'text-blue-400'
        ]
    ];
    
    $config = $types[$type] ?? $types['info'];
@endphp

<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform opacity-0 scale-95"
    x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    class="{{ $config['bg'] }} {{ $config['border'] }} border rounded-lg p-4 mb-4"
    {{ $attributes }}
>
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas {{ $config['icon'] }} {{ $config['iconColor'] }}"></i>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $config['text'] }}">
                    {{ $title }}
                </h3>
            @endif
            
            @if($message)
                <div class="@if($title) mt-2 @endif text-sm {{ $config['text'] }}">
                    {{ $message }}
                </div>
            @endif
            
            @if(count($list) > 0)
                <ul class="@if($title || $message) mt-2 @endif text-sm {{ $config['text'] }} list-disc pl-5 space-y-1">
                    @foreach($list as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endif
            
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button 
                    @click="show = false" 
                    type="button"
                    class="{{ $config['text'] }} hover:opacity-75 focus:outline-none transition-opacity"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
</div> 