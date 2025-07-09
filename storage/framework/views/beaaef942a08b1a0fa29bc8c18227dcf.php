<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Meet2Be')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome Pro -->
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/site/site.css', 'resources/js/site/site.js']); ?>
</head>
<body class="font-sans antialiased bg-stone-900 text-stone-100">
    <div class="min-h-screen bg-stone-900">
        <!-- Navigation -->
        <nav class="bg-stone-800 shadow-lg border-b border-stone-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-white">
                                <?php echo e(config('app.name', 'Meet2Be')); ?>

                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <?php if(auth()->guard()->check()): ?>
                            <span class="text-stone-300"><?php echo e(Auth::user()->full_name); ?></span>
                            <form method="POST" action="<?php echo e(route('site.auth.logout')); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-stone-200 hover:text-white">
                                    <?php echo e(__('site.common.logout')); ?>

                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('site.auth.login')); ?>" class="text-stone-200 hover:text-white">
                                <?php echo e(__('site.common.login')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\layouts\site.blade.php ENDPATH**/ ?>