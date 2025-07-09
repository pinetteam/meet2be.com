@extends('layouts.portal')

@section('title', __('dashboard.title'))

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
            {{ __('dashboard.welcome', ['name' => auth()->user()->first_name]) }}
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            {{ __('dashboard.subtitle') }}
        </p>
        
        <!-- Timezone Info -->
        @if(auth()->user()->tenant->timezone)
            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center">
                    <i class="fa-solid fa-globe mr-2 text-gray-400"></i>
                    <span>{{ __('dashboard.timezone') }}: <span class="font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->tenant->timezone->name }}</span></span>
                </div>
                <div class="flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-gray-400"></i>
                    <span>{{ __('dashboard.current_time') }}: <span class="font-medium text-gray-700 dark:text-gray-300">@dt(\Carbon\Carbon::now())</span></span>
                </div>
            </div>
        @endif
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.stats.total_users') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_users'] ?? 0) }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <i class="fa-solid fa-users text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.stats.active_users') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['active_users'] ?? 0) }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <i class="fa-solid fa-user-check text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Events -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.stats.total_events') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_events'] ?? 0) }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <i class="fa-solid fa-calendar-days text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.stats.upcoming_events') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['upcoming_events'] ?? 0) }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                        <i class="fa-solid fa-calendar-check text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('dashboard.recent_users') }}</h2>
                    <a href="{{ route('portal.user.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                        {{ __('common.actions.view_all') }}
                        <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recent_users ?? [] as $user)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                </div>
                                <div class="ml-3 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->full_name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                <span class="text-xs text-gray-500 dark:text-gray-400">@relative($user->created_at)</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fa-solid fa-user-slash text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_recent_users') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('dashboard.upcoming_events') }}</h2>
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                        {{ __('common.actions.view_all') }}
                        <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($upcoming_events ?? [] as $event)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $event->title }}</p>
                                <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fa-solid fa-calendar text-xs mr-1.5"></i>
                                    <span>@date($event->start_date)</span>
                                    @if($event->venue)
                                        <span class="mx-2">•</span>
                                        <i class="fa-solid fa-location-dot text-xs mr-1.5"></i>
                                        <span class="truncate">{{ $event->venue->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                    {{ $event->getTypeName() ?? __('common.event') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fa-solid fa-calendar-xmark text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_upcoming_events') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('dashboard.quick_actions') }}</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('portal.user.create') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-user-plus mr-2 text-gray-400"></i>
                {{ __('common.actions.create_user') }}
            </a>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-calendar-plus mr-2 text-gray-400"></i>
                {{ __('dashboard.create_new_event') }}
            </a>
            <a href="{{ route('portal.setting.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-gear mr-2 text-gray-400"></i>
                {{ __('common.settings') }}
            </a>
        </div>
    </div>
</div>
@endsection 