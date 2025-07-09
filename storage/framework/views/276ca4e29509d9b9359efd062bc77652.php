



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'label' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'wrapperClass' => '',
    'labelClass' => '',
    'errorClass' => '',
    'hintClass' => '',
    'fieldId' => null
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
    'required' => false,
    'hint' => null,
    'error' => null,
    'wrapperClass' => '',
    'labelClass' => '',
    'errorClass' => '',
    'hintClass' => '',
    'fieldId' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Generate a unique ID if not provided
    $fieldId = $fieldId ?? ($name ? $name . '_' . uniqid() : 'field_' . uniqid());
    
    // Get error from validation errors bag
    $error = $error ?? $errors->first($name);
?>

<div class="space-y-1 <?php echo e($wrapperClass); ?>" <?php echo e($attributes->only(['x-data', 'x-show', 'x-if'])); ?>>
    <?php if($label): ?>
        <label 
            for="<?php echo e($fieldId); ?>" 
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 <?php echo e($labelClass); ?>"
        >
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-red-500 ml-0.5">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <div>
        <?php echo e($slot); ?>

    </div>
    
    <?php if($error): ?>
        <p class="text-sm text-red-600 dark:text-red-400 <?php echo e($errorClass); ?>"><?php echo e($error); ?></p>
    <?php elseif($hint): ?>
        <p class="text-sm text-gray-500 dark:text-gray-400 <?php echo e($hintClass); ?>"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views/components/form/base/field-wrapper.blade.php ENDPATH**/ ?>