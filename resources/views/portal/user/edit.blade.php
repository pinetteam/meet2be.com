@extends('layouts.portal')

@section('title', __('user.edit_title'))

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('portal.user.show', $user) }}" 
               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                <i class="fa-light fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('user.edit_title') }}
            </h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 ml-8">
            {{ __('user.edit_subtitle', ['name' => $user->full_name]) }}
        </p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('portal.user.update', $user) }}" x-data="userForm()">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            {{-- Basic Information --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('user.sections.basic_info') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.input
                        name="first_name"
                        :label="__('user.fields.first_name')"
                        :value="$user->first_name"
                        :placeholder="__('user.placeholders.first_name')"
                        required />
                    
                    <x-form.input
                        name="last_name"
                        :label="__('user.fields.last_name')"
                        :value="$user->last_name"
                        :placeholder="__('user.placeholders.last_name')"
                        required />
                </div>
            </div>

            {{-- Account Information --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('user.sections.account_info') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.input
                        name="username"
                        :label="__('user.fields.username')"
                        :value="$user->username"
                        :placeholder="__('user.placeholders.username')"
                        :hint="__('user.hints.username')"
                        required />
                    
                    <x-form.input
                        type="email"
                        name="email"
                        :label="__('user.fields.email')"
                        :value="$user->email"
                        :placeholder="__('user.placeholders.email')"
                        required />
                    
                    <x-form.input
                        type="tel"
                        name="phone"
                        :label="__('user.fields.phone')"
                        :value="$user->phone"
                        :placeholder="__('user.placeholders.phone')" />
                </div>
            </div>

            {{-- Password --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('user.sections.password') }}
                </h3>
                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <i class="fa-light fa-info-circle mr-1"></i>
                        {{ __('user.hints.password_update') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.input
                        type="password"
                        name="password"
                        :label="__('user.fields.new_password')"
                        :placeholder="__('user.placeholders.new_password')"
                        :hint="__('user.hints.password')" />
                    
                    <x-form.input
                        type="password"
                        name="password_confirmation"
                        :label="__('user.fields.password_confirmation')"
                        :placeholder="__('user.placeholders.password_confirmation')" />
                </div>
            </div>

            {{-- Permissions --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('user.sections.permissions') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.select
                        name="type"
                        :label="__('user.fields.type')"
                        :options="collect(\App\Models\User\User::TYPES)->map(fn($label, $key) => ['value' => $key, 'label' => __('user.types.' . $key)])->values()->toArray()"
                        :selected="$user->type"
                        required />
                    
                    <x-form.select
                        name="status"
                        :label="__('user.fields.status')"
                        :options="collect(\App\Models\User\User::STATUSES)->map(fn($label, $key) => ['value' => $key, 'label' => __('user.statuses.' . $key)])->values()->toArray()"
                        :selected="$user->status"
                        required />
                </div>
            </div>

            {{-- System Information --}}
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('user.sections.system_info') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('user.fields.user_id') }}
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-gray-100 font-mono">
                            {{ $user->id }}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('user.fields.created_at') }}
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-gray-100">
                            @dt($user->created_at)
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('user.fields.last_login') }}
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-gray-100">
                            @if($user->last_login_at)
                                @dt($user->last_login_at)
                            @else
                                <span class="text-gray-400 dark:text-gray-500">{{ __('user.labels.never_logged') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('user.fields.updated_at') }}
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-gray-100">
                            @dt($user->updated_at)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('portal.user.show', $user) }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('common.cancel') }}
                </a>
                <a href="{{ route('portal.user.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                    {{ __('user.actions.back_to_list') }}
                </a>
            </div>
            <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('user.actions.update') }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function userForm() {
    return {
        // Form state can be managed here if needed
    }
}
</script>
@endpush
@endsection 