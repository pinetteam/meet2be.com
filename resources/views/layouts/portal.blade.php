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
            common: @json(__('common')),
            validation: @json(__('validation')),
            portal: @json(__('portal')),
            settings: @json(__('settings')),
            user: @json(__('user')),
            profile: @json(__('profile'))
        };
        window.locale = '{{ app()->getLocale() }}';
        @php
            $defaultDateTime = [
                'timezone' => 'UTC',
                'dateFormat' => 'Y-m-d',
                'timeFormat' => 'H:i',
                'locale' => 'en'
            ];
        @endphp
        window.datetimeFormats = @json($tenantDateTime ?? $defaultDateTime);
    </script>

    <title>@yield('title', __('portal.title')) - {{ config('app.name', 'Meet2Be') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/portal/portal.css', 'resources/js/portal/portal.js'])
</head>
<body class="h-full font-inter antialiased bg-gray-50 dark:bg-gray-900">
    {{-- Global Loading Indicator --}}
    <x-portal.loading-indicator />

    {{-- Page Loading Overlay --}}
    <x-portal.page-loading />
    
    {{-- Advanced Loading Overlay --}}
    <x-portal.loading-overlay type="spinner" :message="__('common.loading')" />

    {{-- Notification System --}}
    @include('components.portal.notification')
    
    {{-- Global Alert Container --}}
    <x-portal.alert-container />
    
    <div x-data="portalApp()" @keydown.escape="closeMobileMenu" class="flex h-full">
        {{-- Mobile menu backdrop --}}
        <x-portal.mobile-menu-backdrop />

        {{-- Sidebar --}}
        <x-portal.sidebar :navigation="[]" />

        {{-- Main content --}}
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Top header --}}
            <x-portal.header />

            {{-- Page content --}}
            <main class="flex-1 bg-gray-50 dark:bg-gray-900 overflow-hidden">
                <div class="h-full overflow-y-auto p-4 sm:p-6 lg:p-8 pb-16">
                    @yield('content')
                </div>
            </main>

            {{-- Fixed Footer --}}
            <x-portal.footer />
        </div>
    </div>
</body>
</html> 
