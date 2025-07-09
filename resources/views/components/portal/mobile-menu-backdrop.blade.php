{{-- Meet2Be: Mobile Menu Backdrop Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Mobile menu overlay backdrop --}}

<div x-show="mobileMenuOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileMenuOpen = false"
     class="fixed inset-0 z-40 bg-gray-600/75 dark:bg-gray-900/75 backdrop-blur-sm lg:hidden"
     aria-hidden="true"></div> 