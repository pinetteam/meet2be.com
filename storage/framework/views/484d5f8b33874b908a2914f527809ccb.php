



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'options' => [], // Array or Collection of options
    'optionValue' => 'id', // Field to use as option value
    'optionLabel' => 'name' // Field to use as option label
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
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'options' => [], // Array or Collection of options
    'optionValue' => 'id', // Field to use as option value
    'optionLabel' => 'name' // Field to use as option label
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
    
    <?php if (isset($component)) { $__componentOriginalff3a4d2506f2feff67979e90a64aac2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff3a4d2506f2feff67979e90a64aac2d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.base.select-base','data' => ['name' => $name,'value' => $value,'placeholder' => $placeholder,'required' => $required,'disabled' => $disabled,'multiple' => $multiple,'model' => $model,'size' => $size,'attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.base.select-base'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholder),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($required),'disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($disabled),'multiple' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($multiple),'model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($model),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($size),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
        
        <?php if($slot->isNotEmpty()): ?>
            <?php echo e($slot); ?>

        <?php else: ?>
            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $optValue = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                    $optLabel = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                    $isSelected = $multiple 
                        ? in_array($optValue, (array) old($name, $value)) 
                        : old($name, $value) == $optValue;
                ?>
                <option value="<?php echo e($optValue); ?>" <?php if($isSelected): ?> selected <?php endif; ?>>
                    <?php echo e($optLabel); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff3a4d2506f2feff67979e90a64aac2d)): ?>
<?php $attributes = $__attributesOriginalff3a4d2506f2feff67979e90a64aac2d; ?>
<?php unset($__attributesOriginalff3a4d2506f2feff67979e90a64aac2d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff3a4d2506f2feff67979e90a64aac2d)): ?>
<?php $component = $__componentOriginalff3a4d2506f2feff67979e90a64aac2d; ?>
<?php unset($__componentOriginalff3a4d2506f2feff67979e90a64aac2d); ?>
<?php endif; ?>
    
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f)): ?>
<?php $attributes = $__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f; ?>
<?php unset($__attributesOriginal4d88ce4e7a623f35fd84edb3500e008f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d88ce4e7a623f35fd84edb3500e008f)): ?>
<?php $component = $__componentOriginal4d88ce4e7a623f35fd84edb3500e008f; ?>
<?php unset($__componentOriginal4d88ce4e7a623f35fd84edb3500e008f); ?>
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\select\simple.blade.php ENDPATH**/ ?>