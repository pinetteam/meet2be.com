@extends('layouts.portal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('User Details') }}</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('View user information') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('portal.user.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Users') }}
            </a>
            <a href="{{ route('portal.user.edit', $user) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-edit"></i>
                {{ __('Edit User') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="size-24 mx-auto rounded-full bg-amber-600 flex items-center justify-center text-white text-2xl font-bold mb-4">
                        {{ substr($user->full_name, 0, 1) }}
                    </div>
                    
                    <!-- Basic Info -->
                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $user->full_name }}</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">{{ $user->username }}</p>
                    
                    <!-- Status & Type Badges -->
                    <div class="flex items-center justify-center gap-2 mb-6">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300
                            @elseif($user->status === 'inactive') bg-zinc-100 text-zinc-800 dark:bg-zinc-800/20 dark:text-zinc-300
                            @else bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-300 @endif">
                            {{ __(\App\Models\User\User::STATUSES[$user->status]) }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->type === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-800/20 dark:text-purple-300
                            @elseif($user->type === 'screener') bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-300
                            @else bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300 @endif">
                            {{ __(\App\Models\User\User::TYPES[$user->type]) }}
                        </span>
                    </div>

                    <!-- Quick Info -->
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Email') }}:</span>
                            <span class="text-zinc-900 dark:text-white">{{ $user->email }}</span>
                        </div>
                        @if($user->phone)
                            <div class="flex items-center justify-between">
                                <span class="text-zinc-500 dark:text-zinc-400">{{ __('Phone') }}:</span>
                                <span class="text-zinc-900 dark:text-white">{{ $user->phone }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Tenant') }}:</span>
                            <span class="text-zinc-900 dark:text-white">{{ $user->tenant?->name ?? __('No Tenant') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Account Information -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Account Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('First Name') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ $user->first_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Last Name') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ $user->last_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Username') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ $user->username }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Email') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ $user->email }}</p>
                    </div>
                    @if($user->phone)
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Phone') }}</label>
                            <p class="text-zinc-900 dark:text-white">{{ $user->phone }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('User Type') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ __(\App\Models\User\User::TYPES[$user->type]) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Status') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ __(\App\Models\User\User::STATUSES[$user->status]) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Tenant') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ $user->tenant?->name ?? __('No Tenant') }}</p>
                    </div>
                </div>
            </div>

            <!-- Login Activity -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Login Activity') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Last Login') }}</label>
                        @if($user->last_login_at)
                            <p class="text-zinc-900 dark:text-white">{{ $user->last_login_at->format('d.m.Y H:i:s') }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->last_login_at->diffForHumans() }}</p>
                        @else
                            <p class="text-zinc-500 dark:text-zinc-400">{{ __('Never logged in') }}</p>
                        @endif
                    </div>
                    @if($user->last_ip_address)
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Last IP Address') }}</label>
                            <p class="text-zinc-900 dark:text-white font-mono">{{ $user->last_ip_address }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Account Created') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ $user->created_at->format('d.m.Y H:i:s') }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Last Updated') }}</label>
                        <p class="text-zinc-900 dark:text-white">{{ $user->updated_at->format('d.m.Y H:i:s') }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                    @if($user->last_user_agent)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Last User Agent') }}</label>
                            <p class="text-zinc-900 dark:text-white text-sm font-mono break-all">{{ $user->last_user_agent }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('System Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('User ID') }}</label>
                        <p class="text-zinc-900 dark:text-white font-mono text-sm">{{ $user->id }}</p>
                    </div>
                    @if($user->tenant)
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Tenant ID') }}</label>
                            <p class="text-zinc-900 dark:text-white font-mono text-sm">{{ $user->tenant_id }}</p>
                        </div>
                    @endif
                </div>
                
                @if($user->settings && count($user->settings) > 0)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">{{ __('User Settings') }}</label>
                        <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                            <pre class="text-sm text-zinc-900 dark:text-white overflow-x-auto">{{ json_encode($user->settings, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Actions') }}</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('portal.user.edit', $user) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition-colors">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit User') }}
                    </a>
                    
                                <form method="POST" action="{{ route('portal.user.destroy', $user) }}" class="inline" 
                  onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium text-sm transition-colors">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete User') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg" x-data="{ show: true }" x-show="show" x-transition>
            {{ session('success') }}
            <button @click="show = false" class="ml-4 text-green-200 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
</div>
@endsection 