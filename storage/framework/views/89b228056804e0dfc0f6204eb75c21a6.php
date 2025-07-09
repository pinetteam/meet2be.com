



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'label' => null,
    'options' => [],
    'selected' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'inline' => false,
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
    'options' => [],
    'selected' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'inline' => false,
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
    // Get selected value
    $selectedValue = old($name, $selected);
?>

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
    
    <div class="<?php echo e($inline ? 'flex flex-wrap gap-4' : 'space-y-2'); ?>">
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal007928a2acf9f0c01766b43c0d46b2e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal007928a2acf9f0c01766b43c0d46b2e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.radio','data' => ['name' => $name,'value' => $value,'label' => $optionLabel,'checked' => $selectedValue == $value,'disabled' => $disabled,'model' => $model,'size' => $size,'wrapperClass' => ''.e($inline ? '' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($optionLabel),'checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedValue == $value),'disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($disabled),'model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($model),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($size),'wrapper-class' => ''.e($inline ? '' : '').'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal007928a2acf9f0c01766b43c0d46b2e5)): ?>
<?php $attributes = $__attributesOriginal007928a2acf9f0c01766b43c0d46b2e5; ?>
<?php unset($__attributesOriginal007928a2acf9f0c01766b43c0d46b2e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal007928a2acf9f0c01766b43c0d46b2e5)): ?>
<?php $component = $__componentOriginal007928a2acf9f0c01766b43c0d46b2e5; ?>
<?php unset($__componentOriginal007928a2acf9f0c01766b43c0d46b2e5); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\radio-group.blade.php ENDPATH**/ ?>