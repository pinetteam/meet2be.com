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

<div x-data="profilePage()" x-init="init()">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profil Bilgileri</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Hesap bilgilerinizi görüntüleyin ve güncelleyin</p>
    </div>

    <!-- Profile Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Personal Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center">
                            <i class="fa-light fa-user text-indigo-600 dark:text-indigo-400 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Kişisel Bilgiler</h2>
                    </div>
                    <button @click="toggleEdit('personal')" 
                            class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                        <i :class="editing.personal ? 'fa-solid fa-xmark' : 'fa-light fa-pen-to-square'" class="mr-1"></i>
                        <span x-text="editing.personal ? 'İptal' : 'Düzenle'"></span>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="personal">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Ad</label>
                            <div class="relative">
                                <input type="text" 
                                       id="first_name"
                                       name="first_name" 
                                       value="{{ $user->first_name }}"
                                       autocomplete="given-name"
                                       :readonly="!editing.personal"
                                       :class="editing.personal ? '' : 'bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed'"
                                       class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 transition-all">
                                <div x-show="!editing.personal" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fa-light fa-id-card text-gray-400"></i>
                                </div>
                            </div>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="last_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Soyad</label>
                            <div class="relative">
                                <input type="text" 
                                       id="last_name"
                                       name="last_name" 
                                       value="{{ $user->last_name }}"
                                       autocomplete="family-name"
                                       :readonly="!editing.personal"
                                       :class="editing.personal ? '' : 'bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed'"
                                       class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 transition-all">
                                <div x-show="!editing.personal" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fa-light fa-id-card text-gray-400"></i>
                                </div>
                            </div>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="username" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Kullanıcı Adı</label>
                        <div class="relative">
                            <input type="text" 
                                   id="username"
                                   name="username" 
                                   value="{{ $user->username }}"
                                   autocomplete="username"
                                   :readonly="!editing.personal"
                                   :class="editing.personal ? '' : 'bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed'"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 transition-all">
                            <div x-show="!editing.personal" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-light fa-at text-gray-400"></i>
                            </div>
                        </div>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div x-show="editing.personal" x-transition class="pt-4 flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            <i class="fa-solid fa-check mr-2"></i>
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                            <i class="fa-light fa-address-card text-emerald-600 dark:text-emerald-400 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">İletişim Bilgileri</h2>
                    </div>
                    <button @click="toggleEdit('contact')" 
                            class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                        <i :class="editing.contact ? 'fa-solid fa-xmark' : 'fa-light fa-pen-to-square'" class="mr-1"></i>
                        <span x-text="editing.contact ? 'İptal' : 'Düzenle'"></span>
                    </button>
                </div>
            </div>
            
            <div class="p-6" style="overflow: visible;">
                <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-4 overflow-visible">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="contact">
                    
                    <div>
                        <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">E-posta Adresi</label>
                        <div class="relative">
                            <input type="email" 
                                   id="email"
                                   name="email" 
                                   value="{{ $user->email }}"
                                   autocomplete="email"
                                   :readonly="!editing.contact"
                                   :class="editing.contact ? '' : 'bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed'"
                                   class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 transition-all">
                            <div x-show="!editing.contact" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-light fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Telefon Numarası</label>
                        <div x-show="!editing.contact" class="relative">
                            <div class="w-full rounded-lg border-0 px-4 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    @if($user->phone)
                                        <img src="/assets/images/flags/32x24/{{ strtolower($currentCountry->iso2) }}.png" 
                                             alt="{{ $currentCountry->name_en }}" 
                                             class="h-4 w-6">
                                        <span>+{{ $currentCountry->phone_code }} {{ $phoneNumber }}</span>
                                    @else
                                        <span class="text-gray-500">Belirtilmemiş</span>
                                    @endif
                                </span>
                                <i class="fa-light fa-phone text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div x-show="editing.contact" x-transition>
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
                                                id="country-search"
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
                                <input type="text" name="phone" id="phone" 
                                    x-model="phoneNumber"
                                    x-mask="999999999999999"
                                    placeholder="1234567890"
                                    maxlength="15"
                                    autocomplete="tel-national"
                                    class="block w-full min-w-0 flex-1 rounded-none rounded-r-lg border border-l-0 px-4 py-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 dark:bg-gray-800 transition-all">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div x-show="editing.contact" x-transition class="pt-4 flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            <i class="fa-solid fa-check mr-2"></i>
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center">
                        <i class="fa-light fa-shield-halved text-red-600 dark:text-red-400 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Güvenlik</h2>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Şifre</span>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle text-green-500 text-xs"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Şifreniz güvenli</span>
                        </div>
                        <button @click="toggleEdit('password')" 
                                class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                            <i class="fa-light fa-key mr-1"></i>
                            Değiştir
                        </button>
                    </div>
                </div>
                
                <!-- Password Change Form -->
                <div x-show="editing.password" x-transition class="mt-4">
                    <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="password">
                        
                        <div>
                            <label for="current_password_inline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Mevcut Şifre
                            </label>
                            <div class="relative">
                                <input :type="showPasswords.current ? 'text' : 'password'" 
                                       name="current_password" 
                                       id="current_password_inline"
                                       placeholder="Mevcut şifrenizi girin"
                                       autocomplete="current-password"
                                       class="w-full rounded-lg border-0 px-4 py-3 pr-12 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 transition-all @error('current_password') ring-red-600 dark:ring-red-500 @enderror">
                                <button type="button" @click="showPasswords.current = !showPasswords.current" 
                                        class="absolute inset-y-0 right-0 w-10 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <i :class="showPasswords.current ? 'fa-light fa-eye-slash' : 'fa-light fa-eye'"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_inline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Yeni Şifre
                            </label>
                            <div class="relative">
                                <input :type="showPasswords.new ? 'text' : 'password'" 
                                       name="password" 
                                       id="password_inline"
                                       placeholder="Yeni şifrenizi girin"
                                       autocomplete="new-password"
                                       class="w-full rounded-lg border-0 px-4 py-3 pr-12 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 transition-all @error('password') ring-red-600 dark:ring-red-500 @enderror">
                                <button type="button" @click="showPasswords.new = !showPasswords.new" 
                                        class="absolute inset-y-0 right-0 w-10 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <i :class="showPasswords.new ? 'fa-light fa-eye-slash' : 'fa-light fa-eye'"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">En az 8 karakter, büyük/küçük harf, rakam ve özel karakter içermelidir.</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation_inline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Yeni Şifre (Tekrar)
                            </label>
                            <div class="relative">
                                <input :type="showPasswords.confirm ? 'text' : 'password'" 
                                       name="password_confirmation" 
                                       id="password_confirmation_inline"
                                       placeholder="Yeni şifrenizi tekrar girin"
                                       autocomplete="new-password"
                                       class="w-full rounded-lg border-0 px-4 py-3 pr-12 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 transition-all @error('password_confirmation') ring-red-600 dark:ring-red-500 @enderror">
                                <button type="button" @click="showPasswords.confirm = !showPasswords.confirm" 
                                        class="absolute inset-y-0 right-0 w-10 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <i :class="showPasswords.confirm ? 'fa-light fa-eye-slash' : 'fa-light fa-eye'"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="pt-4 flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                <i class="fa-solid fa-check mr-2"></i>
                                Şifreyi Değiştir
                            </button>
                        </div>
                    </form>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">İki Faktörlü Doğrulama</span>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle text-yellow-500 text-xs"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Aktif değil</span>
                        </div>
                        <button class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                            <i class="fa-light fa-mobile-screen mr-1"></i>
                            Etkinleştir
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                        <i class="fa-light fa-circle-info text-purple-600 dark:text-purple-400 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Hesap Durumu</h2>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Kullanıcı Tipi</span>
                    <div class="flex items-center gap-2">
                        @if($user->type === 'admin')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                <i class="fa-solid fa-crown"></i>
                                Yönetici
                            </span>
                        @elseif($user->type === 'screener')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                <i class="fa-solid fa-filter"></i>
                                Screener
                            </span>
                        @elseif($user->type === 'operator')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <i class="fa-solid fa-headset"></i>
                                Operatör
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                <i class="fa-solid fa-user"></i>
                                Kullanıcı
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Hesap Durumu</span>
                    <div class="flex items-center gap-2">
                        @if($user->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <i class="fa-solid fa-circle text-xs"></i>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                <i class="fa-solid fa-circle text-xs"></i>
                                Pasif
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Kayıt Tarihi</span>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="fa-light fa-calendar-days text-gray-400"></i>
                        <span>@timezone($user->created_at)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
function profilePage() {
    return {
        editing: {
            personal: false,
            contact: false,
            password: false
        },
        showPasswords: {
            current: false,
            new: false,
            confirm: false
        },
        
        init() {
            // Şifre hataları varsa password editing'i aç
            if ({{ $errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') ? 'true' : 'false' }}) {
                this.editing.password = true;
            }
            
            // Debug için
            console.log('Countries:', this.countries);
            console.log('Selected Country:', this.selectedCountry);
        },
        countries: @json($countries),
        selectedCountry: @json($currentCountry),
        phoneNumber: '{{ $phoneNumber ?? '' }}',
        showCountryDropdown: false,
        countrySearch: '',
        
        toggleEdit(section) {
            // Diğer sectionları kapat
            Object.keys(this.editing).forEach(key => {
                if (key !== section) {
                    this.editing[key] = false;
                }
            });
            this.editing[section] = !this.editing[section];
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