{{-- Meet2Be: Portal Header Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Top navigation header component --}}

<header class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="flex h-16 items-center justify-between px-4 sm:px-6 gap-2">
        {{-- Mobile menu button --}}
        <button @click="mobileMenuOpen = true" 
                class="lg:hidden p-2 rounded-lg transition-colors duration-150 flex-shrink-0">
            <i class="fa-solid fa-bars text-lg sm:text-xl text-gray-600 dark:text-gray-400"></i>
        </button>

        {{-- Search --}}
        <div class="flex-1 flex items-center">
            <div class="w-full max-w-md lg:max-w-xs">
                <label for="search" class="sr-only">{{ __('portal.header.search') }}</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fa-solid fa-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input id="search"
                           name="search"
                           class="block w-full rounded-lg border-0 bg-gray-50 dark:bg-gray-700 py-1.5 sm:py-2 pl-9 sm:pl-10 pr-3 text-xs sm:text-sm text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:focus:ring-blue-500"
                           placeholder="{{ __('portal.header.search_placeholder') }}"
                           type="search">
                </div>
            </div>
        </div>

        {{-- Right side buttons --}}
        <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
            {{-- Theme toggle --}}
            <button @click="toggleTheme()"
                    class="p-1.5 sm:p-2 rounded-lg transition-colors duration-150"
                    aria-label="{{ __('portal.header.toggle_theme') }}">
                <i x-show="!$store.theme.dark" class="fa-solid fa-moon text-base sm:text-lg text-gray-600 dark:text-gray-400"></i>
                <i x-show="$store.theme.dark" class="fa-solid fa-sun text-base sm:text-lg text-gray-600 dark:text-gray-400"></i>
            </button>

            {{-- Notifications --}}
            <x-portal.notification-button />

            {{-- Profile dropdown --}}
            <x-portal.profile-dropdown />
        </div>
    </div>
</header> 