



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'options' => [],
    'optionGroups' => [],
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
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'options' => [],
    'optionGroups' => [],
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
    $selectedValue = old($name, $value);
    
    // Generate field ID
    $fieldId = $name . '_' . uniqid();
    
    // Check for errors
    $hasError = $errors->has($name);
    
    // Build classes
    $baseClasses = 'block w-full rounded-md shadow-sm transition-colors duration-150 dark:bg-gray-700 dark:text-white';
    
    if ($hasError) {
        $borderClasses = 'border-red-300 dark:border-red-400 focus:border-red-500 focus:ring-red-500';
    } else {
        $borderClasses = 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500';
    }
    
    if ($disabled) {
        $stateClasses = 'bg-gray-50 dark:bg-gray-800 cursor-not-allowed opacity-60';
    } else {
        $stateClasses = 'bg-white';
    }
    
    $finalClasses = trim("$baseClasses $borderClasses $stateClasses pl-3 pr-10 py-2 text-sm");
?>

<?php if (isset($component)) { $__componentOriginal4d88ce4e7a623f35fd84edb3500e008f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.base.field-wrapper','data' => ['name' => $name,'label' => $label,'required' => $required,'hint' => $hint,'wrapperClass' => $wrapperClass,'fieldId' => $fieldId]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.base.field-wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($required),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hint),'wrapper-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wrapperClass),'field-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fieldId)]); ?>
    
    <div class="relative">
        <select
            name="<?php echo e($name); ?>"
            id="<?php echo e($fieldId); ?>"
            <?php if($required): ?> required <?php endif; ?>
            <?php if($disabled): ?> disabled <?php endif; ?>
            <?php echo e($attributes->except(['class'])->merge(['class' => $finalClasses])); ?>

        >
            <?php if($placeholder): ?>
                <option value=""><?php echo e($placeholder); ?></option>
            <?php endif; ?>
            
            <?php if(count($optionGroups) > 0): ?>
                <?php $__currentLoopData = $optionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e($group['label']); ?>">
                        <?php $__currentLoopData = $group['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionValue => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($optionValue); ?>" <?php if($selectedValue == $optionValue): echo 'selected'; endif; ?>>
                                <?php echo e($optionLabel); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionValue => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($optionValue); ?>" <?php if($selectedValue == $optionValue): echo 'selected'; endif; ?>>
                        <?php echo e($optionLabel); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </select>
        
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <i class="fas fa-chevron-down text-gray-400"></i>
        </div>
    </div>
    
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f)): ?>
<?php $attributes = $__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f; ?>
<?php unset($__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d88ce4e7a623f35fd84edb3500e008f)): ?>
<?php $component = $__componentOriginal4d88ce4e7a623f35fd84edb3500e008f; ?>
<?php unset($__componentOriginal4d88ce4e7a623f35fd84edb3500e008f); ?>
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views/components/form/select.blade.php ENDPATH**/ ?>