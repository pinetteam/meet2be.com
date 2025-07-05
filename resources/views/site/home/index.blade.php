@extends('layouts.site')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-4">
                    <i class="fa-solid fa-house-chimney mr-2"></i>
                    Welcome to {{ config('app.name', 'Laravel') }}
                </h1>
                
                <p class="text-lg text-gray-600 mb-6">
                    This is a simple home page using Tailwind CSS, Alpine.js, FontAwesome Pro and FluxUI Pro.
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
                            Analytics
                        </flux:heading>
                        <flux:text>View your analytics dashboard</flux:text>
                    </flux:card>

                    <flux:card class="space-y-4">
                        <flux:heading size="lg">
                            <i class="fa-solid fa-users mr-2"></i>
                            Users
                        </flux:heading>
                        <flux:text>Manage your users</flux:text>
                    </flux:card>

                    <flux:card class="space-y-4">
                        <flux:heading size="lg">
                            <i class="fa-solid fa-calendar-days mr-2"></i>
                            Events
                        </flux:heading>
                        <flux:text>Browse upcoming events</flux:text>
                    </flux:card>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 