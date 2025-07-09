<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(__('site.auth.login_title')); ?> - <?php echo e(config('app.name', 'Meet2Be')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/site/site.css', 'resources/js/site/site.js']); ?>
</head>
<body class="font-sans antialiased bg-stone-900">
    <div class="flex min-h-screen bg-stone-900">
        <div class="flex-1 flex justify-center items-center bg-stone-900">
            <div class="w-80 max-w-80 space-y-6">
                <div class="flex justify-center">
                    <a href="/" class="group flex items-center gap-3">
                        <div class="h-12 w-12 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-semibold text-white"><?php echo e(config('app.name', 'Meet2Be')); ?></span>
                    </a>
                </div>
                
                                    <h1 class="text-center text-white text-2xl font-semibold"><?php echo e(__('site.auth.welcome_back')); ?></h1>
                
                <!-- Success Alert -->
                <?php if(session('success')): ?>
                    <div class="rounded-lg border border-green-500/20 bg-green-500/10 p-4" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-check text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-400">
                                    <?php echo e(session('success')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- General Error Alert -->
                <?php if($errors->any()): ?>
                    <div class="rounded-lg border border-red-500/20 bg-red-500/10 p-4" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-exclamation text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-400">
                                    <?php echo e(__('site.auth.input_errors')); ?>

                                </h3>
                                <div class="mt-2 text-sm text-red-300">
                                    <ul class="list-disc space-y-1 pl-5">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo e(route('site.auth.login.store')); ?>" class="flex flex-col gap-6" x-data="loginForm()">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-stone-200 mb-1">
                                                            <?php echo e(__('site.auth.email_label')); ?>

                        </label>
                        <input 
                            id="email"
                            name="email"
                            type="email"
                            placeholder="email@example.com"
                            value="<?php echo e(old('email')); ?>"
                            required
                            autofocus
                            autocomplete="email"
                            class="w-full rounded-lg bg-stone-800 border <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-stone-700 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> px-4 py-2 text-white placeholder-stone-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        />
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-sm font-medium text-stone-200">
                                <?php echo e(__('site.auth.password_label')); ?>

                            </label>
                            <a href="#" class="text-sm text-indigo-400 hover:text-indigo-300">
                                <?php echo e(__('site.auth.forgot_password')); ?>

                            </a>
                        </div>
                        <div class="relative">
                            <input 
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="<?php echo e(__('site.auth.password_placeholder')); ?>"
                                required
                                autocomplete="current-password"
                                class="w-full rounded-lg bg-stone-800 border <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-stone-700 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> px-4 py-2 pr-10 text-white placeholder-stone-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                            />
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-stone-200"
                            >
                                <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            id="remember"
                            name="remember"
                            type="checkbox"
                            value="1"
                            class="h-4 w-4 rounded border-stone-700 bg-stone-800 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0"
                        />
                        <label for="remember" class="ml-2 block text-sm text-stone-200">
                            <?php echo e(__('site.auth.remember_me')); ?>

                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-stone-900 transition-colors"
                        :disabled="isSubmitting"
                    >
                        <span x-show="!isSubmitting"><?php echo e(__('site.auth.login_button')); ?></span>
                        <span x-show="isSubmitting" class="flex items-center justify-center">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                            <?php echo e(__('site.auth.loading')); ?>

                        </span>
                    </button>
                </form>
                
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-stone-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-stone-900 text-stone-400"><?php echo e(__('site.auth.or')); ?></span>
                    </div>
                </div>
                
                <p class="text-center text-stone-300 text-sm">
                    <?php echo e(__('site.auth.first_time')); ?> 
                    <a href="#" class="text-indigo-400 hover:text-indigo-300"><?php echo e(__('site.auth.sign_up_free')); ?></a>
                </p>
            </div>
        </div>
        
        <div class="flex-1 p-4 max-lg:hidden">
            <div class="text-white relative rounded-lg h-full w-full overflow-hidden flex flex-col items-start justify-end p-16"
                 style="background-image: url('<?php echo e(asset('assets/images/site/auth_bg.png')); ?>'); background-size: cover; background-position: center;">
                <!-- Dark overlay for better text readability -->
                <div class="absolute inset-0 bg-black/40 rounded-lg"></div>
                
                <!-- Content -->
                <div class="relative z-10">
                    <div class="flex gap-2 mb-4">
                        <?php for($i = 0; $i < 5; $i++): ?>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="mb-6 italic font-base text-3xl xl:text-4xl">
                        <?php echo e(__('site.testimonials.quote')); ?>

                    </div>
                    <div class="flex gap-4">
                        <img src="<?php echo e(asset('assets/images/site/ismail_celik.png')); ?>" alt="Prof. Dr. İsmail Çelik" class="h-14 w-14 rounded-full">
                        <div class="flex flex-col justify-center font-medium">
                            <div class="text-lg"><?php echo e(__('site.testimonials.author')); ?></div>
                            <div class="text-stone-300"><?php echo e(__('site.testimonials.position')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function loginForm() {
            return {
                showPassword: false,
                isSubmitting: false,
                
                init() {
                    this.$el.addEventListener('submit', () => {
                        this.isSubmitting = true;
                    });
                }
            }
        }
    </script>
</body>
</html> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\site\auth\login.blade.php ENDPATH**/ ?>