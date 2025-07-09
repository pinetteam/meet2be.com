{{-- Meet2Be: Page Loading Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Initial page loading overlay --}}

<div x-data="{ pageLoading: true }" 
     x-init="setTimeout(() => pageLoading = false, 300)"
     x-show="pageLoading"
     x-transition:leave="transition-opacity ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] bg-white/95 dark:bg-gray-900/95 backdrop-blur-md flex items-center justify-center">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
            <svg class="animate-spin h-12 w-12 text-blue-600 dark:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ __('common.loading') }}</p>
    </div>
</div> 