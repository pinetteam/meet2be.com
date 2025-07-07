@extends('layouts.portal')

@section('title', __('portal.settings.title'))

@section('content')
<div x-data="settingsPage()">
    {{-- Header --}}
    <div class="mb-6" x-ref="header">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('portal.settings.title') }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ __('portal.settings.subtitle') }}</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-6" x-ref="tabContainer">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700"
             :class="{'shadow-lg fixed top-14 sm:top-16 left-0 right-0 z-20 mx-4 sm:mx-6 lg:mx-8 lg:left-64': tabsFixed}">
            {{-- Tab container with horizontal scroll on mobile --}}
            <div class="relative overflow-x-auto">
                <nav class="flex -mb-px min-w-max">
                    <button @click="activeTab = 'general'" 
                            :class="activeTab === 'general' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="py-2 px-3 sm:px-6 border-b-2 font-medium text-xs sm:text-sm transition-colors whitespace-nowrap flex-shrink-0">
                        <i class="fas fa-info-circle mr-1 sm:mr-2"></i><span class="hidden sm:inline">{{ __('portal.settings.tabs.general') }}</span><span class="sm:hidden">{{ __('portal.general.general') }}</span>
                    </button>
                    <button @click="activeTab = 'contact'" 
                            :class="activeTab === 'contact' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="py-2 px-3 sm:px-6 border-b-2 font-medium text-xs sm:text-sm transition-colors whitespace-nowrap flex-shrink-0">
                        <i class="fas fa-address-book mr-1 sm:mr-2"></i><span class="hidden sm:inline">{{ __('portal.settings.tabs.contact') }}</span><span class="sm:hidden">{{ __('portal.general.contact') }}</span>
                    </button>
                    <button @click="activeTab = 'localization'" 
                            :class="activeTab === 'localization' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="py-2 px-3 sm:px-6 border-b-2 font-medium text-xs sm:text-sm transition-colors whitespace-nowrap flex-shrink-0">
                        <i class="fas fa-globe mr-1 sm:mr-2"></i><span class="hidden sm:inline">{{ __('portal.settings.tabs.localization') }}</span><span class="sm:hidden">{{ __('portal.general.localization') }}</span>
                    </button>
                    <button @click="activeTab = 'subscription'" 
                            :class="activeTab === 'subscription' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="py-2 px-3 sm:px-6 border-b-2 font-medium text-xs sm:text-sm transition-colors whitespace-nowrap flex-shrink-0">
                        <i class="fas fa-crown mr-1 sm:mr-2"></i><span class="hidden sm:inline">{{ __('portal.settings.tabs.subscription') }}</span><span class="sm:hidden">{{ __('portal.general.subscription') }}</span>
                    </button>
                </nav>
                
                {{-- Scroll indicator for mobile --}}
                <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white dark:from-gray-800 to-transparent pointer-events-none sm:hidden"
                     x-show="!isScrolledToEnd"></div>
            </div>
        </div>
    </div>

    {{-- Spacer for fixed tabs --}}
    <div x-show="tabsFixed" x-cloak class="h-14"></div>

    {{-- Tab Content --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form x-data="settingsForm()" @submit.prevent="submitForm" class="p-4 sm:p-6">
            @csrf
            @method('PUT')

            {{-- General Tab --}}
            <div x-show="activeTab === 'general'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.name') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   x-model="form.name"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('portal.settings.hints.name') }}</p>
                    </div>

                    {{-- Legal Name --}}
                    <div>
                        <label for="legal_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.legal_name') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file-contract text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="legal_name" 
                                   name="legal_name" 
                                   x-model="form.legal_name"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- Code --}}
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.code') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="code" 
                                   value="{{ $tenant->code }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-600 dark:text-gray-300"
                                   disabled>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('portal.settings.hints.code') }}</p>
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.slug') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   x-model="form.slug"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Tab --}}
            <div x-show="activeTab === 'contact'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.email') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   x-model="form.email"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.phone') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   x-model="form.phone"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- Website --}}
                    <div class="md:col-span-2">
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.website') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <input type="url" 
                                   id="website" 
                                   name="website" 
                                   x-model="form.website"
                                   placeholder="https://"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- Address Line 1 --}}
                    <div class="md:col-span-2">
                        <label for="address_line_1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.address_line_1') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="address_line_1" 
                                   name="address_line_1" 
                                   x-model="form.address_line_1"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- Address Line 2 --}}
                    <div class="md:col-span-2">
                        <label for="address_line_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.address_line_2') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="address_line_2" 
                                   name="address_line_2" 
                                   x-model="form.address_line_2"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- City --}}
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.city') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-city text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   x-model="form.city"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- State --}}
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.state') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-signs text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="state" 
                                   name="state" 
                                   x-model="form.state"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- Postal Code --}}
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.postal_code') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-mail-bulk text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   x-model="form.postal_code"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- Country --}}
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.country') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-flag text-gray-400"></i>
                            </div>
                            <select id="country_id" 
                                    name="country_id" 
                                    x-model="form.country_id"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">{{ __('portal.settings.placeholders.select_country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Localization Tab --}}
            <div x-show="activeTab === 'localization'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Language --}}
                    <div>
                        <label for="language_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.language') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-language text-gray-400"></i>
                            </div>
                            <select id="language_id" 
                                    name="language_id" 
                                    x-model="form.language_id"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">{{ __('portal.settings.placeholders.select_language') }}</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}">{{ $language->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Currency --}}
                    <div>
                        <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.currency') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-dollar-sign text-gray-400"></i>
                            </div>
                            <select id="currency_id" 
                                    name="currency_id" 
                                    x-model="form.currency_id"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">{{ __('portal.settings.placeholders.select_currency') }}</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Timezone --}}
                    <div>
                        <label for="timezone_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.timezone') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <select id="timezone_id" 
                                    name="timezone_id" 
                                    x-model="form.timezone_id"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">{{ __('portal.settings.placeholders.select_timezone') }}</option>
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->id }}">{{ $timezone->name }} (UTC{{ $timezone->offset >= 0 ? '+' : '' }}{{ $timezone->offset }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Date Format --}}
                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.date_format') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <select id="date_format" 
                                    name="date_format" 
                                    x-model="form.date_format"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="Y-m-d">{{ now()->format('Y-m-d') }} (Y-m-d)</option>
                                <option value="d/m/Y">{{ now()->format('d/m/Y') }} (d/m/Y)</option>
                                <option value="m/d/Y">{{ now()->format('m/d/Y') }} (m/d/Y)</option>
                                <option value="d.m.Y">{{ now()->format('d.m.Y') }} (d.m.Y)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Time Format --}}
                    <div>
                        <label for="time_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('portal.settings.fields.time_format') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <select id="time_format" 
                                    name="time_format" 
                                    x-model="form.time_format"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="H:i">{{ now()->format('H:i') }} (24 hour)</option>
                                <option value="h:i A">{{ now()->format('h:i A') }} (12 hour)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscription Tab --}}
            <div x-show="activeTab === 'subscription'" x-cloak>
                {{-- Current Plan Info --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-2"></i>
                        <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100">{{ __('portal.settings.subscription.current_plan') }}</h3>
                    </div>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>{{ __('portal.settings.subscription.plan') }}: <span class="font-semibold">{{ $tenant->getPlanName() }}</span></p>
                        <p>{{ __('portal.settings.subscription.status') }}: <span class="font-semibold">{{ $tenant->getStatusName() }}</span></p>
                        @if($tenant->subscription_ends_at)
                            <p>{{ __('portal.settings.subscription.expires') }}: <span class="font-semibold">@dt($tenant->subscription_ends_at)</span></p>
                        @endif
                    </div>
                </div>

                {{-- Usage Statistics --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Users Usage --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('portal.settings.subscription.users') }}</h4>
                            <i class="fas fa-users text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $tenant->current_users }} / {{ $tenant->max_users }}
                        </div>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $tenant->getUserUsagePercentage() }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $tenant->getUserUsagePercentage() }}% {{ __('portal.settings.subscription.used') }}</p>
                        </div>
                    </div>

                    {{-- Storage Usage --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('portal.settings.subscription.storage') }}</h4>
                            <i class="fas fa-hdd text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($tenant->current_storage_mb / 1024, 1) }} GB / {{ number_format($tenant->max_storage_mb / 1024, 1) }} GB
                        </div>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $tenant->getStorageUsagePercentage() }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $tenant->getStorageUsagePercentage() }}% {{ __('portal.settings.subscription.used') }}</p>
                        </div>
                    </div>

                    {{-- Events Usage --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('portal.settings.subscription.events') }}</h4>
                            <i class="fas fa-calendar-alt text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $tenant->current_events }} / {{ $tenant->max_events }}
                        </div>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $tenant->getEventUsagePercentage() }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $tenant->getEventUsagePercentage() }}% {{ __('portal.settings.subscription.used') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="loading">
                    <span x-show="!loading">
                        <i class="fas fa-save mr-2"></i>{{ __('portal.settings.buttons.save') }}
                    </span>
                    <span x-show="loading" x-cloak>
                        <i class="fas fa-spinner fa-spin mr-2"></i>{{ __('portal.settings.buttons.saving') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function settingsPage() {
    return {
        activeTab: 'general',
        tabsFixed: false,
        isScrolledToEnd: false,
        
        init() {
            // Setup intersection observer for header
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        this.tabsFixed = !entry.isIntersecting;
                    });
                },
                {
                    rootMargin: '-60px 0px 0px 0px',
                    threshold: [0, 1]
                }
            );
            
            observer.observe(this.$refs.header);
            
            // Check horizontal scroll position
            const scrollContainer = this.$refs.tabContainer.querySelector('.overflow-x-auto');
            if (scrollContainer) {
                scrollContainer.addEventListener('scroll', () => {
                    const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
                    this.isScrolledToEnd = scrollContainer.scrollLeft >= maxScroll - 5;
                });
                
                // Initial check
                const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
                this.isScrolledToEnd = scrollContainer.scrollLeft >= maxScroll - 5;
            }
            
            // Clean up on destroy
            this.$watch('$el', () => {
                observer.disconnect();
            });
        }
    }
}

function settingsForm() {
    return {
        loading: false,
        form: {
            // Basic Information
            name: @json($tenant->name),
            legal_name: @json($tenant->legal_name),
            slug: @json($tenant->slug),
            
            // Contact Information
            email: @json($tenant->email),
            phone: @json($tenant->phone),
            website: @json($tenant->website),
            
            // Address Information
            address_line_1: @json($tenant->address_line_1),
            address_line_2: @json($tenant->address_line_2),
            city: @json($tenant->city),
            state: @json($tenant->state),
            postal_code: @json($tenant->postal_code),
            country_id: @json($tenant->country_id),
            
            // Localization Settings
            language_id: @json($tenant->language_id),
            currency_id: @json($tenant->currency_id),
            timezone_id: @json($tenant->timezone_id),
            date_format: @json($tenant->date_format),
            time_format: @json($tenant->time_format),
        },
        
        async submitForm() {
            this.loading = true;
            
            try {
                const response = await fetch('{{ route('portal.setting.update', $tenant) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.notify('{{ __('portal.general.success') }}', data.message, 'success');
                    
                    // If datetime settings changed, reload page to update all datetime displays
                    if (data.datetime_updated) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    window.notify('{{ __('portal.general.error') }}', data.message || '{{ __('portal.settings.messages.update_failed') }}', 'error');
                }
            } catch (error) {
                window.notify('{{ __('portal.general.error') }}', '{{ __('portal.settings.messages.update_failed') }}', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection 