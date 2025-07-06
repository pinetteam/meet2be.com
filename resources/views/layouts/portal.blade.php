<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white dark:bg-gray-950" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Meet2Be') }} - Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/portal/portal.css', 'resources/js/portal/portal.js'])
</head>
<body class="h-full">
<div x-data="portalLayout()">
    <!-- Mobile sidebar -->
    <div x-show="mobileMenuOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
        <!-- Backdrop with blur -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-gray-900/80 dark:bg-gray-950/90 backdrop-blur-sm"></div>

        <div class="fixed inset-0 flex">
            <!-- Mobile sidebar -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex w-full max-w-[280px] flex-1">

                <!-- Sidebar content -->
                <div class="flex grow flex-col overflow-y-auto bg-white dark:bg-gray-900 custom-scrollbar w-full shadow-2xl">
                    <!-- Header with logo and close button -->
                    <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex h-16 items-center justify-between px-6">
                            <span class="text-2xl font-bold text-brand">Meet2Be</span>
                            <!-- Close button -->
                            <button type="button" @click="mobileMenuOpen = false" 
                                    class="rounded-full p-2.5 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 hover:rotate-90">
                                <span class="sr-only">Menüyü kapat</span>
                                <i class="fa-regular fa-xmark text-xl text-gray-600 dark:text-gray-300"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Navigation content -->
                    <div class="flex flex-col gap-y-5 px-6 py-4 pb-6 flex-1">
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                            <li>
                                <ul role="list" class="-mx-2 space-y-1">
                                    <template x-for="item in navigation" :key="item.name">
                                        <li>
                                            <template x-if="!item.children">
                                                <a :href="item.href"
                                                   @click="if(!item.children) currentPage = item.name"
                                                   :class="[
                                                       currentPage === item.name 
                                                         ? 'bg-gray-50 dark:bg-gray-800 text-brand' 
                                                         : 'text-gray-700 dark:text-gray-300 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800',
                                                       'group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                                                   ]">
                                                    <i :class="[
                                                        item.icon,
                                                        currentPage === item.name ? 'text-brand' : 'text-gray-400 dark:text-gray-500 group-hover:text-brand',
                                                        'sidebar-icon'
                                                    ]"></i>
                                                    <span x-text="item.name"></span>
                                                </a>
                                            </template>
                                            <template x-if="item.children">
                                                <div>
                                                    <button type="button"
                                                            @click="toggleSubmenu(item.name)"
                                                            class="w-full text-left text-gray-700 dark:text-gray-300 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800 group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                                        <i :class="[
                                                            item.icon,
                                                            'text-gray-400 dark:text-gray-500 group-hover:text-brand',
                                                            'sidebar-icon'
                                                        ]"></i>
                                                        <span class="flex-1" x-text="item.name"></span>
                                                        <i :class="[
                                                            'fa-light fa-chevron-down text-xs transition-transform duration-200',
                                                            openSubmenus.includes(item.name) ? 'rotate-180' : ''
                                                        ]"></i>
                                                    </button>
                                                    <ul x-show="openSubmenus.includes(item.name)" 
                                                        x-collapse 
                                                        class="mt-1 px-2">
                                                        <template x-for="child in item.children" :key="child.name">
                                                            <li>
                                                                <a :href="child.href"
                                                                   @click="currentPage = child.name"
                                                                   :class="[
                                                                       currentPage === child.name 
                                                                         ? 'bg-gray-50 dark:bg-gray-800 text-brand' 
                                                                         : 'text-gray-700 dark:text-gray-400 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800',
                                                                       'group flex items-center gap-x-3 rounded-md py-2 pl-9 pr-2 text-sm leading-6'
                                                                   ]">
                                                                    <span x-text="child.name"></span>
                                                                </a>
                                                            </li>
                                                        </template>
                                                    </ul>
                                                </div>
                                            </template>
                                        </li>
                                    </template>
                                </ul>
                            </li>
                            <li class="mt-auto">
                                <ul role="list" class="-mx-2 space-y-1 border-t border-gray-200 dark:border-gray-700 pt-2">
                                    <li>
                                        <a href="/portal/user" 
                                           @click="currentPage = 'Kullanıcılar'"
                                           :class="[
                                               currentPage === 'Kullanıcılar' 
                                                 ? 'bg-gray-50 dark:bg-gray-800 text-brand' 
                                                 : 'text-gray-700 dark:text-gray-300 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800',
                                               'group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                                           ]">
                                            <i :class="[
                                                'fa-light fa-users',
                                                currentPage === 'Kullanıcılar' ? 'text-brand' : 'text-gray-400 dark:text-gray-500 group-hover:text-brand',
                                                'sidebar-icon'
                                            ]"></i>
                                            Kullanıcılar
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-brand">
                                            <i class="fa-light fa-gear sidebar-icon text-gray-400 dark:text-gray-500 group-hover:text-brand"></i>
                                            Ayarlar
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-6 pb-4 custom-scrollbar">
            <div class="flex h-16 shrink-0 items-center">
                <span class="text-2xl font-bold text-brand">Meet2Be</span>
            </div>
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                        <ul role="list" class="-mx-2 space-y-1">
                            <template x-for="item in navigation" :key="item.name">
                                <li>
                                    <template x-if="!item.children">
                                        <a :href="item.href"
                                           @click="if(!item.children) currentPage = item.name"
                                           :class="[
                                               currentPage === item.name 
                                                 ? 'bg-gray-50 dark:bg-gray-800 text-brand' 
                                                 : 'text-gray-700 dark:text-gray-300 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800',
                                               'group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                                           ]">
                                            <i :class="[
                                                item.icon,
                                                currentPage === item.name ? 'text-brand' : 'text-gray-400 dark:text-gray-500 group-hover:text-brand',
                                                'sidebar-icon'
                                            ]"></i>
                                            <span x-text="item.name"></span>
                                        </a>
                                    </template>
                                    <template x-if="item.children">
                                        <div>
                                            <button type="button"
                                                    @click="toggleSubmenu(item.name)"
                                                    class="w-full text-left text-gray-700 dark:text-gray-300 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800 group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                                <i :class="[
                                                    item.icon,
                                                    'text-gray-400 dark:text-gray-500 group-hover:text-brand',
                                                    'sidebar-icon'
                                                ]"></i>
                                                <span class="flex-1" x-text="item.name"></span>
                                                <i :class="[
                                                    'fa-light fa-chevron-down text-xs transition-transform duration-200',
                                                    openSubmenus.includes(item.name) ? 'rotate-180' : ''
                                                ]"></i>
                                            </button>
                                            <ul x-show="openSubmenus.includes(item.name)" 
                                                x-collapse 
                                                class="mt-1 px-2">
                                                <template x-for="child in item.children" :key="child.name">
                                                    <li>
                                                        <a :href="child.href"
                                                           @click="currentPage = child.name"
                                                           :class="[
                                                               currentPage === child.name 
                                                                 ? 'bg-gray-50 dark:bg-gray-800 text-brand' 
                                                                 : 'text-gray-700 dark:text-gray-400 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800',
                                                               'group flex items-center gap-x-3 rounded-md py-2 pl-9 pr-2 text-sm leading-6'
                                                           ]">
                                                            <span x-text="child.name"></span>
                                                        </a>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </template>
                                </li>
                            </template>
                        </ul>
                    </li>
                    <li class="mt-auto">
                        <ul role="list" class="-mx-2 space-y-1 border-t border-gray-200 dark:border-gray-700 pt-2">
                            <li>
                                <a href="/portal/user" 
                                   @click="currentPage = 'Kullanıcılar'"
                                   :class="[
                                       currentPage === 'Kullanıcılar' 
                                         ? 'bg-gray-50 dark:bg-gray-800 text-brand' 
                                         : 'text-gray-700 dark:text-gray-300 hover:text-brand hover:bg-gray-50 dark:hover:bg-gray-800',
                                       'group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                                   ]">
                                    <i :class="[
                                        'fa-light fa-users',
                                        currentPage === 'Kullanıcılar' ? 'text-brand' : 'text-gray-400 dark:text-gray-500 group-hover:text-brand',
                                        'sidebar-icon'
                                    ]"></i>
                                    Kullanıcılar
                                </a>
                            </li>
                            <li>
                                <a href="#" class="group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-brand">
                                    <i class="fa-light fa-gear sidebar-icon text-gray-400 dark:text-gray-500 group-hover:text-brand"></i>
                                    Ayarlar
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Main content area -->
    <div class="lg:pl-72">
        <!-- Top header -->
        <div class="sticky top-0 z-40 lg:mx-auto">
            <div class="flex h-16 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button type="button" @click="mobileMenuOpen = true" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden">
                    <span class="sr-only">Menüyü aç</span>
                    <i class="fa-light fa-bars text-xl"></i>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <!-- Search -->
                    <form class="relative flex flex-1" action="#" method="GET">
                        <label for="search-field" class="sr-only">Ara</label>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-light fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input id="search-field"
                               class="block h-full w-full border-0 py-0 pl-10 pr-0 text-gray-900 dark:text-gray-100 bg-transparent placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-0 sm:text-sm"
                               placeholder="Ara..."
                               type="search"
                               name="search">
                    </form>
                    
                    <!-- Right side items -->
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Dark mode toggle -->
                        <button type="button" 
                                @click="darkMode = !darkMode"
                                class="-m-2.5 p-2.5 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                            <span class="sr-only">Tema değiştir</span>
                            <i x-show="!darkMode" class="fa-light fa-moon text-xl"></i>
                            <i x-show="darkMode" class="fa-light fa-sun-bright text-xl"></i>
                        </button>

                        <!-- Notifications -->
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                            <span class="sr-only">Bildirimleri görüntüle</span>
                            <i class="fa-light fa-bell text-xl"></i>
                        </button>

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:lg:bg-gray-700" aria-hidden="true"></div>

                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" 
                                    @click="open = !open"
                                    class="-m-1.5 flex items-center p-1.5"
                                    id="user-menu-button"
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                <span class="sr-only">Kullanıcı menüsünü aç</span>
                                <div class="h-8 w-8 rounded-full bg-brand flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name ?? 'K', 0, 1) }}</span>
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm/6 font-semibold text-gray-900 dark:text-gray-100" aria-hidden="true">{{ auth()->user()->name ?? 'Kullanıcı' }}</span>
                                    <i class="fa-light fa-chevron-down ml-2 text-xs text-gray-400 dark:text-gray-500"></i>
                                </span>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open"
                                 @click.outside="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700 focus:outline-none"
                                 role="menu"
                                 aria-orientation="vertical"
                                 aria-labelledby="user-menu-button">
                                <a href="#" class="block px-3 py-1 text-sm/6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem">Profilim</a>
                                <a href="#" class="block px-3 py-1 text-sm/6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem">Ayarlar</a>
                                <hr class="my-1 border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('site.auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-3 py-1 text-sm/6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem">
                                        Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <main class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
function portalLayout() {
    return {
        mobileMenuOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        currentPage: 'Dashboard',
        openSubmenus: [],
        
        navigation: [
            {
                name: 'Dashboard',
                href: '/portal',
                icon: 'fa-light fa-house'
            },
            {
                name: 'Hazırlık',
                icon: 'fa-light fa-clipboard-list-check',
                children: [
                    { name: 'Dökümanlar', href: '/portal/documents' },
                    { name: 'Katılımcılar', href: '/portal/participants' }
                ]
            },
            {
                name: 'Etkinlik & Aktivite',
                icon: 'fa-light fa-calendar-days',
                children: [
                    { name: 'Duyurular', href: '/portal/announcements' },
                    { name: 'Puan Oyunları', href: '/portal/score-games' },
                    { name: 'Anketler', href: '/portal/surveys' }
                ]
            },
            {
                name: 'Ortam',
                icon: 'fa-light fa-building-columns',
                children: [
                    { name: 'Salonlar', href: '/portal/halls' },
                    { name: 'Sanal Stantlar', href: '/portal/virtual-stands' }
                ]
            },
            {
                name: 'Sistem Yönetimi',
                icon: 'fa-light fa-gear',
                children: [
                    { name: 'Tenant\'ler', href: '/portal/tenants' },
                    { name: 'Ülkeler', href: '/portal/countries' },
                    { name: 'Diller', href: '/portal/languages' },
                    { name: 'Para Birimleri', href: '/portal/currencies' },
                    { name: 'Saat Dilimleri', href: '/portal/timezones' }
                ]
            },
            {
                name: 'Raporlar',
                href: '/portal/reports',
                icon: 'fa-light fa-chart-mixed'
            }
        ],
        
        toggleSubmenu(name) {
            const index = this.openSubmenus.indexOf(name);
            if (index > -1) {
                this.openSubmenus.splice(index, 1);
            } else {
                this.openSubmenus.push(name);
            }
        },
        
        init() {
            // Set current page based on URL
            const path = window.location.pathname;
            
            // Check bottom menu items
            if (path === '/portal/user') {
                this.currentPage = 'Kullanıcılar';
            } else {
                // Check main navigation
                this.navigation.forEach(item => {
                    if (item.href === path) {
                        this.currentPage = item.name;
                    } else if (item.children) {
                        item.children.forEach(child => {
                            if (child.href === path) {
                                this.currentPage = child.name;
                                if (!this.openSubmenus.includes(item.name)) {
                                    this.openSubmenus.push(item.name);
                                }
                            }
                        });
                    }
                });
            }
            
            // Watch dark mode changes
            this.$watch('darkMode', value => {
                localStorage.setItem('darkMode', value);
            });
        }
    }
}
</script>
</body>
</html> 
