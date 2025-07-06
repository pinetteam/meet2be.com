@extends('layouts.portal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Users') }}</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Manage system users and their permissions') }}</p>
        </div>
        <a href="{{ route('portal.user.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition-colors">
            <i class="fas fa-plus"></i>
            {{ __('Add User') }}
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6" x-data="userFilters()">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Search') }}</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="{{ __('Name, email, username...') }}"
                       class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('User Type') }}</label>
                <select name="type" id="type" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach(\App\Models\User\User::TYPES as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ __($label) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Status') }}</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach(\App\Models\User\User::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ __($label) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg font-medium text-sm transition-colors">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('portal.user.index') }}" class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 text-zinc-700 dark:text-zinc-300 rounded-lg font-medium text-sm transition-colors">
                    {{ __('Clear') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-700 border-b border-zinc-200 dark:border-zinc-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Contact') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Tenant') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Last Login') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-600">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-amber-600 flex items-center justify-center text-white font-medium">
                                        {{ substr($user->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-zinc-900 dark:text-white">{{ $user->full_name }}</div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-900 dark:text-white">{{ $user->email }}</div>
                                @if($user->phone)
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $user->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->type === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-800/20 dark:text-purple-300
                                    @elseif($user->type === 'screener') bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-300
                                    @else bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300 @endif">
                                    {{ __(\App\Models\User\User::TYPES[$user->type]) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300
                                    @elseif($user->status === 'inactive') bg-zinc-100 text-zinc-800 dark:bg-zinc-800/20 dark:text-zinc-300
                                    @else bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-300 @endif">
                                    {{ __(\App\Models\User\User::STATUSES[$user->status]) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-900 dark:text-white">{{ $user->tenant?->name ?? __('No Tenant') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->last_login_at)
                                    <div class="text-sm text-zinc-900 dark:text-white">{{ $user->last_login_at->format('d.m.Y H:i') }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->last_login_at->diffForHumans() }}</div>
                                @else
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Never') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('portal.user.show', $user) }}" 
                                       class="text-zinc-500 hover:text-amber-600 dark:text-zinc-400 dark:hover:text-amber-500" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('portal.user.edit', $user) }}" 
                                       class="text-zinc-500 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-500" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('portal.user.destroy', $user) }}" class="inline" 
                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-zinc-500 hover:text-red-600 dark:text-zinc-400 dark:hover:text-red-500" title="{{ __('Delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-zinc-500 dark:text-zinc-400">
                                    <i class="fas fa-users text-5xl mx-auto mb-4"></i>
                                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-2">{{ __('No users found') }}</h3>
                                    <p class="text-sm">{{ __('Try adjusting your search or filter criteria') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-600">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg" x-data="{ show: true }" x-show="show" x-transition>
            {{ session('success') }}
            <button @click="show = false" class="ml-4 text-green-200 hover:text-white">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
</div>

<script>
function userFilters() {
    return {
        
    }
}
</script>
@endsection 