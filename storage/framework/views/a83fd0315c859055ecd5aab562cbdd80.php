



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => ''
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
    'label' => null,
    'value' => '1',
    'checked' => false,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Size classes
    $sizes = [
        'sm' => 'h-3.5 w-3.5',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5'
    ];
    
    $labelSizes = [
        'sm' => 'text-sm',
        'md' => 'text-sm',
        'lg' => 'text-base'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $labelSizeClass = $labelSizes[$size] ?? $labelSizes['md'];
    
    // Generate unique ID
    $checkboxId = $attributes->get('id') ?? ($name ? $name . '_' . uniqid() : 'checkbox_' . uniqid());
    
    // Determine if checked
    $isChecked = old($name) ? old($name) == $value : $checked;
    
    // Determine if there's an error
    $hasError = $errors->has($name);
    
    // Build classes
    $baseClasses = 'rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:checked:bg-blue-600 transition-colors duration-150';
    
    if ($hasError) {
        $baseClasses = str_replace('border-gray-300 dark:border-gray-600', 'border-red-300 dark:border-red-400', $baseClasses);
        $baseClasses = str_replace('focus:border-blue-500 focus:ring-blue-500', 'focus:border-red-500 focus:ring-red-500', $baseClasses);
    }
    
    if ($disabled) {
        $baseClasses .= ' cursor-not-allowed opacity-60';
    } else {
        $baseClasses .= ' cursor-pointer';
    }
    
    // Combine all classes
    $finalClasses = trim("$baseClasses $sizeClass");
?>

<div class="<?php echo e($wrapperClass); ?>">
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input 
                type="checkbox"
                name="<?php echo e($name); ?>"
                id="<?php echo e($checkboxId); ?>"
                value="<?php echo e($value); ?>"
                <?php if($model): ?>
                    x-model="<?php echo e($model); ?>"
                <?php elseif($isChecked): ?>
                    checked
                <?php endif; ?>
                <?php if($required): ?> required <?php endif; ?>
                <?php if($disabled): ?> disabled <?php endif; ?>
                <?php echo e($attributes->except(['class'])->merge(['class' => $finalClasses])); ?>

            />
        </div>
        
        <?php if($label): ?>
            <div class="ml-3">
                <label for="<?php echo e($checkboxId); ?>" class="font-medium text-gray-700 dark:text-gray-300 <?php echo e($labelSizeClass); ?> <?php echo e($disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer'); ?>">
                    <?php echo e($label); ?>

                    <?php if($required): ?>
                        <span class="text-red-500 ml-0.5">*</span>
                    <?php endif; ?>
                </label>
                
                <?php if($hint): ?>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?php echo e($hint); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\checkbox.blade.php ENDPATH**/ ?>