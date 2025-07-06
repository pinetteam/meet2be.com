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
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
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
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
                <button type="button" class="px-3 h-8 flex items-center rounded-lg relative text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/10 max-lg:hidden">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </button>
            </nav>

            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="group flex items-center rounded-lg p-1 hover:bg-zinc-800/5 dark:hover:bg-white/10">
                    <div class="size-8 rounded-full bg-amber-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name ?? 'K', 0, 1) }}</span>
                    </div>
                    <svg class="size-4 ml-2 text-zinc-400 dark:text-white/80 group-hover:text-zinc-800 dark:group-hover:text-white" viewBox="0 0 16 16" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
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
            <svg class="size-5 text-zinc-500 dark:text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
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
                                    <svg x-show="isSubmenuOpen(item.name)" class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                    <svg x-show="!isSubmenuOpen(item.name)" class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
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
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span class="flex-1 text-sm font-medium">Ayarlar</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/5 dark:hover:bg-white/[7%] border border-transparent">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
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
                openSubmenus: ['Hazırlık'],
                
                topNavigation: [
                    {
                        name: 'Dashboard',
                        href: '/portal/dashboard',
                        icon: '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'
                    },
                    {
                        name: 'Etkinlikler',
                        href: '/portal/events',
                        icon: '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>'
                    },
                    {
                        name: 'Raporlar',
                        href: '/portal/reports',
                        icon: '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" /></svg>',
                        badge: '3'
                    }
                ],
                
                sidebarNavigation: [
                    {
                        name: 'Dashboard',
                        href: '/portal/dashboard',
                        icon: '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'
                    },
                    {
                        name: 'Hazırlık',
                        icon: '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>',
                        children: [
                            { name: 'Dökümanlar', href: '/portal/documents' },
                            { name: 'Katılımcılar', href: '/portal/participants' }
                        ]
                    },
                    {
                        name: 'Etkinlik & Aktivite',
                        icon: '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>',
                        children: [
                            { name: 'Duyurular', href: '/portal/announcements' },
                            { name: 'Puan Oyunları', href: '/portal/score-games' },
                            { name: 'Anketler', href: '/portal/surveys' }
                        ]
                    },
                    {
                        name: 'Ortam',
                        icon: '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>',
                        children: [
                            { name: 'Salonlar', href: '/portal/halls' },
                            { name: 'Sanal Stantlar', href: '/portal/virtual-stands' }
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