@extends('layouts.portal')

@section('title', 'Kullanıcı Detayı')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Kullanıcı Bilgileri
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    Detaylı kullanıcı bilgileri ve hesap durumu
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portal.user.edit', $user) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-edit mr-2"></i>
                    Düzenle
                </a>
                <form action="{{ route('portal.user.destroy', $user) }}" method="POST" class="inline" 
                      onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>
                        Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="border-t border-gray-200 dark:border-gray-700">
        <dl>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Ad Soyad
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 mr-3">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                    </div>
                    {{ $user->full_name }}
                </dd>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Kullanıcı Adı
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    @{{ $user->username }}
                </dd>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    E-posta
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <a href="mailto:{{ $user->email }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                        {{ $user->email }}
                    </a>
                    @if($user->email_verified_at)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <i class="fas fa-check-circle mr-1"></i>
                            Doğrulanmış
                        </span>
                    @else
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Doğrulanmamış
                        </span>
                    @endif
                </dd>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Telefon
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    @if($user->phone)
                        <a href="tel:{{ $user->phone }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                            {{ $user->phone }}
                        </a>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </dd>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Kullanıcı Tipi
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($user->type == 'admin') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @elseif($user->type == 'screener') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                        @elseif($user->type == 'operator') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                        @endif">
                        {{ $user->type_text }}
                    </span>
                </dd>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Hesap Durumu
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <span class="
                        @if($user->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($user->status === 'suspended') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif
                        px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ $user->getStatusText() }}
                    </span>
                </dd>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Son Aktivite
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <div class="flex items-start space-x-3">
                        <div class="rounded-full p-2 bg-gray-100 dark:bg-gray-700">
                            <i class="fa-solid fa-clock w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('user.fields.last_activity') }}</p>
                            <span class="text-gray-900 dark:text-white">@humandiff($user->last_activity)</span>
                            <span class="text-gray-500 dark:text-gray-400 text-xs ml-2">(@datetime($user->last_activity))</span>
                        </div>
                    </div>
                </dd>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Kayıt Tarihi
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <div class="flex items-start space-x-3">
                        <div class="rounded-full p-2 bg-gray-100 dark:bg-gray-700">
                            <i class="fa-solid fa-user-plus w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('common.fields.created_at') }}</p>
                            <span class="text-gray-900 dark:text-white">@datetime($user->created_at)</span>
                            <span class="text-gray-500 dark:text-gray-400 text-xs ml-2">(@humandiff($user->created_at))</span>
                        </div>
                    </div>
                </dd>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Son Güncelleme
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                    @if($user->updated_at->ne($user->created_at))
                    <div class="flex items-start space-x-3">
                        <div class="rounded-full p-2 bg-gray-100 dark:bg-gray-700">
                            <i class="fa-solid fa-edit w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('common.fields.updated_at') }}</p>
                            <span class="text-gray-900 dark:text-white">@datetime($user->updated_at)</span>
                            <span class="text-gray-500 dark:text-gray-400 text-xs ml-2">(@humandiff($user->updated_at))</span>
                        </div>
                    </div>
                    @else
                        <span class="text-gray-400">Güncelleme yapılmadı</span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>
</div>

<div class="mt-8 flex justify-end space-x-3">
    <a href="{{ route('portal.user.index') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-arrow-left mr-2"></i>
        Geri Dön
    </a>
</div>
@endsection 