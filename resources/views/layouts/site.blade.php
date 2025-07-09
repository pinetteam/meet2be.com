<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Theme Detection (MUST BE FIRST - Prevents Flash) -->
    <script>
        // Immediately apply saved theme to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme_dark');
            if (savedTheme === 'true' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <title>{{ config('app.name', 'Meet2Be') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome Pro -->
    <!-- Scripts -->
    @vite(['resources/css/site/site.css', 'resources/js/site/site.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-50 dark:bg-stone-900 text-gray-900 dark:text-stone-100">
    <div class="min-h-screen bg-gray-50 dark:bg-stone-900">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-stone-800 shadow-lg border-b border-gray-200 dark:border-stone-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ config('app.name', 'Meet2Be') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <span class="text-gray-700 dark:text-stone-300">{{ Auth::user()->full_name }}</span>
                            <form method="POST" action="{{ route('site.auth.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 dark:text-stone-200 hover:text-gray-900 dark:hover:text-white">
                                    {{ __('site.common.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('site.auth.login') }}" class="text-gray-700 dark:text-stone-200 hover:text-gray-900 dark:hover:text-white">
                                {{ __('site.common.login') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html> 