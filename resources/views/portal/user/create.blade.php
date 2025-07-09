@extends('layouts.portal')

@section('title', __('user.create_title'))

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('portal.user.index') }}" 
               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                <i class="fa-light fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('user.create_title') }}
            </h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 ml-8">
            {{ __('user.create_subtitle') }}
        </p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('portal.user.store') }}" x-data="userForm()">
        @csrf
        
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
                        :placeholder="__('user.placeholders.first_name')"
                        required
                        autofocus />
                    
                    <x-form.input
                        name="last_name"
                        :label="__('user.fields.last_name')"
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
                        :placeholder="__('user.placeholders.username')"
                        :hint="__('user.hints.username')"
                        required />
                    
                    <x-form.input
                        type="email"
                        name="email"
                        :label="__('user.fields.email')"
                        :placeholder="__('user.placeholders.email')"
                        required />
                    
                    <x-form.input
                        type="tel"
                        name="phone"
                        :label="__('user.fields.phone')"
                        :placeholder="__('user.placeholders.phone')" />
                </div>
            </div>

            {{-- Password --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('user.sections.password') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.input
                        type="password"
                        name="password"
                        :label="__('user.fields.password')"
                        :placeholder="__('user.placeholders.password')"
                        :hint="__('user.hints.password')"
                        required />
                    
                    <x-form.input
                        type="password"
                        name="password_confirmation"
                        :label="__('user.fields.password_confirmation')"
                        :placeholder="__('user.placeholders.password_confirmation')"
                        required />
                </div>
            </div>

            {{-- Permissions --}}
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('user.sections.permissions') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.select
                        name="type"
                        :label="__('user.fields.type')"
                        :options="collect(\App\Models\User\User::TYPES)->map(fn($label, $key) => ['value' => $key, 'label' => __('user.types.' . $key)])->values()->toArray()"
                        :placeholder="__('user.placeholders.select_type')"
                        required />
                    
                    <x-form.select
                        name="status"
                        :label="__('user.fields.status')"
                        :options="collect(\App\Models\User\User::STATUSES)->map(fn($label, $key) => ['value' => $key, 'label' => __('user.statuses.' . $key)])->values()->toArray()"
                        :selected="'active'"
                        required />
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('portal.user.index') }}" 
               class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('common.cancel') }}
            </a>
            <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('user.actions.create') }}
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