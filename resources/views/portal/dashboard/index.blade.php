@extends('layouts.portal')

@section('content')
<div class="flex max-md:flex-col items-start">
    <!-- Sub navigation -->
    <div class="w-full md:w-[220px] pb-4 mr-10">
        <nav class="flex flex-col overflow-visible min-h-auto">
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px bg-zinc-800/[4%] dark:bg-white/[7%] text-amber-600 dark:text-amber-500">
                <span class="flex-1 text-sm font-medium">Dashboard</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/[4%] dark:hover:bg-white/[7%]">
                <span class="flex-1 text-sm font-medium">Siparişler</span>
                <span class="text-xs font-medium rounded-sm px-1 py-0.5 text-zinc-700 dark:text-zinc-200 bg-zinc-400/15 dark:bg-white/10">32</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/[4%] dark:hover:bg-white/[7%]">
                <span class="flex-1 text-sm font-medium">Katalog</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/[4%] dark:hover:bg-white/[7%]">
                <span class="flex-1 text-sm font-medium">Ödemeler</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/[4%] dark:hover:bg-white/[7%]">
                <span class="flex-1 text-sm font-medium">Müşteriler</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/[4%] dark:hover:bg-white/[7%]">
                <span class="flex-1 text-sm font-medium">Faturalama</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/[4%] dark:hover:bg-white/[7%]">
                <span class="flex-1 text-sm font-medium">Teklifler</span>
            </a>
            <a href="#" class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-800/[4%] dark:hover:bg-white/[7%]">
                <span class="flex-1 text-sm font-medium">Yapılandırma</span>
            </a>
        </nav>
    </div>

    <div class="h-px w-full md:hidden bg-zinc-800/15 dark:bg-white/20"></div>

    <!-- Main content -->
    <div class="flex-1 max-md:pt-6 self-stretch">
        <h1 class="font-medium text-zinc-800 dark:text-white text-2xl mb-2">İyi günler, {{ auth()->user()->name ?? 'Kullanıcı' }}</h1>
        <div class="text-sm text-zinc-500 dark:text-white/70 mt-2 mb-6 text-base">Bugün neler var bakalım</div>
        <div class="h-px w-full bg-zinc-800/5 dark:bg-white/10"></div>

        <!-- Stats Grid -->
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Stat Card 1 -->
            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-6">
                <dt class="flex items-center gap-2">
                    <div class="rounded-md bg-amber-600 p-2">
                        <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Aktif Etkinlikler</p>
                </dt>
                <dd class="mt-4 flex items-baseline justify-between">
                    <p class="text-3xl font-semibold text-zinc-900 dark:text-white">8</p>
                    <p class="flex items-baseline text-sm font-semibold text-green-600 dark:text-green-400">
                        <svg class="size-4 self-center" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.08-1.04l5.25-5.5a.75.75 0 0 1 1.08 0l5.25 5.5a.75.75 0 1 1-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1">12%</span>
                    </p>
                </dd>
            </div>

            <!-- Stat Card 2 -->
            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-6">
                <dt class="flex items-center gap-2">
                    <div class="rounded-md bg-amber-600 p-2">
                        <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Toplam Katılımcı</p>
                </dt>
                <dd class="mt-4 flex items-baseline justify-between">
                    <p class="text-3xl font-semibold text-zinc-900 dark:text-white">2,847</p>
                    <p class="flex items-baseline text-sm font-semibold text-green-600 dark:text-green-400">
                        <svg class="size-4 self-center" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.08-1.04l5.25-5.5a.75.75 0 0 1 1.08 0l5.25 5.5a.75.75 0 1 1-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1">32%</span>
                    </p>
                </dd>
            </div>

            <!-- Stat Card 3 -->
            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-6">
                <dt class="flex items-center gap-2">
                    <div class="rounded-md bg-amber-600 p-2">
                        <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Anketler</p>
                </dt>
                <dd class="mt-4 flex items-baseline justify-between">
                    <p class="text-3xl font-semibold text-zinc-900 dark:text-white">156</p>
                    <p class="flex items-baseline text-sm font-semibold text-zinc-500 dark:text-zinc-400">
                        <span>—</span>
                    </p>
                </dd>
            </div>

            <!-- Stat Card 4 -->
            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-6">
                <dt class="flex items-center gap-2">
                    <div class="rounded-md bg-amber-600 p-2">
                        <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Toplam Gelir</p>
                </dt>
                <dd class="mt-4 flex items-baseline justify-between">
                    <p class="text-3xl font-semibold text-zinc-900 dark:text-white">₺487K</p>
                    <p class="flex items-baseline text-sm font-semibold text-red-600 dark:text-red-400">
                        <svg class="size-4 self-center" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v10.638l3.96-4.158a.75.75 0 1 1 1.08 1.04l-5.25 5.5a.75.75 0 0 1-1.08 0l-5.25-5.5a.75.75 0 1 1 1.08-1.04l3.96 4.158V3.75A.75.75 0 0 1 10 3Z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1">4.5%</span>
                    </p>
                </dd>
            </div>
        </div>

        <!-- Activity and Events Section -->
        <div class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Recent Activities -->
            <div class="rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                <div class="p-6">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Son Aktiviteler</h2>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Platformdaki son hareketler</p>
                </div>
                <div class="border-t border-zinc-200 dark:border-zinc-700">
                    <ul class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        <li class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/20">
                                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">Yeni katılımcı kaydı</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Ahmet Yılmaz - Blockchain Summit 2024</p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">2 dk önce</p>
                                </div>
                            </div>
                        </li>
                        <li class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/20">
                                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">Anket oluşturuldu</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Memnuniyet Anketi - Teknoloji Zirvesi</p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">15 dk önce</p>
                                </div>
                            </div>
                        </li>
                        <li class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/20">
                                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">Duyuru yayınlandı</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Program değişikliği - AI Conference</p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">1 saat önce</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="bg-zinc-50 dark:bg-zinc-900/50 px-6 py-3">
                    <a href="#" class="text-sm font-medium text-amber-600 dark:text-amber-500 hover:text-amber-700 dark:hover:text-amber-400">Tümünü görüntüle</a>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                <div class="p-6">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Yaklaşan Etkinlikler</h2>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Önümüzdeki 30 gün</p>
                </div>
                <div class="border-t border-zinc-200 dark:border-zinc-700">
                    <ul class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-600 text-sm font-semibold text-white">
                                            25
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">Teknoloji Zirvesi 2024</p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">25-27 Ocak • İstanbul</p>
                                    </div>
                                </div>
                                <div class="ml-4 flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                    <svg class="mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    450
                                </div>
                            </div>
                        </li>
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-600 text-sm font-semibold text-white">
                                            02
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">Blockchain Summit</p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">2-3 Şubat • Ankara</p>
                                    </div>
                                </div>
                                <div class="ml-4 flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                    <svg class="mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    280
                                </div>
                            </div>
                        </li>
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-600 text-sm font-semibold text-white">
                                            15
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">AI Conference 2024</p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">15-16 Şubat • Online</p>
                                    </div>
                                </div>
                                <div class="ml-4 flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                    <svg class="mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    1,200
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="bg-zinc-50 dark:bg-zinc-900/50 px-6 py-3">
                    <a href="#" class="text-sm font-medium text-amber-600 dark:text-amber-500 hover:text-amber-700 dark:hover:text-amber-400">Takvime git</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 