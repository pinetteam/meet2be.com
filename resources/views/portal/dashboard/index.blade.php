@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="card">
        <div class="card-body">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ __('dashboard.welcome', ['name' => auth()->user()->first_name]) }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('dashboard.subtitle') }}
            </p>
            
            <!-- Timezone Info -->
            @if(auth()->user()->tenant->timezone)
                <div class="mt-4 flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center">
                        <i class="fa-solid fa-globe mr-1.5 text-gray-400 dark:text-gray-500"></i>
                        <span>{{ __('dashboard.timezone') }}: {{ auth()->user()->tenant->timezone->name }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fa-solid fa-clock mr-1.5 text-gray-400 dark:text-gray-500"></i>
                        <span>{{ __('dashboard.current_time') }}: @dt(\Carbon\Carbon::now())</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.total_users') }}</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_users'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <i class="fa-solid fa-users text-xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.active_users') }}</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['active_users'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="fa-solid fa-user-check text-xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Events -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.total_events') }}</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_events'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <i class="fa-solid fa-calendar-days text-xl text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.upcoming_events') }}</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['upcoming_events'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <i class="fa-solid fa-calendar-check text-xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('dashboard.recent_users') }}</h2>
                    <a href="{{ route('portal.user.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-150">
                        {{ __('common.actions.view_all') }}
                        <i class="fa-solid fa-chevron-right text-xs ml-0.5"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recent_users ?? [] as $user)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr($user->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $user->full_name }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @relative($user->created_at)
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.no_recent_users') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('dashboard.upcoming_events') }}</h2>
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-150">
                        {{ __('common.actions.view_all') }}
                        <i class="fa-solid fa-chevron-right text-xs ml-0.5"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($upcoming_events ?? [] as $event)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $event->title }}</div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <i class="fa-solid fa-calendar-days mr-1.5 text-xs text-gray-400 dark:text-gray-500"></i>
                                    @date($event->start_date)
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full">
                                {{ $event->getTypeName() ?? 'Event' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.no_upcoming_events') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 