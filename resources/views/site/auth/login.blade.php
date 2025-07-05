@extends('layouts.site')

@section('content')
<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 bg-indigo-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-calendar-check text-white text-2xl"></i>
            </div>
            <flux:heading size="2xl" class="text-gray-900">{{ config('app.name', 'Meet2Be') }}</flux:heading>
            <flux:text size="sm" class="text-gray-600 mt-2">{{ __('site.common.event_management_system') }}</flux:text>
        </div>

        <!-- Login Card -->
        <flux:card>
            <flux:heading size="lg" class="text-center mb-2">{{ __('site.common.login_title') }}</flux:heading>
            <flux:text class="text-center mb-8 text-gray-600">{{ __('site.common.login_subtitle') }}</flux:text>
            
            <flux:separator class="mb-6" />

            <form method="POST" action="{{ route('site.auth.login.store') }}" class="space-y-6">
                @csrf

                <flux:field>
                    <flux:label>{{ __('site.common.email_label') }}</flux:label>
                    <flux:input 
                        name="email"
                        type="email" 
                        placeholder="{{ __('site.common.email_placeholder') }}"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    />
                    @error('email')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('site.common.password_label') }}</flux:label>
                    <flux:input 
                        name="password"
                        type="password" 
                        placeholder="{{ __('site.common.password_placeholder') }}"
                        required
                    />
                    @error('password')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <div class="flex items-center justify-between">
                    <flux:checkbox name="remember">
                        {{ __('site.common.remember_me') }}
                    </flux:checkbox>
                    
                    <flux:link href="#" size="sm">
                        {{ __('site.common.forgot_password') }}
                    </flux:link>
                </div>

                <flux:button type="submit" variant="primary" class="w-full">
                    <i class="fa-solid fa-sign-in-alt"></i>
                    <span>{{ __('site.common.sign_in_button') }}</span>
                </flux:button>
            </form>

            <flux:separator class="my-6" />

            <div class="text-center">
                <flux:text size="sm" class="text-gray-600">
                    {{ __('site.common.dont_have_account') }} 
                    <flux:link href="#" variant="primary">
                        {{ __('site.common.sign_up') }}
                    </flux:link>
                </flux:text>
            </div>
        </flux:card>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <flux:text size="xs" class="text-gray-500">
                © {{ date('Y') }} {{ config('app.name', 'Meet2Be') }}. {{ __('site.common.all_rights_reserved') }}
            </flux:text>
        </div>
    </div>
</div>
@endsection 