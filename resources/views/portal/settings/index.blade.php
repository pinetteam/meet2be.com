@extends('layouts.portal')

@section('title', __('settings.title'))

@section('content')
@if (session('success'))
    <div class="mb-6 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
@endif

<div x-data="settingsPage()">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('settings.title') }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('settings.subtitle') }}</p>
    </div>

    <!-- Subscription & Usage Info -->
    <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Plan Info -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold">{{ __('settings.subscription.current_plan') }}</h3>
                    <p class="text-3xl font-bold mt-1">{{ $tenant->getPlanName() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-crown text-2xl"></i>
                </div>
            </div>
            @if($tenant->subscription_ends_at)
                <p class="text-sm opacity-90">
                    <i class="fa-regular fa-calendar-days mr-1"></i>
                    {{ __('settings.subscription.expires') }}: @timezoneDate($tenant->subscription_ends_at)
                </p>
            @endif
        </div>

        <!-- Users Usage -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('settings.usage.users') }}</h3>
                <i class="fa-solid fa-users text-amber-500"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-900 dark:text-white font-medium">{{ $tenant->current_users }} / {{ $tenant->max_users }}</span>
                    <span class="text-gray-600 dark:text-gray-400">{{ $tenant->getUserUsagePercentage() }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-amber-500 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $tenant->getUserUsagePercentage() }}%"></div>
                </div>
            </div>
        </div>

        <!-- Storage Usage -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('settings.usage.storage') }}</h3>
                <i class="fa-solid fa-hard-drive text-blue-500"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-900 dark:text-white font-medium">
                        {{ number_format($tenant->current_storage_mb / 1024, 2) }} GB / {{ number_format($tenant->max_storage_mb / 1024, 2) }} GB
                    </span>
                    <span class="text-gray-600 dark:text-gray-400">{{ $tenant->getStorageUsagePercentage() }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $tenant->getStorageUsagePercentage() }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-8 overflow-x-auto">
        <nav class="-mb-px flex space-x-4 md:space-x-8 min-w-max" aria-label="Tabs">
            <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-xs md:text-sm transition-colors duration-200">
                <i class="fa-solid fa-building mr-1 md:mr-2"></i>
                {{ __('settings.tabs.general') }}
            </button>
            <button @click="activeTab = 'contact'"
                    :class="activeTab === 'contact' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-xs md:text-sm transition-colors duration-200">
                <i class="fa-solid fa-address-card mr-1 md:mr-2"></i>
                {{ __('settings.tabs.contact') }}
            </button>
            <button @click="activeTab = 'system'"
                    :class="activeTab === 'system' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-xs md:text-sm transition-colors duration-200">
                <i class="fa-solid fa-cog mr-1 md:mr-2"></i>
                {{ __('settings.tabs.system') }}
            </button>
            <button @click="activeTab = 'branding'"
                    :class="activeTab === 'branding' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-xs md:text-sm transition-colors duration-200">
                <i class="fa-solid fa-palette mr-1 md:mr-2"></i>
                {{ __('settings.tabs.branding') }}
            </button>
        </nav>
    </div>

    <form action="{{ route('portal.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- General Information Tab -->
        <div x-show="activeTab === 'general'" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Company Type Badge -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('settings.fields.company_type') }}
                        </label>
                        <div class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700">
                            <i class="fa-solid {{ $tenant->type === 'enterprise' ? 'fa-building' : ($tenant->type === 'business' ? 'fa-briefcase' : 'fa-user') }} mr-2 text-gray-600 dark:text-gray-400"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $tenant->getTypeName() }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.tenant_name') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name', $tenant->name) }}"
                                       required
                                       class="w-full rounded-lg border-0 pl-10 pr-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('name') ring-red-600 dark:ring-red-500 @enderror">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.tenant_code') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-key text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       id="code"
                                       value="{{ $tenant->code }}"
                                       readonly
                                       class="w-full rounded-lg border-0 pl-10 pr-4 py-3 text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="legal_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('settings.fields.company_name') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-building text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="legal_name" 
                                   id="legal_name"
                                   value="{{ old('legal_name', $tenant->legal_name) }}"
                                   placeholder="{{ __('settings.placeholders.legal_name') }}"
                                   class="w-full rounded-lg border-0 pl-10 pr-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('legal_name') ring-red-600 dark:ring-red-500 @enderror">
                        </div>
                        @error('legal_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('settings.fields.website') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-globe text-gray-400"></i>
                            </div>
                            <input type="url" 
                                   name="website" 
                                   id="website"
                                   value="{{ old('website', $tenant->website) }}"
                                   placeholder="https://example.com"
                                   class="w-full rounded-lg border-0 pl-10 pr-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('website') ring-red-600 dark:ring-red-500 @enderror">
                        </div>
                        @error('website')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information Tab -->
        <div x-show="activeTab === 'contact'" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('settings.fields.email') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email', $tenant->email) }}"
                                   required
                                   placeholder="info@example.com"
                                   class="w-full rounded-lg border-0 pl-10 pr-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('email') ring-red-600 dark:ring-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('settings.fields.phone') }}
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <div class="relative flex-shrink-0">
                                <button type="button" @click="showCountryDropdown = !showCountryDropdown"
                                    class="relative flex items-center gap-2 h-full px-4 py-3 text-sm font-medium text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-r-0 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 focus:z-10 rounded-l-lg transition-colors">
                                    <img :src="`/assets/images/flags/32x24/${selectedCountry.iso2.toLowerCase()}.png`" 
                                        :alt="getCountryName(selectedCountry)" class="h-4 w-6">
                                    <span x-text="`+${selectedCountry.phone_code}`" class="font-medium whitespace-nowrap"></span>
                                    <i class="fa-solid fa-chevron-down h-3 w-3 text-gray-400"></i>
                                </button>
                                <div x-show="showCountryDropdown" @click.away="showCountryDropdown = false"
                                    x-transition
                                    class="absolute left-0 mt-1 w-80 origin-top-left rounded-lg bg-white dark:bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                    <div class="py-1 max-h-60 overflow-auto">
                                        <input type="text" 
                                            x-model="countrySearch" 
                                            placeholder="{{ __('settings.search_country') }}"
                                            autocomplete="off"
                                            class="mx-2 mb-2 mt-1 block w-[calc(100%-16px)] rounded-md border-0 px-3 py-2 text-sm text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-600"
                                            @click.stop>
                                        <template x-for="country in filteredCountries" :key="country.id">
                                            <a href="#" @click.prevent="selectCountry(country)"
                                                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <img :src="`/assets/images/flags/32x24/${country.iso2.toLowerCase()}.png`" 
                                                    :alt="country.name_en" class="h-4 w-6 flex-shrink-0">
                                                <span class="flex-1 truncate">
                                                    <span x-text="getCountryName(country)"></span>
                                                    <span class="text-gray-500 dark:text-gray-400" x-text="`(+${country.phone_code})`"></span>
                                                </span>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="phone_country_id" x-model="selectedCountry.id">
                            <input type="tel" 
                                   name="phone" 
                                   id="phone"
                                   x-model="phoneNumber"
                                   x-mask="999999999999999"
                                   placeholder="1234567890"
                                   maxlength="15"
                                   autocomplete="tel-national"
                                   class="block w-full min-w-0 flex-1 rounded-none rounded-r-lg border border-l-0 px-4 py-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-800 transition-all @error('phone') ring-red-600 dark:ring-red-500 @enderror">
                        </div>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('settings.fields.address') }}
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fa-solid fa-location-dot text-gray-400"></i>
                            </div>
                            <textarea name="address" 
                                      id="address"
                                      rows="3"
                                      placeholder="{{ __('settings.placeholders.address') }}"
                                      class="w-full rounded-lg border-0 pl-10 pr-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('address') ring-red-600 dark:ring-red-500 @enderror">{{ old('address', $tenant->address_line_1) }}</textarea>
                        </div>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.city') }}
                            </label>
                            <input type="text" 
                                   name="city" 
                                   id="city"
                                   value="{{ old('city', $tenant->city) }}"
                                   placeholder="{{ __('settings.placeholders.city') }}"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('city') ring-red-600 dark:ring-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.state') }}
                            </label>
                            <input type="text" 
                                   name="state" 
                                   id="state"
                                   value="{{ old('state', $tenant->state) }}"
                                   placeholder="{{ __('settings.placeholders.state') }}"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('state') ring-red-600 dark:ring-red-500 @enderror">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.postal_code') }}
                            </label>
                            <input type="text" 
                                   name="postal_code" 
                                   id="postal_code"
                                   value="{{ old('postal_code', $tenant->postal_code) }}"
                                   placeholder="{{ __('settings.placeholders.postal_code') }}"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('postal_code') ring-red-600 dark:ring-red-500 @enderror">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.country') }}
                            </label>
                            <select name="country_id" 
                                    id="country_id"
                                    class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('country_id') ring-red-600 dark:ring-red-500 @enderror">
                                <option value="">{{ __('common.labels.select') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id', $tenant->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'tr' ? $country->name_tr : $country->name_en }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Settings Tab -->
        <div x-show="activeTab === 'system'" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.currency') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-money-bill-wave text-gray-400"></i>
                                </div>
                                <select name="currency_id" 
                                        id="currency_id"
                                        class="w-full rounded-lg border-0 pl-10 pr-10 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all appearance-none @error('currency_id') ring-red-600 dark:ring-red-500 @enderror">
                                    <option value="">{{ __('common.labels.select') }}</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id', $tenant->currency_id) == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->symbol }} {{ $currency->code }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('currency_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="language_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.default_language') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-language text-gray-400"></i>
                                </div>
                                <select name="language_id" 
                                        id="language_id"
                                        class="w-full rounded-lg border-0 pl-10 pr-10 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all appearance-none @error('language_id') ring-red-600 dark:ring-red-500 @enderror">
                                    <option value="">{{ __('common.labels.select') }}</option>
                                    @foreach($languages as $language)
                                        <option value="{{ $language->id }}" {{ old('language_id', $tenant->language_id) == $language->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() === 'tr' ? $language->name_tr : $language->name_en }} ({{ $language->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('language_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="timezone_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.timezone') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                </div>
                                <select name="timezone_id" 
                                        id="timezone_id"
                                        class="w-full rounded-lg border-0 pl-10 pr-10 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all appearance-none @error('timezone_id') ring-red-600 dark:ring-red-500 @enderror">
                                    <option value="">{{ __('common.labels.select') }}</option>
                                    @php
                                        $regionNames = [
                                            'Africa' => __('settings.regions.africa'),
                                            'America' => __('settings.regions.america'),
                                            'Antarctica' => __('settings.regions.antarctica'),
                                            'Arctic' => __('settings.regions.arctic'),
                                            'Asia' => __('settings.regions.asia'),
                                            'Atlantic' => __('settings.regions.atlantic'),
                                            'Australia' => __('settings.regions.australia'),
                                            'Europe' => __('settings.regions.europe'),
                                            'Indian' => __('settings.regions.indian'),
                                            'Pacific' => __('settings.regions.pacific'),
                                            'UTC' => 'UTC',
                                            'Other' => __('settings.regions.other')
                                        ];
                                    @endphp
                                    @foreach($groupedTimezones as $region => $timezones)
                                        <optgroup label="{{ $regionNames[$region] ?? $region }}">
                                            @foreach($timezones as $timezone)
                                                @php
                                                    $cityName = str_replace($region . '/', '', $timezone->name);
                                                    $cityName = str_replace('_', ' ', $cityName);
                                                @endphp
                                                <option value="{{ $timezone->id }}" {{ old('timezone_id', $tenant->timezone_id) == $timezone->id ? 'selected' : '' }}>
                                                    {{ $cityName }} (UTC{{ $timezone->offset_string }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('timezone_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.date_format') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-calendar text-gray-400"></i>
                                </div>
                                <select name="date_format" 
                                        id="date_format"
                                        class="w-full rounded-lg border-0 pl-10 pr-10 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all appearance-none">
                                    <option value="Y-m-d" {{ old('date_format', $tenant->date_format) == 'Y-m-d' ? 'selected' : '' }}>
                                        {{ now()->format('Y-m-d') }} (Y-m-d)
                                    </option>
                                    <option value="d/m/Y" {{ old('date_format', $tenant->date_format) == 'd/m/Y' ? 'selected' : '' }}>
                                        {{ now()->format('d/m/Y') }} (d/m/Y)
                                    </option>
                                    <option value="m/d/Y" {{ old('date_format', $tenant->date_format) == 'm/d/Y' ? 'selected' : '' }}>
                                        {{ now()->format('m/d/Y') }} (m/d/Y)
                                    </option>
                                    <option value="d.m.Y" {{ old('date_format', $tenant->date_format) == 'd.m.Y' ? 'selected' : '' }}>
                                        {{ now()->format('d.m.Y') }} (d.m.Y)
                                    </option>
                                    <option value="d-m-Y" {{ old('date_format', $tenant->date_format) == 'd-m-Y' ? 'selected' : '' }}>
                                        {{ now()->format('d-m-Y') }} (d-m-Y)
                                    </option>
                                    <option value="M j, Y" {{ old('date_format', $tenant->date_format) == 'M j, Y' ? 'selected' : '' }}>
                                        {{ now()->format('M j, Y') }} (M j, Y)
                                    </option>
                                    <option value="F j, Y" {{ old('date_format', $tenant->date_format) == 'F j, Y' ? 'selected' : '' }}>
                                        {{ now()->format('F j, Y') }} (F j, Y)
                                    </option>
                                    <option value="j F Y" {{ old('date_format', $tenant->date_format) == 'j F Y' ? 'selected' : '' }}>
                                        {{ now()->format('j F Y') }} (j F Y)
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="time_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.time_format') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                </div>
                                <select name="time_format" 
                                        id="time_format"
                                        class="w-full rounded-lg border-0 pl-10 pr-10 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all appearance-none">
                                    <option value="H:i" {{ old('time_format', $tenant->time_format) == 'H:i' ? 'selected' : '' }}>
                                        {{ now()->format('H:i') }} (24 saat)
                                    </option>
                                    <option value="H:i:s" {{ old('time_format', $tenant->time_format) == 'H:i:s' ? 'selected' : '' }}>
                                        {{ now()->format('H:i:s') }} (24 saat + saniye)
                                    </option>
                                    <option value="g:i A" {{ old('time_format', $tenant->time_format) == 'g:i A' ? 'selected' : '' }}>
                                        {{ now()->format('g:i A') }} (12 saat)
                                    </option>
                                    <option value="g:i:s A" {{ old('time_format', $tenant->time_format) == 'g:i:s A' ? 'selected' : '' }}>
                                        {{ now()->format('g:i:s A') }} (12 saat + saniye)
                                    </option>
                                    <option value="h:i A" {{ old('time_format', $tenant->time_format) == 'h:i A' ? 'selected' : '' }}>
                                        {{ now()->format('h:i A') }} (12 saat, 0 başlıklı)
                                    </option>
                                    <option value="h:i:s A" {{ old('time_format', $tenant->time_format) == 'h:i:s A' ? 'selected' : '' }}>
                                        {{ now()->format('h:i:s A') }} (12 saat + saniye, 0 başlıklı)
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branding Tab -->
        <div x-show="activeTab === 'branding'" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 space-y-6">
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-info-circle text-amber-600 dark:text-amber-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-800 dark:text-amber-200">
                                    {{ __('settings.branding.coming_soon') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.logo') }}
                            </label>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 mb-3"></i>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('settings.branding.logo_placeholder') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('settings.fields.favicon') }}
                            </label>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                <i class="fa-solid fa-file-image text-4xl text-gray-400 mb-3"></i>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('settings.branding.favicon_placeholder') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">ICO, PNG 32x32px</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Save Button & Info -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Tenant Info -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-fingerprint text-gray-400"></i>
                    <span class="font-medium">ID:</span>
                    <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $tenant->id }}</code>
                </div>
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-clock text-gray-400"></i>
                    <span>{{ __('common.fields.created_at') }}:</span>
                    <span>@timezone($tenant->created_at)</span>
                </div>
                @if($tenant->status === 'trial' && $tenant->trial_ends_at)
                    <div class="flex items-center gap-1 text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-hourglass-half"></i>
                        <span>{{ __('settings.subscription.trial_ends') }}:</span>
                        <span>@timezoneDate($tenant->trial_ends_at)</span>
                    </div>
                @endif
            </div>

            <!-- Save Button -->
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm hover:shadow-md">
                <i class="fa-solid fa-check mr-2"></i>
                {{ __('common.actions.save_changes') }}
            </button>
        </div>
    </form>
</div>

<script>
function settingsPage() {
    return {
        activeTab: 'general',
        countries: @json($countries),
        selectedCountry: @json($currentCountry),
        phoneNumber: '{{ $phoneNumber ?? '' }}',
        showCountryDropdown: false,
        countrySearch: '',
        locale: '{{ app()->getLocale() }}',
        
        init() {
            // URL'den tab parametresini kontrol et
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab && ['general', 'contact', 'system', 'branding'].includes(tab)) {
                this.activeTab = tab;
            }
            
            // Tab değiştiğinde URL'yi güncelle
            this.$watch('activeTab', (value) => {
                const url = new URL(window.location);
                url.searchParams.set('tab', value);
                window.history.pushState({}, '', url);
            });
        },
        
        get filteredCountries() {
            if (!this.countrySearch) {
                return this.countries;
            }
            const search = this.countrySearch.toLowerCase();
            return this.countries.filter(country => {
                const name = this.locale === 'tr' ? country.name_tr : country.name_en;
                return name.toLowerCase().includes(search) ||
                       country.phone_code.includes(search);
            });
        },
        
        selectCountry(country) {
            this.selectedCountry = country;
            this.showCountryDropdown = false;
            this.countrySearch = '';
            this.phoneNumber = '';
        },
        
        getCountryName(country) {
            return this.locale === 'tr' ? country.name_tr : country.name_en;
        }
    }
}
</script>
@endsection 