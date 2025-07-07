@extends('layouts.portal')

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
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ayarlar</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Tenant bilgilerini ve sistem ayarlarını yönetin</p>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fa-light fa-building mr-2"></i>
                Genel Bilgiler
            </button>
            <button @click="activeTab = 'contact'"
                    :class="activeTab === 'contact' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fa-light fa-address-card mr-2"></i>
                İletişim Bilgileri
            </button>
            <button @click="activeTab = 'system'"
                    :class="activeTab === 'system' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fa-light fa-globe mr-2"></i>
                Sistem Ayarları
            </button>
        </nav>
    </div>

    <form action="{{ route('portal.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- General Information Tab -->
        <div x-show="activeTab === 'general'" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tenant Adı <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $tenant->name) }}"
                               required
                               class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('name') ring-red-600 dark:ring-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Şirket Adı
                        </label>
                        <input type="text" 
                               name="company_name" 
                               id="company_name"
                               value="{{ old('company_name', $tenant->company_name) }}"
                               class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('company_name') ring-red-600 dark:ring-red-500 @enderror">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Website
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-light fa-globe text-gray-400"></i>
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6" style="overflow: visible;">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            E-posta Adresi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-light fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email', $tenant->email) }}"
                                   required
                                   class="w-full rounded-lg border-0 pl-10 pr-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('email') ring-red-600 dark:ring-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Telefon
                        </label>
                        <div class="flex rounded-lg shadow-sm relative">
                            <div class="relative flex-shrink-0" style="z-index: 1000;">
                                <button type="button" @click="showCountryDropdown = !showCountryDropdown"
                                    class="relative flex items-center gap-2 h-full px-4 py-3 text-sm font-medium text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-r-0 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 focus:z-10 rounded-l-lg transition-colors">
                                    <img :src="`/assets/images/flags/32x24/${selectedCountry.iso2.toLowerCase()}.png`" 
                                        :alt="selectedCountry.name_en" class="h-4 w-6">
                                    <span x-text="`+${selectedCountry.phone_code}`" class="font-medium whitespace-nowrap"></span>
                                    <i class="fa-solid fa-chevron-down h-3 w-3 text-gray-400"></i>
                                </button>
                                <div x-show="showCountryDropdown" @click.away="showCountryDropdown = false"
                                    x-transition
                                    style="position: absolute; left: 0; top: 100%; margin-top: 0.25rem; z-index: 9999;"
                                    class="w-80 origin-top-left rounded-lg bg-white dark:bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1 max-h-60 overflow-auto">
                                        <input type="text" 
                                            x-model="countrySearch" 
                                            placeholder="Ülke ara..."
                                            autocomplete="off"
                                            class="mx-2 mb-2 mt-1 block w-[calc(100%-16px)] rounded-md border-0 px-3 py-2 text-sm text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-600"
                                            @click.stop>
                                        <template x-for="country in filteredCountries" :key="country.id">
                                            <a href="#" @click.prevent="selectCountry(country)"
                                                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <img :src="`/assets/images/flags/32x24/${country.iso2.toLowerCase()}.png`" 
                                                    :alt="country.name_en" class="h-4 w-6 flex-shrink-0">
                                                <span class="flex-1 truncate">
                                                    <span x-text="country.name_en"></span>
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
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Adres
                        </label>
                        <textarea name="address" 
                                  id="address"
                                  rows="3"
                                  class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('address') ring-red-600 dark:ring-red-500 @enderror">{{ old('address', $tenant->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Şehir
                            </label>
                            <input type="text" 
                                   name="city" 
                                   id="city"
                                   value="{{ old('city', $tenant->city) }}"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('city') ring-red-600 dark:ring-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Eyalet/İl
                            </label>
                            <input type="text" 
                                   name="state" 
                                   id="state"
                                   value="{{ old('state', $tenant->state) }}"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('state') ring-red-600 dark:ring-red-500 @enderror">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Posta Kodu
                            </label>
                            <input type="text" 
                                   name="postal_code" 
                                   id="postal_code"
                                   value="{{ old('postal_code', $tenant->postal_code) }}"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('postal_code') ring-red-600 dark:ring-red-500 @enderror">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Settings Tab -->
        <div x-show="activeTab === 'system'" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Ülke
                        </label>
                        <select name="country_id" 
                                id="country_id"
                                class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('country_id') ring-red-600 dark:ring-red-500 @enderror">
                            <option value="">Seçiniz</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $tenant->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Para Birimi
                        </label>
                        <select name="currency_id" 
                                id="currency_id"
                                class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('currency_id') ring-red-600 dark:ring-red-500 @enderror">
                            <option value="">Seçiniz</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ old('currency_id', $tenant->currency_id) == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->code }} - {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="language_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Varsayılan Dil
                        </label>
                        <select name="language_id" 
                                id="language_id"
                                class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('language_id') ring-red-600 dark:ring-red-500 @enderror">
                            <option value="">Seçiniz</option>
                            @foreach($languages as $language)
                                <option value="{{ $language->id }}" {{ old('language_id', $tenant->language_id) == $language->id ? 'selected' : '' }}>
                                    {{ $language->name_en }} ({{ $language->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('language_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="timezone_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Saat Dilimi
                        </label>
                        <select name="timezone_id" 
                                id="timezone_id"
                                class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-700 transition-all @error('timezone_id') ring-red-600 dark:ring-red-500 @enderror">
                            <option value="">Seçiniz</option>
                            @php
                                $regionNames = [
                                    'Africa' => 'Afrika',
                                    'America' => 'Amerika',
                                    'Antarctica' => 'Antarktika',
                                    'Arctic' => 'Arktik',
                                    'Asia' => 'Asya',
                                    'Atlantic' => 'Atlantik',
                                    'Australia' => 'Avustralya',
                                    'Europe' => 'Avrupa',
                                    'Indian' => 'Hint Okyanusu',
                                    'Pacific' => 'Pasifik',
                                    'UTC' => 'UTC',
                                    'Other' => 'Diğer'
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
                        @error('timezone_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Tenant Info -->
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tenant Bilgileri</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $tenant->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('portal.common.created_at') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">@timezone($tenant->created_at)</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Durum</dt>
                            <dd class="mt-1">
                                @if($tenant->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ __('Aktif') }}
                                    </span>
                                @elseif($tenant->status === 'trial')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ __('Deneme') }}
                                    </span>
                                @elseif($tenant->status === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200">
                                        <i class="fas fa-ban mr-1"></i>
                                        {{ __('Askıda') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        {{ $tenant->getStatusName() }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('portal.common.updated_at') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">@timezone($tenant->updated_at)</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
        
        <!-- Save Button -->
        <div class="mt-6 flex justify-end">
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <i class="fa-solid fa-check mr-2"></i>
                Değişiklikleri Kaydet
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
        
        init() {
            // URL'den tab parametresini kontrol et
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab && ['general', 'contact', 'system'].includes(tab)) {
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
            return this.countries.filter(country => 
                country.name_en.toLowerCase().includes(search) ||
                country.phone_code.includes(search)
            );
        },
        
        selectCountry(country) {
            this.selectedCountry = country;
            this.showCountryDropdown = false;
            this.countrySearch = '';
            this.phoneNumber = '';
        }
    }
}
</script>
@endsection 