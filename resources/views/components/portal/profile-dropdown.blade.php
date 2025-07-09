{{-- Meet2Be: Profile Dropdown Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- User profile dropdown menu --}}

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
            class="flex items-center p-1 sm:p-1.5 rounded-lg transition-colors duration-150"
            :aria-expanded="open"
            aria-haspopup="true"
            aria-label="{{ __('portal.header.user_menu') }}">
        <div class="h-7 w-7 sm:h-8 sm:w-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 flex items-center justify-center shadow-sm">
            <span class="text-xs sm:text-sm font-medium text-white">
                {{ substr(auth()->user()->full_name ?? auth()->user()->first_name ?? 'K', 0, 1) }}
            </span>
        </div>
        <span class="hidden ml-2 sm:ml-3 text-sm font-medium text-gray-700 dark:text-gray-200 lg:block">
            {{ auth()->user()->full_name ?? __('portal.header.default_user') }}
        </span>
        <i class="fa-solid fa-chevron-down hidden ml-1 text-xs text-gray-400 dark:text-gray-500 lg:block"></i>
    </button>

    {{-- Dropdown menu --}}
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-lg bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-gray-700 focus:outline-none"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="user-menu-button">
        <a href="{{ route('portal.profile.index') }}" 
           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150" 
           role="menuitem">
            <i class="fa-solid fa-user mr-3 text-gray-400 dark:text-gray-500"></i>
            {{ __('portal.profile_menu.profile') }}
        </a>
        
        <hr class="my-1 border-gray-200 dark:border-gray-700">
        
        <form method="POST" action="{{ route('site.auth.logout') }}">
            @csrf
            <button type="submit" 
                    class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150" 
                    role="menuitem">
                <i class="fa-solid fa-right-from-bracket mr-3 text-gray-400 dark:text-gray-500"></i>
                {{ __('portal.profile_menu.logout') }}
            </button>
        </form>
    </div>
</div> 