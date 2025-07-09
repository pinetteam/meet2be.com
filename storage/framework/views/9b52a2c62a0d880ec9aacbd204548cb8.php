



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'id' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'maxlength' => null,
    'pattern' => null,
    'model' => null,
    'size' => 'md',
    'icon' => null,
    'prefix' => null,
    'suffix' => null,
    'inputClass' => '',
    'error' => false
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => '',
    'id' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'maxlength' => null,
    'pattern' => null,
    'model' => null,
    'size' => 'md',
    'icon' => null,
    'prefix' => null,
    'suffix' => null,
    'inputClass' => '',
    'error' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $sizes = [
        'sm' => 'px-2.5 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $hasPrefix = $prefix || $icon;
    
    // Use provided ID or generate one
    $inputId = $id ?? ($name ? $name . '_' . uniqid() : 'input_' . uniqid());
    
    // Determine if there's an error
    $hasError = $error || $errors->has($name);
    
    // Build base classes
    $baseClasses = 'block w-full rounded-md shadow-sm transition-colors duration-150 dark:bg-gray-700 dark:text-white';
    
    // Border and focus classes
    if ($hasError) {
        $borderClasses = 'border-red-300 dark:border-red-400 focus:border-red-500 focus:ring-red-500';
    } else {
        $borderClasses = 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500';
    }
    
    // State classes
    $stateClasses = '';
    if ($disabled) {
        $stateClasses = 'bg-gray-50 dark:bg-gray-800 cursor-not-allowed opacity-60';
    } elseif ($readonly) {
        $stateClasses = 'bg-gray-50 dark:bg-gray-800';
    } else {
        $stateClasses = 'bg-white';
    }
    
    // Prefix/suffix padding
    $paddingClasses = $sizeClass;
    if ($hasPrefix) {
        $paddingClasses = preg_replace('/\bpl-\S+/', 'pl-10', $paddingClasses);
    }
    if ($suffix) {
        $paddingClasses = preg_replace('/\bpr-\S+/', 'pr-10', $paddingClasses);
    }
    
    // Combine all classes
    $finalClasses = trim("$baseClasses $borderClasses $stateClasses $paddingClasses $inputClass");
?>

<div class="relative">
    <?php if($hasPrefix): ?>
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <?php if($icon): ?>
                <i class="<?php echo e($icon); ?> text-gray-400 <?php echo e($size === 'sm' ? 'text-sm' : ''); ?>"></i>
            <?php else: ?>
                <span class="text-gray-500 dark:text-gray-400 <?php echo e($size === 'sm' ? 'text-sm' : 'sm:text-sm'); ?>">
                    <?php echo e($prefix); ?>

                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <input
        type="<?php echo e($type); ?>"
        name="<?php echo e($name); ?>"
        id="<?php echo e($inputId); ?>"
        <?php if($model): ?>
            x-model="<?php echo e($model); ?>"
        <?php else: ?>
            value="<?php echo e(old($name, $value)); ?>"
        <?php endif; ?>
        <?php if($placeholder): ?> placeholder="<?php echo e($placeholder); ?>" <?php endif; ?>
        <?php if($required): ?> required <?php endif; ?>
        <?php if($disabled): ?> disabled <?php endif; ?>
        <?php if($readonly): ?> readonly <?php endif; ?>
        <?php if($autofocus): ?> autofocus <?php endif; ?>
        <?php if($autocomplete): ?> autocomplete="<?php echo e($autocomplete); ?>" <?php endif; ?>
        <?php if($maxlength): ?> maxlength="<?php echo e($maxlength); ?>" <?php endif; ?>
        <?php if($pattern): ?> pattern="<?php echo e($pattern); ?>" <?php endif; ?>
        <?php echo e($attributes->except(['class'])->merge(['class' => $finalClasses])); ?>

    />
    
    <?php if($suffix): ?>
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 <?php echo e($size === 'sm' ? 'text-sm' : 'sm:text-sm'); ?>">
                <?php echo e($suffix); ?>

            </span>
        </div>
    <?php endif; ?>
</div> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\base\input-base.blade.php ENDPATH**/ ?>