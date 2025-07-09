{{-- Meet2Be: Loading Overlay Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Advanced loading overlay with multiple states --}}

@props([
    'type' => 'spinner', // spinner, bar, dots, pulse
    'message' => null,
    'showBackdrop' => true
])

<div x-data="{ 
        message: @js($message)
     }" 
     x-show="$store.loading.isLoading" 
     x-cloak
     @loading.window="message = $event.detail?.message || message"
     x-transition:enter="transition-all ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-all ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[10000] flex items-center justify-center {{ $showBackdrop ? 'bg-gray-900/50 dark:bg-gray-950/70 backdrop-blur-sm' : '' }}">
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 m-4 max-w-sm w-full"
         x-transition:enter="transition-all ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition-all ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        
        <div class="flex flex-col items-center">
            {{-- Spinner Type --}}
            @if($type === 'spinner')
                <div class="relative">
                    <svg class="animate-spin h-12 w-12 text-blue-600 dark:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            @endif
            
            {{-- Bar Type --}}
            @if($type === 'bar')
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full animate-loading-bar"></div>
                </div>
            @endif
            
            {{-- Dots Type --}}
            @if($type === 'dots')
                <div class="flex space-x-2">
                    <div class="w-3 h-3 bg-blue-600 dark:bg-blue-500 rounded-full animate-pulse-loading"></div>
                    <div class="w-3 h-3 bg-blue-600 dark:bg-blue-500 rounded-full animate-pulse-loading" style="animation-delay: 0.2s"></div>
                    <div class="w-3 h-3 bg-blue-600 dark:bg-blue-500 rounded-full animate-pulse-loading" style="animation-delay: 0.4s"></div>
                </div>
            @endif
            
            {{-- Pulse Type --}}
            @if($type === 'pulse')
                <div class="relative">
                    <div class="w-16 h-16 bg-blue-600 dark:bg-blue-500 rounded-full animate-ping absolute"></div>
                    <div class="w-16 h-16 bg-blue-600 dark:bg-blue-500 rounded-full relative"></div>
                </div>
            @endif
            
            {{-- Loading Message --}}
            <p x-show="message || $store.loading.message" 
               x-text="message || $store.loading.message" 
               class="mt-4 text-sm text-gray-600 dark:text-gray-400 font-medium text-center"></p>
        </div>
    </div>
</div> 