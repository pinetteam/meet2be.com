@extends('layouts.portal')

@section('title', 'Kullanıcılar')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ __('Users') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ __('Manage system users and their permissions') }}</p>
            </div>
            <a href="{{ route('portal.user.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus"></i>
                {{ __('Add User') }}
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6" x-data="userFilters()">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-zinc-400 dark:text-zinc-500"></i>
                    <input type="text" 
                           placeholder="{{ __('Search users...') }}" 
                           class="w-full pl-10 pr-4 py-3 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                           x-model="search">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div>
                <select class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                        x-model="statusFilter">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                    <option value="suspended">{{ __('Suspended') }}</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <select class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                        x-model="typeFilter">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="admin">{{ __('Admin') }}</option>
                    <option value="screener">{{ __('Screener') }}</option>
                    <option value="operator">{{ __('Operator') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <!-- Table Header (Mobile Hidden) -->
        <div class="hidden lg:block px-6 py-4 bg-zinc-50 dark:bg-zinc-700/50 border-b border-zinc-200 dark:border-zinc-600">
            <div class="grid grid-cols-12 gap-4 text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wide">
                <div class="col-span-4">{{ __('User') }}</div>
                <div class="col-span-2">{{ __('Type') }}</div>
                <div class="col-span-2">{{ __('Status') }}</div>
                <div class="col-span-2">{{ __('Last Login') }}</div>
                <div class="col-span-2 text-center">{{ __('Actions') }}</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse($users as $user)
                <!-- Desktop View -->
                <div class="hidden lg:block px-6 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors duration-150">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <!-- User Info -->
                        <div class="col-span-4 flex items-center gap-3">
                            <div class="size-10 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($user->full_name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-semibold text-zinc-900 dark:text-white truncate">
                                    {{ $user->full_name }}
                                </div>
                                <div class="text-sm text-zinc-500 dark:text-zinc-400 truncate">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="col-span-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($user->type === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                @elseif($user->type === 'screener') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 @endif">
                                {{ __(\App\Models\User\User::TYPES[$user->type]) }}
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="col-span-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($user->status === 'inactive') bg-zinc-100 text-zinc-800 dark:bg-zinc-800/30 dark:text-zinc-400
                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                {{ __(\App\Models\User\User::STATUSES[$user->status]) }}
                            </span>
                        </div>

                        <!-- Last Login -->
                        <div class="col-span-2 text-sm text-zinc-600 dark:text-zinc-400">
                            @if($user->last_activity)
                                <div>@timezoneDate($user->last_activity)</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-500">@timezoneTime($user->last_activity)</div>
                            @else
                                <span class="text-zinc-400 dark:text-zinc-500">{{ __('Never') }}</span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="col-span-2 flex items-center justify-center gap-2">
                            <a href="{{ route('portal.user.show', $user) }}" 
                               class="p-2 text-zinc-600 hover:text-amber-600 dark:text-zinc-400 dark:hover:text-amber-400 transition-colors duration-150"
                               title="{{ __('View') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('portal.user.edit', $user) }}" 
                               class="p-2 text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors duration-150"
                               title="{{ __('Edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('portal.user.destroy', $user) }}" class="inline" 
                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-zinc-600 hover:text-red-600 dark:text-zinc-400 dark:hover:text-red-400 transition-colors duration-150"
                                        title="{{ __('Delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="lg:hidden p-4 space-y-3">
                    <!-- User Header -->
                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white font-bold">
                            {{ substr($user->full_name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-zinc-900 dark:text-white truncate">
                                {{ $user->full_name }}
                            </div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400 truncate">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Details -->
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Type') }}:</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($user->type === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($user->type === 'screener') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 @endif">
                                    {{ __(\App\Models\User\User::TYPES[$user->type]) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Status') }}:</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($user->status === 'inactive') bg-zinc-100 text-zinc-800 dark:bg-zinc-800/30 dark:text-zinc-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                    {{ __(\App\Models\User\User::STATUSES[$user->status]) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Last Login') }}:</span>
                            <div class="mt-1 text-zinc-900 dark:text-white">
                                @if($user->last_activity)
                                    @timezone($user->last_activity)
                                @else
                                    <span class="text-zinc-400 dark:text-zinc-500">{{ __('Never') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Actions -->
                    <div class="flex items-center gap-3 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                        <a href="{{ route('portal.user.show', $user) }}" 
                           class="flex-1 px-4 py-2 bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg text-center font-medium text-sm hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors duration-150">
                            <i class="fas fa-eye mr-2"></i>{{ __('View') }}
                        </a>
                        <a href="{{ route('portal.user.edit', $user) }}" 
                           class="flex-1 px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-lg text-center font-medium text-sm hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors duration-150">
                            <i class="fas fa-edit mr-2"></i>{{ __('Edit') }}
                        </a>
                        <form method="POST" action="{{ route('portal.user.destroy', $user) }}" class="flex-1" 
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg font-medium text-sm hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors duration-150">
                                <i class="fas fa-trash mr-2"></i>{{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="text-zinc-400 dark:text-zinc-500 mb-4">
                        <i class="fas fa-users text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-2">{{ __('No users found') }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Try adjusting your search or filter criteria') }}</p>
                    </div>
                    <a href="{{ route('portal.user.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition-colors duration-150">
                        <i class="fas fa-plus"></i>
                        {{ __('Add First User') }}
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Showing') }} {{ $users->firstItem() }} {{ __('to') }} {{ $users->lastItem() }} {{ __('of') }} {{ $users->total() }} {{ __('results') }}
                </div>
                <div class="flex items-center gap-2">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50" 
         x-data="{ show: true }" x-show="show" x-transition>
        {{ session('success') }}
        <button @click="show = false" class="ml-4 text-green-200 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<!-- Alpine.js Component -->
<script>
function userFilters() {
    return {
        search: '{{ request('search') }}',
        statusFilter: '{{ request('status') }}',
        typeFilter: '{{ request('type') }}',
        
        init() {
            // Auto-submit form when filters change
            this.$watch('search', () => this.applyFilters());
            this.$watch('statusFilter', () => this.applyFilters());
            this.$watch('typeFilter', () => this.applyFilters());
        },
        
        applyFilters() {
            // Debounce search input
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.submitFilters();
            }, 300);
        },
        
        submitFilters() {
            const url = new URL(window.location.href);
            const params = new URLSearchParams();
            
            if (this.search) params.set('search', this.search);
            if (this.statusFilter) params.set('status', this.statusFilter);
            if (this.typeFilter) params.set('type', this.typeFilter);
            
            url.search = params.toString();
            window.location.href = url.toString();
        }
    }
}
</script>
@endsection 