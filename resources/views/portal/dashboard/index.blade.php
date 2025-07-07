@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">
            {{ __('dashboard.welcome', ['name' => auth()->user()->first_name]) }}
        </h1>
        <p class="text-zinc-600 dark:text-zinc-400 mt-1">
            {{ __('dashboard.subtitle') }}
        </p>
        
        <!-- Timezone Info -->
        @if(auth()->user()->tenant->timezone)
            <div class="mt-4 flex items-center gap-4 text-sm text-zinc-600 dark:text-zinc-400">
                <div>
                    <i class="fas fa-globe mr-1"></i>
                    <span>{{ __('dashboard.timezone') }}: {{ auth()->user()->tenant->timezone->name }}</span>
                </div>
                <div>
                    <i class="fas fa-clock mr-1"></i>
                    <span>{{ __('dashboard.current_time') }}: @timezone(\Carbon\Carbon::now())</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('dashboard.stats.total_users') }}</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                    <i class="fas fa-users text-amber-600 dark:text-amber-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('dashboard.stats.active_users') }}</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['active_users']) }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <i class="fas fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Events -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('dashboard.stats.total_events') }}</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['total_events']) }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-calendar-days text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('dashboard.stats.upcoming_events') }}</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['upcoming_events']) }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <i class="fas fa-calendar-check text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('dashboard.recent_users') }}</h2>
                    <a href="{{ route('portal.user.index') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300">
                        {{ __('common.actions.view_all') }} →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($recent_users as $user)
                    <div class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($user->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white">{{ $user->full_name }}</div>
                                    <div class="text-sm text-zinc-600 dark:text-zinc-400">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                @timezoneRelative($user->created_at)
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                        {{ __('dashboard.no_recent_users') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('dashboard.upcoming_events') }}</h2>
                    <a href="#" class="text-sm text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300">
                        {{ __('common.actions.view_all') }} →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($upcoming_events as $event)
                    <div class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">{{ $event->title }}</div>
                                <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                    <i class="fas fa-calendar-days mr-1"></i>
                                    @timezoneDate($event->start_date)
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs rounded-full">
                                {{ $event->getTypeName() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                        {{ __('dashboard.no_upcoming_events') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 