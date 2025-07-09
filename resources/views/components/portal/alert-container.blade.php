{{-- Meet2Be: Alert Container Component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Global alert notification container --}}

<div class="fixed top-4 right-4 z-[9999] max-w-md w-full pointer-events-none" x-data>
    <div class="space-y-2">
        <template x-for="alert in $store.alerts.items" :key="alert.id">
            <div class="pointer-events-auto"
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95">
                <div :class="{
                    'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800': alert.type === 'success',
                    'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800': alert.type === 'error',
                    'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800': alert.type === 'warning',
                    'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800': alert.type === 'info'
                }" class="border rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i :class="{
                                'fa-check-circle text-green-400': alert.type === 'success',
                                'fa-exclamation-circle text-red-400': alert.type === 'error',
                                'fa-exclamation-triangle text-yellow-400': alert.type === 'warning',
                                'fa-info-circle text-blue-400': alert.type === 'info'
                            }" class="fas"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 x-show="alert.title" x-text="alert.title" :class="{
                                'text-green-800 dark:text-green-200': alert.type === 'success',
                                'text-red-800 dark:text-red-200': alert.type === 'error',
                                'text-yellow-800 dark:text-yellow-200': alert.type === 'warning',
                                'text-blue-800 dark:text-blue-200': alert.type === 'info'
                            }" class="text-sm font-medium"></h3>
                            
                            <div x-show="alert.message" x-text="alert.message" :class="{
                                'text-green-800 dark:text-green-200': alert.type === 'success',
                                'text-red-800 dark:text-red-200': alert.type === 'error',
                                'text-yellow-800 dark:text-yellow-200': alert.type === 'warning',
                                'text-blue-800 dark:text-blue-200': alert.type === 'info',
                                'mt-2': alert.title
                            }" class="text-sm"></div>
                            
                            <ul x-show="alert.list && alert.list.length > 0" :class="{
                                'text-green-800 dark:text-green-200': alert.type === 'success',
                                'text-red-800 dark:text-red-200': alert.type === 'error',
                                'text-yellow-800 dark:text-yellow-200': alert.type === 'warning',
                                'text-blue-800 dark:text-blue-200': alert.type === 'info',
                                'mt-2': alert.title || alert.message
                            }" class="text-sm list-disc pl-5 space-y-1">
                                <template x-for="item in alert.list" :key="item">
                                    <li x-text="item"></li>
                                </template>
                            </ul>
                        </div>
                        
                        <div x-show="alert.dismissible" class="ml-auto pl-3">
                            <button @click="show = false; setTimeout(() => $store.alerts.remove(alert.id), 300)" 
                                    type="button"
                                    :class="{
                                        'text-green-800 dark:text-green-200': alert.type === 'success',
                                        'text-red-800 dark:text-red-200': alert.type === 'error',
                                        'text-yellow-800 dark:text-yellow-200': alert.type === 'warning',
                                        'text-blue-800 dark:text-blue-200': alert.type === 'info'
                                    }"
                                    class="hover:opacity-75 focus:outline-none transition-opacity">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div> 