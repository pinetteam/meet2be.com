@extends('layouts.portal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Add User') }}</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Create a new system user') }}</p>
        </div>
        <a href="{{ route('portal.user.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg font-medium text-sm transition-colors">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Users') }}
        </a>
    </div>

    <!-- User Form -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700" x-data="userForm()">
        <form method="POST" action="{{ route('portal.user.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Basic Information') }}</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('First Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('first_name') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Last Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('last_name') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Account Information') }}</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Username') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('username') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Email') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('email') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Phone') }}
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('phone') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tenant -->
                    <div>
                        <label for="tenant_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Tenant') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="tenant_id" id="tenant_id" required
                                class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                @error('tenant_id') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                            <option value="">{{ __('Select a tenant') }}</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('tenant_id') === $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tenant_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Information -->
            <div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Password Information') }}</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Password') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('password') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Confirm Password') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('password_confirmation') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- User Permissions -->
            <div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('User Permissions') }}</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- User Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('User Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                @error('type') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                            <option value="">{{ __('Select user type') }}</option>
                            @foreach(\App\Models\User\User::TYPES as $key => $label)
                                <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                    {{ __($label) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Status') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                @error('status') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                            <option value="">{{ __('Select status') }}</option>
                            @foreach(\App\Models\User\User::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ old('status', 'active') === $key ? 'selected' : '' }}>
                                    {{ __($label) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-zinc-200 dark:border-zinc-600">
                <a href="{{ route('portal.user.index') }}" 
                   class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 text-zinc-700 dark:text-zinc-300 rounded-lg font-medium text-sm transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition-colors">
                    {{ __('Create User') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function userForm() {
    return {
        
    }
}
</script>
@endsection 