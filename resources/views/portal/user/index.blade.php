@extends('layouts.portal')

@section('title', __('user.title'))

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('user.title') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('user.subtitle') }}
            </p>
        </div>
        <a href="{{ route('portal.user.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fa-light fa-plus mr-2"></i>
            {{ __('user.actions.add') }}
        </a>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6" 
         x-data="userFilters()">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="sm:col-span-2">
                <div class="relative">
                    <i class="fa-light fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           placeholder="{{ __('user.labels.search_placeholder') }}" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           x-model="search"
                           @keyup.enter="applyFilters()">
                </div>
            </div>
            
            {{-- Status Filter --}}
            <div>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        x-model="statusFilter"
                        @change="applyFilters()">
                    <option value="">{{ __('user.labels.all_statuses') }}</option>
                    <option value="active">{{ __('user.statuses.active') }}</option>
                    <option value="inactive">{{ __('user.statuses.inactive') }}</option>
                    <option value="suspended">{{ __('user.statuses.suspended') }}</option>
                </select>
            </div>

            {{-- Type Filter --}}
            <div>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        x-model="typeFilter"
                        @change="applyFilters()">
                    <option value="">{{ __('user.labels.all_types') }}</option>
                    <option value="admin">{{ __('user.types.admin') }}</option>
                    <option value="screener">{{ __('user.types.screener') }}</option>
                    <option value="operator">{{ __('user.types.operator') }}</option>
                </select>
            </div>
        </div>

        {{-- Active Filters --}}
        <div x-show="hasActiveFilters()" x-cloak class="mt-4 flex items-center gap-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.labels.active_filters') }}:</span>
            <div class="flex flex-wrap gap-2">
                <span x-show="search" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-md text-sm">
                    {{ __('common.labels.search') }}: <span x-text="search"></span>
                    <button @click="search = ''; applyFilters()" class="ml-1 hover:text-blue-900 dark:hover:text-blue-100">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </span>
                <span x-show="statusFilter" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-md text-sm">
                    {{ __('user.labels.status') }}: <span x-text="getStatusLabel(statusFilter)"></span>
                    <button @click="statusFilter = ''; applyFilters()" class="ml-1 hover:text-blue-900 dark:hover:text-blue-100">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </span>
                <span x-show="typeFilter" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-md text-sm">
                    {{ __('user.labels.type') }}: <span x-text="getTypeLabel(typeFilter)"></span>
                    <button @click="typeFilter = ''; applyFilters()" class="ml-1 hover:text-blue-900 dark:hover:text-blue-100">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </span>
                <button @click="clearFilters()" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                    {{ __('common.actions.clear_all') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <x-ui.sortable-header column="name" :label="__('user.table.user')" />
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">
                            <x-ui.sortable-header column="type" :label="__('user.table.type')" />
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">
                            <x-ui.sortable-header column="status" :label="__('user.table.status')" />
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">
                            <x-ui.sortable-header column="last_login_at" :label="__('user.table.last_login')" />
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">{{ __('user.table.actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            {{-- User Info --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->type === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($user->type === 'screener') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 @endif">
                                    {{ $user->type_text }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($user->status === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                    {{ $user->status_text }}
                                </span>
                            </td>

                            {{-- Last Login --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                @if($user->last_login_at)
                                    <span title="@dt($user->last_login_at)">
                                        @relative($user->last_login_at)
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">{{ __('user.labels.never_logged') }}</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('portal.user.show', $user) }}" 
                                       class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                       title="{{ __('user.actions.view') }}">
                                        <i class="fa-light fa-eye"></i>
                                    </a>
                                    <a href="{{ route('portal.user.edit', $user) }}" 
                                       class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                       title="{{ __('user.actions.edit') }}">
                                        <i class="fa-light fa-pen-to-square"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" 
                                                @click="deleteUser('{{ $user->id }}')"
                                                class="text-gray-400 hover:text-red-600 dark:hover:text-red-400"
                                                title="{{ __('user.actions.delete') }}">
                                            <i class="fa-light fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <i class="fa-light fa-users text-4xl mb-4"></i>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                        {{ __('user.messages.no_users_found') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('user.messages.try_adjusting_search_or_filter_criteria') }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function userFilters() {
    return {
        search: @js(request('search', '')),
        statusFilter: @js(request('status', '')),
        typeFilter: @js(request('type', '')),
        
        hasActiveFilters() {
            return this.search || this.statusFilter || this.typeFilter;
        },
        
        getStatusLabel(status) {
            const labels = {
                'active': '{{ __('user.statuses.active') }}',
                'inactive': '{{ __('user.statuses.inactive') }}',
                'suspended': '{{ __('user.statuses.suspended') }}'
            };
            return labels[status] || status;
        },
        
        getTypeLabel(type) {
            const labels = {
                'admin': '{{ __('user.types.admin') }}',
                'screener': '{{ __('user.types.screener') }}',
                'operator': '{{ __('user.types.operator') }}'
            };
            return labels[type] || type;
        },
        
        applyFilters() {
            const params = new URLSearchParams();
            
            if (this.search) params.set('search', this.search);
            if (this.statusFilter) params.set('status', this.statusFilter);
            if (this.typeFilter) params.set('type', this.typeFilter);
            
            // Preserve sort parameters
            const sortBy = @js(request('sort_by', ''));
            const sortOrder = @js(request('sort_order', ''));
            if (sortBy) params.set('sort_by', sortBy);
            if (sortOrder) params.set('sort_order', sortOrder);
            
            window.location.href = `{{ route('portal.user.index') }}${params.toString() ? '?' + params.toString() : ''}`;
        },
        
        clearFilters() {
            this.search = '';
            this.statusFilter = '';
            this.typeFilter = '';
            this.applyFilters();
        },
        
        deleteUser(userId) {
            if (confirm('{{ __('user.messages.confirm_delete') }}')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('portal.user.index') }}/${userId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
</script>
@endpush
@endsection 