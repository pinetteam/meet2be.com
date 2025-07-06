@extends('layouts.site')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-stone-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold text-white mb-4">{{ __('Welcome to Meet2Be') }}</h1>
            <p class="text-stone-300 mb-6">{{ __('This is the site home page.') }}</p>
            
            <!-- Alpine.js Example -->
            <div x-data="{ count: 0 }" class="mb-8">
                <p class="text-stone-300 mb-2">
                    Alpine.js Counter Example: <span x-text="count" class="font-bold text-indigo-400"></span>
                </p>
                <button @click="count++" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>
                    {{ __('Increment') }}
                </button>
            </div>
            
            <!-- Cards Example -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-stone-700 rounded-lg p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-white">
                        <i class="fa-solid fa-chart-line mr-2 text-indigo-400"></i>
                        {{ __('Analytics') }}
                    </h2>
                    <p class="text-stone-300">{{ __('View') }} {{ __('Analytics') }}</p>
                </div>
                
                <div class="bg-stone-700 rounded-lg p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-white">
                        <i class="fa-solid fa-users mr-2 text-indigo-400"></i>
                        {{ __('Users') }}
                    </h2>
                    <p class="text-stone-300">{{ __('Management') }} {{ __('Users') }}</p>
                </div>
                
                <div class="bg-stone-700 rounded-lg p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-white">
                        <i class="fa-solid fa-calendar mr-2 text-indigo-400"></i>
                        {{ __('Events') }}
                    </h2>
                    <p class="text-stone-300">{{ __('View') }} {{ __('Events') }}</p>
                </div>
            </div>
            
            @auth
                <form method="POST" action="{{ route('site.auth.logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fa-solid fa-sign-out-alt mr-2"></i>
                        {{ __('Log out') }}
                    </button>
                </form>
            @endauth
        </div>
    </div>
</div>
@endsection 