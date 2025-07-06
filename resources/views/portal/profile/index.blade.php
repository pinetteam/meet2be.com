@extends('layouts.portal')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profilim</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kişisel bilgilerinizi görüntüleyin ve güncelleyin</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <form action="{{ route('portal.profile.update') }}" method="POST" class="divide-y divide-gray-200 dark:divide-gray-700">
            @csrf
            @method('PUT')
            
            <!-- Personal Information -->
            <div class="px-6 py-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Kişisel Bilgiler</h2>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name', $user->first_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Soyad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name', $user->last_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Telefon
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="px-6 py-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Hesap Bilgileri</h2>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kullanıcı Adı <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="{{ old('username', $user->username) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            E-posta <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="px-6 py-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Ek Bilgiler</h2>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- User Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kullanıcı Tipi
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                @if($user->type === 'admin')
                                    <i class="fa-solid fa-crown text-amber-500 mr-2"></i>Admin
                                @elseif($user->type === 'screener')
                                    <i class="fa-solid fa-filter text-blue-500 mr-2"></i>Screener
                                @elseif($user->type === 'operator')
                                    <i class="fa-solid fa-headset text-green-500 mr-2"></i>Operator
                                @else
                                    <i class="fa-solid fa-user text-gray-500 mr-2"></i>{{ ucfirst($user->type) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Durum
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                @if($user->status === 'active')
                                    <i class="fa-solid fa-circle text-green-500 text-xs mr-2"></i>Aktif
                                @elseif($user->status === 'inactive')
                                    <i class="fa-solid fa-circle text-gray-500 text-xs mr-2"></i>Pasif
                                @else
                                    <i class="fa-solid fa-ban text-red-500 mr-2"></i>Askıya Alınmış
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Created Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kayıt Tarihi
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                <i class="fa-regular fa-calendar-days mr-2"></i>{{ $user->created_at->format('d.m.Y H:i') }}
                            </span>
                        </div>
                    </div>

                    <!-- Last Login -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Son Giriş
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                <i class="fa-regular fa-clock mr-2"></i>
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                @else
                                    Henüz giriş yapılmadı
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand text-white font-medium rounded-md shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand dark:focus:ring-offset-gray-800 transition-all">
                    <i class="fa-solid fa-save mr-2"></i>
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 