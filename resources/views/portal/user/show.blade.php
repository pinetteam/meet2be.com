@extends('layouts.portal')

@section('title', __('user.show_title'))

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('portal.user.index') }}" 
               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                <i class="fa-light fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                {{ $user->full_name }}
            </h1>
        </div>
        <div class="flex items-center gap-4 ml-8">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($user->type === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                @elseif($user->type === 'screener') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 @endif">
                {{ $user->type_text }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                @elseif($user->status === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                {{ $user->status_text }}
            </span>
        </div>
    </div>

    {{-- Actions --}}
    <div class="mb-6 flex items-center justify-end gap-3">
        <a href="{{ route('portal.user.edit', $user) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fa-light fa-pen-to-square mr-2"></i>
            {{ __('user.actions.edit') }}
        </a>
        @if($user->id !== auth()->id())
            <button type="button" 
                    @click="deleteUser"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fa-light fa-trash mr-2"></i>
                {{ __('user.actions.delete') }}
            </button>
        @endif
    </div>

    {{-- User Details --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- User Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-xl font-medium text-gray-600 dark:text-gray-400">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-xl font-medium text-gray-900 dark:text-white">
                        {{ $user->full_name }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @{{ $user->username }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Basic Information --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ __('user.sections.basic_info') }}
            </h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.first_name') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $user->first_name }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.last_name') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $user->last_name }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Account Information --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ __('user.sections.account_info') }}
            </h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.email') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            {{ $user->email }}
                        </a>
                        @if($user->email_verified_at)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <i class="fa-light fa-check-circle mr-1"></i>
                                {{ __('user.labels.verified') }}
                            </span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.phone') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($user->phone)
                            <a href="tel:{{ $user->phone }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ $user->phone }}
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Permissions --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ __('user.sections.permissions') }}
            </h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.type') }}
                    </dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->type === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                            @elseif($user->type === 'screener') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                            @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 @endif">
                            {{ $user->type_text }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.status') }}
                    </dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @elseif($user->status === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                            @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                            {{ $user->status_text }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        {{-- System Information --}}
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ __('user.sections.system_info') }}
            </h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.user_id') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                        {{ $user->id }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.last_login') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($user->last_login_at)
                            <span title="@dt($user->last_login_at)">
                                @relative($user->last_login_at)
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">{{ __('user.labels.never_logged') }}</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.created_at') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        <span title="@relative($user->created_at)">
                            @dt($user->created_at)
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('user.fields.updated_at') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($user->updated_at->ne($user->created_at))
                            <span title="@relative($user->updated_at)">
                                @dt($user->updated_at)
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">{{ __('user.labels.never_updated') }}</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="mt-6 flex justify-end">
        <a href="{{ route('portal.user.index') }}" 
           class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fa-light fa-arrow-left mr-2"></i>
            {{ __('user.actions.back_to_list') }}
        </a>
    </div>
</div>

@push('scripts')
<script>
function deleteUser() {
    if (confirm('{{ __('user.messages.confirm_delete') }}')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('portal.user.destroy', $user) }}';
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection 