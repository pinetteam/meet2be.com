<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Current Locale -->
    <meta name="current-locale" content="{{ app()->getLocale() }}">

    <!-- Tenant Date/Time Formats -->
    @auth
        @if(auth()->user()->tenant)
            <meta name="tenant-date-format" content="{{ auth()->user()->tenant->date_format }}">
            <meta name="tenant-time-format" content="{{ auth()->user()->tenant->time_format }}">
            <meta name="tenant-timezone" content="{{ auth()->user()->tenant->timezone?->name ?? 'UTC' }}">
        @endif
    @endauth

    <!-- Tenant DateTime Settings -->
    @auth
        @if(auth()->user()->tenant)
            @php
                $tenantDateTime = [
                    'timezone' => auth()->user()->tenant->timezone?->name ?? config('app.timezone'),
                    'dateFormat' => auth()->user()->tenant->date_format ?? 'Y-m-d',
                    'timeFormat' => auth()->user()->tenant->time_format ?? 'H:i',
                    'locale' => auth()->user()->tenant->language?->iso_639_1 ?? app()->getLocale()
                ];
            @endphp
            <meta name="tenant-datetime" content='{{ json_encode($tenantDateTime) }}'>
        @endif
    @endauth

    <!-- Translations for JavaScript -->
    <script>
        window.translations = {
            navigation: @json(__('portal.navigation')),
            header: @json(__('portal.header')),
            profile_menu: @json(__('portal.profile_menu')),
            footer: @json(__('portal.footer')),
            general: @json(__('portal.general'))
        };
        window.locale = '{{ app()->getLocale() }}';
    </script>

    <title>@yield('title', __('portal.title')) - {{ config('app.name', 'Meet2Be') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/portal/portal.css', 'resources/js/portal/portal.js'])
</head>
<body class="h-full font-inter antialiased bg-gray-50 dark:bg-gray-900">
    <!-- Notification System -->
    @include('components.portal.notification')
    
    <div x-data="portalApp()" @keydown.escape="closeMobileMenu" class="flex h-full">
        <!-- Mobile menu backdrop -->
        <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 z-40 bg-gray-600/75 dark:bg-gray-900/75 backdrop-blur-sm lg:hidden"
             aria-hidden="true"></div>

    <!-- Sidebar -->
        <aside :class="{'translate-x-0': mobileMenuOpen, '-translate-x-full': !mobileMenuOpen}"
               class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto">

        <!-- Logo -->
            <div class="flex h-16 items-center justify-between px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="/portal" class="flex items-center space-x-2">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-sm">M2B</span>
                    </div>
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">Meet2Be</span>
                </a>
                <button @click="mobileMenuOpen = false" class="lg:hidden p-2 rounded-lg transition-colors duration-150">
                    <i class="fa-solid fa-xmark text-lg text-gray-500 dark:text-gray-400"></i>
                </button>
            </div>

        <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto">
                <div class="py-4">
                    <div class="px-3">
                        <template x-for="(item, index) in navigation" :key="index">
                <div>
                                <!-- Single item without children -->
                    <template x-if="!item.children">
                        <a :href="item.href"
                                       @click="currentPage = item.name"
                                       :class="{
                                           'sidebar-nav-item': true,
                                           'active': currentPage === item.name,
                                           'text-gray-700 dark:text-gray-300': currentPage !== item.name,
                                           'text-blue-700 dark:text-blue-200': currentPage === item.name
                                       }">
                                        <i :class="[item.icon, currentPage === item.name ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500']"
                                           class="mr-3 text-base w-5 text-center"></i>
                                        <span x-text="item.label"></span>
                        </a>
                    </template>

                                <!-- Item with children -->
                    <template x-if="item.children">
                        <div>
                                        <button @click="toggleSubmenu(item.name)"
                                                :class="{
                                                    'sidebar-nav-item w-full justify-between': true,
                                                    'active': isSubmenuOpen(item.name) || hasActiveChild(item),
                                                    'text-gray-700 dark:text-gray-300': !(isSubmenuOpen(item.name) || hasActiveChild(item)),
                                                    'text-gray-900 dark:text-white': isSubmenuOpen(item.name) || hasActiveChild(item)
                                                }">
                                            <div class="flex items-center">
                                                <i :class="[item.icon, isSubmenuOpen(item.name) || hasActiveChild(item) ? 'text-gray-600 dark:text-gray-400' : 'text-gray-400 dark:text-gray-500']"
                                                   class="mr-3 text-base w-5 text-center"></i>
                                                <span x-text="item.label"></span>
                                </div>
                                            <i :class="{'rotate-90': isSubmenuOpen(item.name)}"
                                               class="fa-solid fa-chevron-right text-xs text-gray-400 dark:text-gray-500 transition-transform duration-500 ease-in-out"></i>
                            </button>
                                        
                                        <div x-show="isSubmenuOpen(item.name)"
                                             x-transition:enter="transition ease-out duration-500"
                                             x-transition:enter-start="opacity-0 max-h-0"
                                             x-transition:enter-end="opacity-100 max-h-96"
                                             x-transition:leave="transition ease-in duration-300"
                                             x-transition:leave-start="opacity-100 max-h-96"
                                             x-transition:leave-end="opacity-0 max-h-0"
                                             class="overflow-hidden">
                                            <div class="py-1">
                                                <template x-for="(child, childIndex) in item.children" :key="childIndex">
                                    <a :href="child.href"
                                                       @click="currentPage = child.name"
                                                       :class="{
                                                           'sidebar-submenu-item': true,
                                                           'active': currentPage === child.name,
                                                           'text-gray-600 dark:text-gray-400': currentPage !== child.name,
                                                           'text-blue-700 dark:text-blue-200': currentPage === child.name
                                                       }">
                                                        <i :class="[child.icon || 'fa-solid fa-circle', currentPage === child.name ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500']"
                                                           class="mr-3 w-4 text-center"
                                                           :style="!child.icon ? 'font-size: 6px;' : ''"></i>
                                                        <span x-text="child.label"></span>
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Bottom navigation items -->
                    <div class="mt-auto pt-4 pb-2">
                        <div class="px-3 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-1">
                            <!-- Users -->
                            <a href="/portal/user" 
                               @click="currentPage = 'users'"
                               :class="{
                                   'sidebar-nav-item': true,
                                   'active': window.location.pathname.includes('/portal/user'),
                                   'text-gray-700 dark:text-gray-300': !window.location.pathname.includes('/portal/user'),
                                   'text-blue-700 dark:text-blue-200': window.location.pathname.includes('/portal/user')
                               }">
                                <i :class="window.location.pathname.includes('/portal/user') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500'" 
                                   class="fa-solid fa-users mr-3 text-base w-5 text-center"></i>
                                {{ __('portal.navigation.users') }}
                            </a>
                            
                            <!-- Settings -->
                            <a href="{{ route('portal.setting.index') }}" 
                               @click="currentPage = 'settings'"
                               :class="{
                                   'sidebar-nav-item': true,
                                   'active': window.location.pathname.includes('/portal/setting'),
                                   'text-gray-700 dark:text-gray-300': !window.location.pathname.includes('/portal/setting'),
                                   'text-blue-700 dark:text-blue-200': window.location.pathname.includes('/portal/setting')
                               }">
                                <i :class="window.location.pathname.includes('/portal/setting') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500'" 
                                   class="fa-solid fa-gear mr-3 text-base w-5 text-center"></i>
                                {{ __('portal.navigation.settings') }}
                            </a>
                        </div>
                    </div>
                </div>
        </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Top header -->
            <header class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6 gap-2">
                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = true" 
                            class="lg:hidden p-2 rounded-lg transition-colors duration-150 flex-shrink-0">
                        <i class="fa-solid fa-bars text-lg sm:text-xl text-gray-600 dark:text-gray-400"></i>
                    </button>

                    <!-- Search -->
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

                    <!-- Right side buttons -->
                    <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                        <!-- Theme toggle -->
                        <button @click="toggleTheme()"
                                class="p-1.5 sm:p-2 rounded-lg transition-colors duration-150"
                                aria-label="{{ __('portal.header.toggle_theme') }}">
                            <i x-show="!darkMode" class="fa-solid fa-moon text-base sm:text-lg text-gray-600 dark:text-gray-400"></i>
                            <i x-show="darkMode" class="fa-solid fa-sun text-base sm:text-lg text-gray-600 dark:text-gray-400"></i>
                        </button>

                        <!-- Notifications -->
                        <button class="relative p-1.5 sm:p-2 rounded-lg transition-colors duration-150"
                                aria-label="{{ __('portal.header.notifications') }}">
                            <i class="fa-solid fa-bell text-base sm:text-lg text-gray-600 dark:text-gray-400"></i>
                            <span class="absolute top-1 right-1 sm:top-1.5 sm:right-1.5 h-2 w-2 rounded-full bg-red-500 dark:bg-red-400 ring-2 ring-white dark:ring-gray-800"></span>
                        </button>

                        <!-- Profile dropdown -->
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

                            <!-- Dropdown menu -->
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
                    </div>
    </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 bg-gray-50 dark:bg-gray-900 overflow-hidden">
                <div class="h-full overflow-y-auto p-4 sm:p-6 lg:p-8 pb-16">
                    @yield('content')
                </div>
            </main>

            <!-- Fixed Footer -->
            <footer class="fixed bottom-0 left-0 right-0 z-20 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 lg:left-64">
                <div class="px-4 py-2 lg:py-3">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm">
                        <!-- Copyright -->
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

                        <!-- Right side info -->
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
        </div>
    </div>
</body>
</html> 
