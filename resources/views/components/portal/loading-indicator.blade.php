{{-- Meet2Be: Loading Indicator Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Global loading indicator bar (Atlassian Style) --}}

<div x-data="{ loading: false }" 
     x-show="loading" 
     x-cloak
     @loading.window="loading = true"
     @loaded.window="loading = false"
     x-transition:enter="transition-opacity ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-0 left-0 right-0 z-[10000] bg-gray-200/50 dark:bg-gray-700/50 backdrop-blur-sm">
    <div class="h-1 bg-gray-200 dark:bg-gray-700">
        <div class="h-full bg-blue-600 dark:bg-blue-500 animate-loading-bar"></div>
    </div>
</div> 