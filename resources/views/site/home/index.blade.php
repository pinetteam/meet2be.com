@extends('layouts.site')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-4">
                    <i class="fa-solid fa-house-chimney mr-2"></i>
                    {{ __('Welcome to Meet2Be Event Management System') }}
                </h1>
                
                <p class="text-lg text-gray-600 mb-6">
                    {{ __('Create and manage your events with ease.') }}
                </p>

                <!-- Alpine.js Example -->
                <div x-data="{ count: 0 }" class="mb-6">
                    <flux:button @click="count++" variant="primary">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Clicked <span x-text="count"></span> times
                    </flux:button>
                </div>

                <!-- FluxUI Components Example -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <flux:card class="space-y-4">
                        <flux:heading size="lg">
                            <i class="fa-solid fa-chart-line mr-2"></i>
                            {{ __('Analytics') }}
                        </flux:heading>
                        <flux:text>{{ __('View') }} {{ __('Analytics') }}</flux:text>
                    </flux:card>

                    <flux:card class="space-y-4">
                        <flux:heading size="lg">
                            <i class="fa-solid fa-users mr-2"></i>
                            {{ __('Users') }}
                        </flux:heading>
                        <flux:text>{{ __('Management') }} {{ __('Users') }}</flux:text>
                    </flux:card>

                    <flux:card class="space-y-4">
                        <flux:heading size="lg">
                            <i class="fa-solid fa-calendar-days mr-2"></i>
                            {{ __('Events') }}
                        </flux:heading>
                        <flux:text>{{ __('View') }} {{ __('Events') }}</flux:text>
                    </flux:card>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 