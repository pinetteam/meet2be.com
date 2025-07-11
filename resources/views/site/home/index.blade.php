@extends('layouts.site')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-stone-900">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-white mb-4">{{ __('site.welcome') }}</h1>
        <p class="text-stone-300 mb-6">{{ __('site.description') }}</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-8" x-data="{ count: 0 }">
            <!-- Increment Button Card -->
            <div class="bg-stone-800 rounded-lg p-6 border border-stone-700 hover:border-indigo-500 transition-colors">
                <i class="fa-solid fa-plus-circle text-4xl text-indigo-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-white mb-2">Counter: <span x-text="count"></span></h3>
                <button @click="count++" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>
                    {{ __('common.increment') }}
                </button>
            </div>
            
            <!-- Analytics Card -->
            <div class="bg-stone-800 rounded-lg p-6 border border-stone-700 hover:border-indigo-500 transition-colors">
                <i class="fa-solid fa-chart-line text-4xl text-indigo-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-white mb-2">{{ __('common.analytics') }}</h3>
                <p class="text-stone-400 text-sm">
                    <p class="text-stone-300">{{ __('common.actions.view') }} {{ __('common.analytics') }}</p>
                </p>
            </div>
            
            <!-- Users Card -->
            <div class="bg-stone-800 rounded-lg p-6 border border-stone-700 hover:border-indigo-500 transition-colors">
                <i class="fa-solid fa-users text-4xl text-indigo-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-white mb-2">{{ __('common.users') }}</h3>
                <p class="text-stone-400 text-sm">
                    <p class="text-stone-300">{{ __('common.management') }} {{ __('common.users') }}</p>
                </p>
            </div>
            
            <!-- Events Card -->
            <div class="bg-stone-800 rounded-lg p-6 border border-stone-700 hover:border-indigo-500 transition-colors">
                <i class="fa-solid fa-calendar-check text-4xl text-indigo-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-white mb-2">{{ __('common.events') }}</h3>
                <p class="text-stone-400 text-sm">
                    <p class="text-stone-300">{{ __('common.actions.view') }} {{ __('common.events') }}</p>
                </p>
            </div>
        </div>
        
        <!-- Logout Button -->
        <form action="{{ route('site.auth.logout') }}" method="POST" class="mt-8 inline-block">
            @csrf
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fa-solid fa-sign-out-alt mr-2"></i>
                {{ __('site.common.logout') }}
            </button>
        </form>
    </div>
</div>
@endsection 