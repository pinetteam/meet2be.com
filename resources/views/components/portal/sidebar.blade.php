{{-- Meet2Be: Portal Sidebar Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Reusable sidebar navigation component --}}

@props([
    'navigation' => []
])

<aside :class="{'translate-x-0': mobileMenuOpen, '-translate-x-full': !mobileMenuOpen}"
       class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto">

    {{-- Logo --}}
    <div class="flex h-16 items-center justify-between px-6 border-b border-gray-200 dark:border-gray-700">
        <a href="/portal" class="flex items-center space-x-2">
            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 flex items-center justify-center shadow-sm">
                <span class="text-white font-bold text-sm">M2B</span>
            </div>
            <span class="text-xl font-semibold text-gray-900 dark:text-white">Meet2Be</span>
        </a>
        <button @click="mobileMenuOpen = false" class="lg:hidden p-2 rounded-lg transition-colors duration-150">
            <i class="fa-solid fa-xmark text-lg text-gray-500 dark:text-gray-400"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto">
        <div class="py-4">
            <div class="px-3">
                <template x-for="(item, index) in navigation" :key="index">
                    <div>
                        {{-- Single item without children --}}
                        <template x-if="!item.children">
                            <a :href="item.href"
                               @click="currentPage = item.name"
                               :class="{
                                   'sidebar-nav-item': true,
                                   'active': currentPage === item.name,
                                   'text-gray-700 dark:text-gray-300': currentPage !== item.name,
                                   'text-blue-700 dark:text-blue-200': currentPage === item.name
                               }">
                                <i :class="[item.icon, currentPage === item.name ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500']"
                                   class="mr-3 text-base w-5 text-center"></i>
                                <span x-text="item.label"></span>
                            </a>
                        </template>

                        {{-- Item with children --}}
                        <template x-if="item.children">
                            <div>
                                <button @click="toggleSubmenu(item.name)"
                                        :class="{
                                            'sidebar-nav-item w-full justify-between': true,
                                            'active': isSubmenuOpen(item.name) || hasActiveChild(item),
                                            'text-gray-700 dark:text-gray-300': !(isSubmenuOpen(item.name) || hasActiveChild(item)),
                                            'text-gray-900 dark:text-white': isSubmenuOpen(item.name) || hasActiveChild(item)
                                        }">
                                    <div class="flex items-center">
                                        <i :class="[item.icon, isSubmenuOpen(item.name) || hasActiveChild(item) ? 'text-gray-600 dark:text-gray-400' : 'text-gray-400 dark:text-gray-500']"
                                           class="mr-3 text-base w-5 text-center"></i>
                                        <span x-text="item.label"></span>
                                    </div>
                                    <i :class="{'rotate-90': isSubmenuOpen(item.name)}"
                                       class="fa-solid fa-chevron-right text-xs text-gray-400 dark:text-gray-500 transition-transform duration-500 ease-in-out"></i>
                                </button>
                                
                                <div x-show="isSubmenuOpen(item.name)"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 max-h-0"
                                     x-transition:enter-end="opacity-100 max-h-96"
                                     x-transition:leave="transition ease-in duration-300"
                                     x-transition:leave-start="opacity-100 max-h-96"
                                     x-transition:leave-end="opacity-0 max-h-0"
                                     class="overflow-hidden">
                                    <div class="py-1">
                                        <template x-for="(child, childIndex) in item.children" :key="childIndex">
                                            <a :href="child.href"
                                               @click="currentPage = child.name"
                                               :class="{
                                                   'sidebar-submenu-item': true,
                                                   'active': currentPage === child.name,
                                                   'text-gray-600 dark:text-gray-400': currentPage !== child.name,
                                                   'text-blue-700 dark:text-blue-200': currentPage === child.name
                                               }">
                                                <i :class="[child.icon || 'fa-solid fa-circle', currentPage === child.name ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500']"
                                                   class="mr-3 w-4 text-center"
                                                   :style="!child.icon ? 'font-size: 6px;' : ''"></i>
                                                <span x-text="child.label"></span>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Bottom navigation items --}}
            <div class="mt-auto pt-4 pb-2">
                <div class="px-3 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-1">
                    {{-- Users --}}
                    <a href="/portal/user" 
                       @click="currentPage = 'users'"
                       :class="{
                           'sidebar-nav-item': true,
                           'active': window.location.pathname.includes('/portal/user'),
                           'text-gray-700 dark:text-gray-300': !window.location.pathname.includes('/portal/user'),
                           'text-blue-700 dark:text-blue-200': window.location.pathname.includes('/portal/user')
                       }">
                        <i :class="window.location.pathname.includes('/portal/user') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500'" 
                           class="fa-solid fa-users mr-3 text-base w-5 text-center"></i>
                        {{ __('portal.navigation.users') }}
                    </a>
                    
                    {{-- Settings --}}
                    <a href="{{ route('portal.setting.index') }}" 
                       @click="currentPage = 'settings'"
                       :class="{
                           'sidebar-nav-item': true,
                           'active': window.location.pathname.includes('/portal/setting'),
                           'text-gray-700 dark:text-gray-300': !window.location.pathname.includes('/portal/setting'),
                           'text-blue-700 dark:text-blue-200': window.location.pathname.includes('/portal/setting')
                       }">
                        <i :class="window.location.pathname.includes('/portal/setting') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500'" 
                           class="fa-solid fa-gear mr-3 text-base w-5 text-center"></i>
                        {{ __('portal.navigation.settings') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>
</aside> 