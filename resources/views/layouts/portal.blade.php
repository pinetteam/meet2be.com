<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ darkMode: true }" :class="{ 'dark': darkMode }">
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
<body class="font-sans antialiased min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100" x-data="portalLayout()">
    <!-- Header -->
    <header class="z-10 min-h-14 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700" :class="{ 'lg:pl-64': sidebarOpen }">
        <div class="mx-auto w-full h-full px-6 lg:px-8 flex items-center">
            <!-- Mobile menu button -->
            <button type="button" @click="toggleMobileSidebar()" class="relative items-center font-medium justify-center gap-2 whitespace-nowrap h-10 text-sm rounded-lg w-10 inline-flex bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white shrink-0 lg:hidden">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <!-- Logo (visible on mobile when sidebar is closed) -->
            <a href="/portal/dashboard" class="h-10 flex items-center me-4 gap-2 lg:hidden">
                <span class="text-xl font-bold text-amber-500">Meet2Be</span>
            </a>

            <!-- Top Navigation -->
            <nav class="flex items-center gap-1 py-3 -mb-px max-lg:hidden">
                <template x-for="item in topNavigation" :key="item.name">
                    <a :href="item.href" 
                       @click="currentTopNav = item.name"
                       :class="[
                           currentTopNav === item.name ? 'text-amber-600 dark:text-amber-500 after:absolute after:-bottom-3 after:inset-x-0 after:h-[2px] after:bg-amber-600 dark:after:bg-amber-500' : 'text-zinc-500 dark:text-white/80',
                           'px-3 h-8 flex items-center rounded-lg relative text-sm font-medium hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/10'
                       ]">
                        <span x-html="item.icon" class="size-5 mr-2"></span>
                        <span x-text="item.name"></span>
                        <span x-show="item.badge" x-text="item.badge" class="ml-2 text-xs font-medium rounded-sm px-1 py-0.5 text-zinc-700 dark:text-zinc-200 bg-zinc-400/15 dark:bg-white/10"></span>
                    </a>
                </template>
            </nav>

            <div class="flex-1"></div>

            <!-- Right side navigation -->
            <nav class="flex items-center gap-1 py-3 mr-4">
                <button type="button" class="px-3 h-8 flex items-center rounded-lg relative text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/10">
                    <i class="fas fa-search text-lg"></i>
                </button>
                <button type="button" class="px-3 h-8 flex items-center rounded-lg relative text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/10 max-lg:hidden">
                    <i class="fas fa-bell text-lg"></i>
                </button>
            </nav>

            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="group flex items-center rounded-lg p-1 hover:bg-zinc-800/5 dark:hover:bg-white/10">
                    <div class="size-8 rounded-full bg-amber-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name ?? 'K', 0, 1) }}</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs ml-2 text-zinc-400 dark:text-white/80 group-hover:text-zinc-800 dark:group-hover:text-white"></i>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 z-10 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-zinc-700 ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <div class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300">
                            <div class="font-medium">{{ auth()->user()->name ?? 'Kullanıcı' }}</div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                        <hr class="my-1 border-zinc-200 dark:border-zinc-600">
                        <a href="#" class="block px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-600">Profil</a>
                        <a href="#" class="block px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-600">Ayarlar</a>
                        <hr class="my-1 border-zinc-200 dark:border-zinc-600">
                        <form method="POST" action="{{ route('site.auth.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                                Çıkış Yap
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile sidebar backdrop -->
    <div x-show="mobileSidebarOpen" 
         @click="mobileSidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/10 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 flex flex-col gap-4 w-64 p-4 bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700 transform transition-transform duration-300"
         :class="{ '-translate-x-full': !mobileSidebarOpen, 'translate-x-0': mobileSidebarOpen, 'lg:translate-x-0': sidebarOpen, 'lg:-translate-x-full': !sidebarOpen }">
        
        <!-- Close button (mobile only) -->
        <button type="button" @click="mobileSidebarOpen = false" class="absolute top-4 right-4 lg:hidden">
            <i class="fas fa-times text-lg text-zinc-500 dark:text-zinc-400"></i>
        </button>

        <!-- Logo -->
        <a href="/portal/dashboard" class="h-10 flex items-center gap-2 px-2">
            <span class="text-xl font-bold text-amber-500">Meet2Be</span>
        </a>

        <!-- Navigation -->
        <nav class="flex flex-col overflow-visible min-h-auto">
            <template x-for="item in sidebarNavigation" :key="item.name">
                <div>
                    <template x-if="!item.children">
                        <a :href="item.href"
                           @click="currentSideNav = item.name"
                           :class="[
                               currentSideNav === item.name ? 'bg-white dark:bg-white/[7%] text-amber-600 dark:text-amber-500 border-zinc-200 dark:border-transparent' : 'text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/[7%] border-transparent',
                               'h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px border'
                           ]">
                            <span x-html="item.icon" class="size-4"></span>
                            <span class="flex-1 text-sm font-medium" x-text="item.name"></span>
                            <span x-show="item.badge" x-text="item.badge" class="text-xs font-medium rounded-sm px-1 py-0.5 text-zinc-700 dark:text-zinc-200 bg-zinc-400/15 dark:bg-white/10"></span>
                        </a>
                    </template>
                    <template x-if="item.children">
                        <div>
                            <button type="button"
                                    @click="toggleSubmenu(item.name)"
                                    class="w-full h-10 lg:h-8 flex items-center rounded-lg hover:bg-zinc-800/5 dark:hover:bg-white/[7%] text-zinc-500 hover:text-zinc-800 dark:text-white/80 dark:hover:text-white">
                                <div class="ps-3 pe-4">
                                    <i x-show="isSubmenuOpen(item.name)" class="fas fa-chevron-down text-xs"></i>
                                    <i x-show="!isSubmenuOpen(item.name)" class="fas fa-chevron-right text-xs"></i>
                                </div>
                                <span class="text-sm font-medium" x-text="item.name"></span>
                            </button>
                            <div x-show="isSubmenuOpen(item.name)" x-collapse class="relative space-y-[2px] ps-7">
                                <div class="absolute inset-y-[3px] w-px bg-zinc-200 dark:bg-white/30 start-0 ms-4"></div>
                                <template x-for="child in item.children" :key="child.name">
                                    <a :href="child.href"
                                       @click="currentSideNav = child.name"
                                       :class="[
                                           currentSideNav === child.name ? 'bg-white dark:bg-white/[7%] text-amber-600 dark:text-amber-500 border-zinc-200 dark:border-transparent' : 'text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/[7%] border-transparent',
                                           'h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px border'
                                       ]">
                                        <span class="text-sm font-medium" x-text="child.name"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </nav>

        <div class="flex-1"></div>

        <!-- Bottom navigation -->
        <nav class="flex flex-col overflow-visible min-h-auto">
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/[7%] border border-transparent">
                <i class="fas fa-cog"></i>
                <span class="flex-1 text-sm font-medium">Ayarlar</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/[7%] border border-transparent">
                <i class="fas fa-question-circle"></i>
                <span class="flex-1 text-sm font-medium">Yardım</span>
            </a>
        </nav>
    </div>

    <!-- Main content -->
    <main :class="{ 'lg:pl-64': sidebarOpen }">
        <div class="p-6 lg:p-8">
            @yield('content')
        </div>
    </main>

    <script>
        function portalLayout() {
            return {
                sidebarOpen: true,
                mobileSidebarOpen: false,
                currentTopNav: 'Dashboard',
                currentSideNav: 'Dashboard',
                openSubmenus: ['Kullanıcı Yönetimi', 'Hazırlık'],
                
                topNavigation: [
                    {
                        name: 'Dashboard',
                        href: '/portal',
                        icon: '<i class="fas fa-home"></i>'
                    },
                    {
                        name: 'Etkinlikler',
                        href: '/portal/events',
                        icon: '<i class="fas fa-calendar-alt"></i>'
                    },
                    {
                        name: 'Raporlar',
                        href: '/portal/reports',
                        icon: '<i class="fas fa-chart-pie"></i>',
                        badge: '3'
                    }
                ],
                
                sidebarNavigation: [
                    {
                        name: 'Dashboard',
                        href: '/portal',
                        icon: '<i class="fas fa-home"></i>'
                    },
                    {
                        name: 'Kullanıcı Yönetimi',
                        icon: '<i class="fas fa-users-cog"></i>',
                        children: [
                            { name: 'Kullanıcılar', href: '/portal/user' },
                            { name: 'Roller', href: '/portal/roles' },
                            { name: 'İzinler', href: '/portal/permissions' }
                        ]
                    },
                    {
                        name: 'Hazırlık',
                        icon: '<i class="fas fa-clipboard-list"></i>',
                        children: [
                            { name: 'Dökümanlar', href: '/portal/documents' },
                            { name: 'Katılımcılar', href: '/portal/participants' }
                        ]
                    },
                    {
                        name: 'Etkinlik & Aktivite',
                        icon: '<i class="fas fa-calendar-check"></i>',
                        children: [
                            { name: 'Duyurular', href: '/portal/announcements' },
                            { name: 'Puan Oyunları', href: '/portal/score-games' },
                            { name: 'Anketler', href: '/portal/surveys' }
                        ]
                    },
                    {
                        name: 'Ortam',
                        icon: '<i class="fas fa-building"></i>',
                        children: [
                            { name: 'Salonlar', href: '/portal/halls' },
                            { name: 'Sanal Stantlar', href: '/portal/virtual-stands' }
                        ]
                    },
                    {
                        name: 'Sistem Yönetimi',
                        icon: '<i class="fas fa-cogs"></i>',
                        children: [
                            { name: 'Tenant\'ler', href: '/portal/tenants' },
                            { name: 'Ülkeler', href: '/portal/countries' },
                            { name: 'Diller', href: '/portal/languages' },
                            { name: 'Para Birimleri', href: '/portal/currencies' },
                            { name: 'Saat Dilimleri', href: '/portal/timezones' }
                        ]
                    }
                ],
                
                toggleMobileSidebar() {
                    this.mobileSidebarOpen = !this.mobileSidebarOpen;
                },
                
                toggleSubmenu(name) {
                    const index = this.openSubmenus.indexOf(name);
                    if (index > -1) {
                        this.openSubmenus.splice(index, 1);
                    } else {
                        this.openSubmenus.push(name);
                    }
                },
                
                isSubmenuOpen(name) {
                    return this.openSubmenus.includes(name);
                }
            }
        }
    </script>
</body>
</html> 