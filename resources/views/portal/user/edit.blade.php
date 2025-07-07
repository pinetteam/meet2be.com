@extends('layouts.portal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Edit User') }}</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Update user information') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('portal.user.show', $user) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to User') }}
            </a>
            <a href="{{ route('portal.user.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-500 hover:bg-zinc-600 text-white rounded-lg font-medium text-sm transition-colors">
                {{ __('All Users') }}
            </a>
        </div>
    </div>

    <!-- User Form -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700" x-data="userEditForm()">
        <form method="POST" action="{{ route('portal.user.update', $user) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Basic Information') }}</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('First Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
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
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
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
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
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
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
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
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('phone') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Information -->
            <div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('Password Information') }}</h3>
                <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4 mb-4">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('Leave password fields empty to keep current password') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('New Password') }}
                        </label>
                        <input type="password" name="password" id="password"
                               class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                               @error('password') border-red-500 dark:border-red-500 @else border-zinc-300 dark:border-zinc-600 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('Confirm New Password') }}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
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
                                <option value="{{ $key }}" {{ old('type', $user->type) === $key ? 'selected' : '' }}>
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
                                <option value="{{ $key }}" {{ old('status', $user->status) === $key ? 'selected' : '' }}>
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

            <!-- System Information (Read Only) -->
            <div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">{{ __('System Information') }}</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('User ID') }}</label>
                        <div class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-zinc-50 dark:bg-zinc-700 text-zinc-900 dark:text-white font-mono text-sm">
                            {{ $user->id }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Created At') }}</label>
                        <div class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-zinc-50 dark:bg-zinc-700 text-zinc-900 dark:text-white">
                            {{ $user->created_at->format('d.m.Y H:i:s') }}
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Last Login') }}</label>
                        @if($user->last_activity)
                            <div class="mt-1 flex items-center">
                                <i class="fas fa-clock text-zinc-400 mr-2"></i>
                                <span class="text-zinc-900 dark:text-white">
                                    @timezone($user->last_activity)
                                </span>
                            </div>
                        @else
                            <p class="mt-1 text-zinc-400 dark:text-zinc-500">{{ __('Never logged in') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Last Updated') }}</label>
                        <div class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-zinc-50 dark:bg-zinc-700 text-zinc-900 dark:text-white">
                            {{ $user->updated_at->format('d.m.Y H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-zinc-600">
                <div class="flex items-center gap-3">
                                    <a href="{{ route('portal.user.show', $user) }}" 
                   class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 text-zinc-700 dark:text-zinc-300 rounded-lg font-medium text-sm transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <a href="{{ route('portal.user.index') }}" 
                       class="px-4 py-2 text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 text-sm transition-colors">
                        {{ __('All Users') }}
                    </a>
                </div>
                <button type="submit" 
                        class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition-colors">
                    {{ __('Update User') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function userEditForm() {
    return {
        
    }
}
</script>
@endsection 