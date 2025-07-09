{{-- Meet2Be: Notification Button Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Notification bell with badge --}}

<button class="relative p-1.5 sm:p-2 rounded-lg transition-colors duration-150"
        aria-label="{{ __('portal.header.notifications') }}">
    <i class="fa-solid fa-bell text-base sm:text-lg text-gray-600 dark:text-gray-400"></i>
    <span class="absolute top-1 right-1 sm:top-1.5 sm:right-1.5 h-2 w-2 rounded-full bg-red-500 dark:bg-red-400 ring-2 ring-white dark:ring-gray-800"></span>
</button> 