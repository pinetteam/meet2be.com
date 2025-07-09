



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'value' => '50',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'min' => '0',
    'max' => '100',
    'step' => '1',
    'model' => null,
    'showValue' => true,
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
    'name',
    'label' => null,
    'value' => '50',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'min' => '0',
    'max' => '100',
    'step' => '1',
    'model' => null,
    'showValue' => true,
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

<?php if (isset($component)) { $__componentOriginal4d88ce4e7a623f35fd84edb3500e008f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.base.field-wrapper','data' => ['name' => $name,'label' => $label,'required' => $required,'hint' => $hint,'wrapperClass' => $wrapperClass]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.base.field-wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($required),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hint),'wrapper-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wrapperClass)]); ?>
    
    <div x-data="{ value: <?php echo e($model ? '$wire.entangle(\'' . $model . '\')' : old($name, $value)); ?> }">
        <input 
            type="range"
            name="<?php echo e($name); ?>"
            id="<?php echo e($name); ?>"
            x-model="value"
            <?php if($required): ?> required <?php endif; ?>
            <?php if($disabled): ?> disabled <?php endif; ?>
            min="<?php echo e($min); ?>"
            max="<?php echo e($max); ?>"
            step="<?php echo e($step); ?>"
            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
            <?php echo e($attributes); ?> />
            
        <?php if($showValue): ?>
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                <span><?php echo e($min); ?></span>
                <span x-text="value" class="font-medium text-gray-700 dark:text-gray-300"></span>
                <span><?php echo e($max); ?></span>
            </div>
        <?php endif; ?>
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
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\input\range.blade.php ENDPATH**/ ?>