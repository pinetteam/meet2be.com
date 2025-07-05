<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Meet2Be') }} - Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome Pro -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-solid.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-regular.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-light.css">

    <!-- Scripts -->
    @vite(['resources/css/portal/portal.css', 'resources/js/portal/portal.js'])

    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- FluxUI Appearance -->
    @fluxAppearance
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-indigo-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/portal" class="text-xl font-bold text-white">
                                {{ config('app.name', 'Meet2Be') }} Portal
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <span class="text-white">{{ Auth::user()->getFullNameAttribute() }}</span>
                            <form method="POST" action="{{ route('site.auth.logout') }}" class="inline">
                                @csrf
                                <flux:button type="submit" variant="ghost" size="sm" class="text-white hover:text-gray-200">
                                    <i class="fa-solid fa-sign-out-alt mr-2"></i>
                                    {{ __('portal.common.logout') }}
                                </flux:button>
                            </form>
                        @else
                            <flux:button href="{{ route('site.auth.login') }}" variant="ghost" size="sm" class="text-white hover:text-gray-200">
                                <i class="fa-solid fa-sign-in-alt mr-2"></i>
                                {{ __('portal.common.login') }}
                            </flux:button>
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

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- FluxUI Scripts -->
    @fluxScripts
</body>
</html> 