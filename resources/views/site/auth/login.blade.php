<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Sign In') }} - {{ config('app.name', 'Meet2Be') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/site/site.css', 'resources/js/site/site.js'])
</head>
<body class="font-sans antialiased bg-stone-900">
    <div class="flex min-h-screen bg-stone-900">
        <div class="flex-1 flex justify-center items-center bg-stone-900">
            <div class="w-80 max-w-80 space-y-6">
                <div class="flex justify-center">
                    <a href="/" class="group flex items-center gap-3">
                        <div class="h-12 w-12 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-semibold text-white">{{ config('app.name', 'Meet2Be') }}</span>
                    </a>
                </div>
                
                <flux:heading class="text-center text-white" size="xl">{{ __('Welcome back!') }}</flux:heading>
                
                <form method="POST" action="{{ route('site.auth.login.store') }}" class="flex flex-col gap-6">
                    @csrf
                    
                    <flux:field>
                        <flux:label class="text-stone-200">{{ __('Email Address') }}</flux:label>
                        <flux:input 
                            name="email"
                            type="email" 
                            placeholder="email@example.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="bg-stone-800 text-white border-stone-700 focus:border-indigo-500"
                        />
                        @error('email')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                    
                    <flux:field>
                        <div class="mb-3 flex justify-between">
                            <flux:label class="text-stone-200">{{ __('Password') }}</flux:label>
                            <flux:link href="#" variant="subtle" class="text-sm text-indigo-400 hover:text-indigo-300">{{ __('Forgot password?') }}</flux:link>
                        </div>
                        <flux:input 
                            name="password"
                            type="password" 
                            placeholder="{{ __('Enter your password') }}"
                            required
                            class="bg-stone-800 text-white border-stone-700 focus:border-indigo-500"
                        />
                        @error('password')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                    
                    <flux:checkbox name="remember" label="{{ __('Remember me for 30 days') }}" class="text-stone-200" />
                    
                    <flux:button type="submit" variant="primary" class="w-full bg-indigo-600 hover:bg-indigo-700">{{ __('Login') }}</flux:button>
                </form>
                
                <flux:separator class="border-stone-700" />
                
                <flux:subheading class="text-center text-stone-300">
                    {{ __("First time around here?") }} <flux:link href="#" class="text-indigo-400 hover:text-indigo-300">{{ __('Sign up for free') }}</flux:link>
                </flux:subheading>
            </div>
        </div>
        
        <div class="flex-1 p-4 max-lg:hidden">
            <div class="text-white relative rounded-lg h-full w-full overflow-hidden flex flex-col items-start justify-end p-16"
                 style="background-image: url('{{ asset('assets/images/site/auth_bg.png') }}'); background-size: cover; background-position: center;">
                <!-- Dark overlay for better text readability -->
                <div class="absolute inset-0 bg-black/40 rounded-lg"></div>
                
                <!-- Content -->
                <div class="relative z-10">
                    <div class="flex gap-2 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star text-yellow-400"></i>
                        @endfor
                    </div>
                    <div class="mb-6 italic font-base text-3xl xl:text-4xl">
                        {{ __('Meet2Be has enabled me to design, build, and manage events faster than ever before.') }}
                    </div>
                    <div class="flex gap-4">
                        <flux:avatar src="{{ asset('assets/images/site/ismail_celik.png') }}" size="xl" />
                        <div class="flex flex-col justify-center font-medium">
                            <div class="text-lg">Prof. Dr. İsmail Çelik</div>
                            <div class="text-stone-300">{{ __('President of Turkish Medical Oncology Association') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 