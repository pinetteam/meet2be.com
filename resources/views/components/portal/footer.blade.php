{{-- Meet2Be: Portal Footer Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Fixed footer component --}}

<footer class="fixed bottom-0 left-0 right-0 z-20 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 lg:left-64">
    <div class="px-4 py-2 lg:py-3">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm">
            {{-- Copyright --}}
            <div class="text-gray-600 dark:text-gray-400">
                <span class="hidden sm:inline">
                    {{ __('portal.footer.copyright', [
                        'year' => date('Y'),
                        'company' => config('app.name', 'Meet2Be')
                    ]) }}
                </span>
                <span class="sm:hidden">
                    © {{ date('Y') }} {{ config('app.name', 'Meet2Be') }}
                </span>
            </div>

            {{-- Right side info --}}
            <div class="flex items-center gap-2 sm:gap-4 text-gray-600 dark:text-gray-400">
                <span>{{ __('portal.footer.version', ['version' => config('app.version', '1.0.0')]) }}</span>
                <span class="hidden sm:inline">•</span>
                <span class="hidden sm:inline">
                    {{ __('portal.footer.made_with') }}
                    <i class="fa-solid fa-heart text-red-500 mx-1"></i>
                    {{ __('portal.footer.by') }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">Meet2Be</span>
                </span>
                <span class="sm:hidden">
                    <i class="fa-solid fa-heart text-red-500"></i>
                </span>
            </div>
        </div>
    </div>
</footer> 