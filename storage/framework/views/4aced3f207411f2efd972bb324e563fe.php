

<?php $__env->startSection('title', __('user.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e(__('user.title')); ?></h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo e(__('user.subtitle')); ?></p>
            </div>
            <a href="<?php echo e(route('portal.user.create')); ?>" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium text-sm transition-colors duration-150">
                <i class="fa-solid fa-plus text-xs"></i>
                <?php echo e(__('user.actions.add')); ?>

            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6" x-data="userFilters()">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="sm:col-span-2 lg:col-span-2">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           placeholder="<?php echo e(__('user.labels.search_placeholder')); ?>" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150 text-sm"
                           x-model="search">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150 text-sm"
                        x-model="statusFilter">
                    <option value=""><?php echo e(__('user.labels.all_statuses')); ?></option>
                    <option value="active"><?php echo e(__('user.statuses.active')); ?></option>
                    <option value="inactive"><?php echo e(__('user.statuses.inactive')); ?></option>
                    <option value="suspended"><?php echo e(__('user.statuses.suspended')); ?></option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150 text-sm"
                        x-model="typeFilter">
                    <option value=""><?php echo e(__('user.labels.all_types')); ?></option>
                    <option value="admin"><?php echo e(__('user.types.admin')); ?></option>
                    <option value="screener"><?php echo e(__('user.types.screener')); ?></option>
                    <option value="operator"><?php echo e(__('user.types.operator')); ?></option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <!-- Table Header (Desktop) -->
        <div class="hidden lg:block">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <?php echo e(__('user.table.user')); ?>

                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <?php echo e(__('user.table.type')); ?>

                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <?php echo e(__('user.table.status')); ?>

                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <?php echo e(__('user.table.last_login')); ?>

                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <?php echo e(__('user.table.actions')); ?>

                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <!-- User Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                <?php echo e(substr($user->full_name, 0, 1)); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            <?php echo e($user->full_name); ?>

                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($user->email); ?>

                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Type -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($user->type === 'admin'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    <?php elseif($user->type === 'screener'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    <?php else: ?> bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 <?php endif; ?>">
                                    <?php echo e($user->type_text); ?>

                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($user->status === 'active'): ?> bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    <?php elseif($user->status === 'inactive'): ?> bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                    <?php else: ?> bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 <?php endif; ?>">
                                    <?php echo e($user->status_text); ?>

                                </span>
                            </td>

                            <!-- Last Login -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                <?php if($user->last_login_at): ?>
                                    <span title="<?php echo dt($user->last_login_at)->toDateTimeString(); ?>">
                                        <?php echo dt($user->last_login_at)->toRelativeString(); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 dark:text-gray-500"><?php echo e(__('user.labels.never_logged')); ?></span>
                                <?php endif; ?>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('portal.user.show', $user)); ?>" 
                                       class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors duration-150"
                                       title="<?php echo e(__('user.actions.view')); ?>">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('portal.user.edit', $user)); ?>" 
                                       class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors duration-150"
                                       title="<?php echo e(__('user.actions.edit')); ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('portal.user.destroy', $user)); ?>" class="inline" 
                                          onsubmit="return confirm('<?php echo e(__('user.messages.confirm_delete')); ?>')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-150"
                                                title="<?php echo e(__('user.actions.delete')); ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <i class="fa-solid fa-users text-4xl mb-4"></i>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1"><?php echo e(__('user.messages.no_users_found')); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(__('user.messages.try_adjusting_search_or_filter_criteria')); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="lg:hidden">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <!-- User Header -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                <?php echo e(substr($user->full_name, 0, 1)); ?>

                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 dark:text-white truncate">
                                <?php echo e($user->full_name); ?>

                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                <?php echo e($user->email); ?>

                            </div>
                        </div>
                    </div>

                    <!-- Mobile Details -->
                    <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 text-xs"><?php echo e(__('user.labels.type')); ?></span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    <?php if($user->type === 'admin'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    <?php elseif($user->type === 'screener'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    <?php else: ?> bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 <?php endif; ?>">
                                    <?php echo e($user->type_text); ?>

                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 text-xs"><?php echo e(__('user.labels.status')); ?></span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    <?php if($user->status === 'active'): ?> bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    <?php elseif($user->status === 'inactive'): ?> bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                    <?php else: ?> bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 <?php endif; ?>">
                                    <?php echo e($user->status_text); ?>

                                </span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-500 dark:text-gray-400 text-xs"><?php echo e(__('user.labels.last_login')); ?></span>
                            <div class="mt-1 text-gray-900 dark:text-white">
                                <?php if($user->last_login_at): ?>
                                    <?php echo dt($user->last_login_at)->toRelativeString(); ?>
                                <?php else: ?>
                                    <span class="text-gray-400 dark:text-gray-500 text-sm"><?php echo e(__('user.labels.never_logged')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Actions -->
                    <div class="flex items-center gap-2">
                        <a href="<?php echo e(route('portal.user.show', $user)); ?>" 
                           class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-center text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-150">
                            <i class="fa-solid fa-eye mr-1"></i><?php echo e(__('user.actions.view')); ?>

                        </a>
                        <a href="<?php echo e(route('portal.user.edit', $user)); ?>" 
                           class="flex-1 px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-md text-center text-sm font-medium hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-150">
                            <i class="fa-solid fa-pen-to-square mr-1"></i><?php echo e(__('user.actions.edit')); ?>

                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <!-- Empty State Mobile -->
                <div class="p-8 text-center">
                    <div class="text-gray-400 dark:text-gray-500">
                        <i class="fa-solid fa-users text-4xl mb-4"></i>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-1"><?php echo e(__('user.messages.no_users_found')); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4"><?php echo e(__('user.messages.try_adjusting_search_or_filter_criteria')); ?></p>
                        <a href="<?php echo e(route('portal.user.create')); ?>" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium text-sm transition-colors duration-150">
                            <i class="fa-solid fa-plus"></i>
                            <?php echo e(__('user.actions.add_first_user')); ?>

                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pagination -->
    <?php if($users->hasPages()): ?>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <?php echo e(__('user.labels.showing')); ?> <?php echo e($users->firstItem()); ?> <?php echo e(__('user.labels.to')); ?> <?php echo e($users->lastItem()); ?> <?php echo e(__('user.labels.of')); ?> <?php echo e($users->total()); ?> <?php echo e(__('user.labels.results')); ?>

                </div>
                <div class="flex items-center gap-2">
                    <?php echo e($users->links()); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Success Message -->
<?php if(session('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.notify('<?php echo e(session('success')); ?>', 'success');
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.notify('<?php echo e(session('error')); ?>', 'error');
        });
    </script>
<?php endif; ?>

<!-- Alpine.js Component -->
<script>
function userFilters() {
    return {
        search: '<?php echo e(request('search')); ?>',
        statusFilter: '<?php echo e(request('status')); ?>',
        typeFilter: '<?php echo e(request('type')); ?>',
        
        init() {
            // Auto-submit form when filters change
            this.$watch('search', () => this.applyFilters());
            this.$watch('statusFilter', () => this.applyFilters());
            this.$watch('typeFilter', () => this.applyFilters());
        },
        
        applyFilters() {
            // Debounce search input
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.submitFilters();
            }, 300);
        },
        
        submitFilters() {
            const url = new URL(window.location.href);
            const params = new URLSearchParams();
            
            if (this.search) params.set('search', this.search);
            if (this.statusFilter) params.set('status', this.statusFilter);
            if (this.typeFilter) params.set('type', this.typeFilter);
            
            url.search = params.toString();
            window.location.href = url.toString();
        }
    }
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\portal\user\index.blade.php ENDPATH**/ ?>