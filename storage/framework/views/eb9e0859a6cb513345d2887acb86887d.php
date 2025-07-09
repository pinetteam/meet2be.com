



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'model' => null,
    'size' => 'md',
    'selectClass' => ''
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
    'name',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'model' => null,
    'size' => 'md',
    'selectClass' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Meet2Be: Size classes
    $sizeClasses = [
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base'
    ];
    
    $baseClasses = 'block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-150';
    $currentSizeClasses = $sizeClasses[$size] ?? $sizeClasses['md'];
    
    if ($disabled) {
        $baseClasses .= ' bg-gray-50 dark:bg-gray-600 cursor-not-allowed';
    }
    
    $finalClasses = trim("$baseClasses $currentSizeClasses $selectClass");
?>

<select 
    name="<?php echo e($name); ?><?php echo e($multiple ? '[]' : ''); ?>"
    id="<?php echo e($name); ?>"
    <?php if($model): ?> x-model="<?php echo e($model); ?>" <?php endif; ?>
    <?php if($required): ?> required <?php endif; ?>
    <?php if($disabled): ?> disabled <?php endif; ?>
    <?php if($multiple): ?> multiple <?php endif; ?>
    <?php echo e($attributes->merge(['class' => $finalClasses])); ?>>
    
    <?php if($placeholder && !$multiple): ?>
        <option value=""><?php echo e($placeholder); ?></option>
    <?php endif; ?>
    
    <?php echo e($slot); ?>

</select> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\base\select-base.blade.php ENDPATH**/ ?>