



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'time_format',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
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
    'name' => 'time_format',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
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
    $label = $label ?? __('settings.fields.time_format');
    $placeholder = $placeholder ?? __('common.select');
    
    // Common time formats with preview
    $now = now();
    $options = [
        'H:i' => __('settings.time_formats.24_hour') . ' (' . $now->format('H:i') . ')',
        'H:i:s' => __('settings.time_formats.24_hour_seconds') . ' (' . $now->format('H:i:s') . ')',
        'h:i A' => __('settings.time_formats.12_hour') . ' (' . $now->format('h:i A') . ')',
        'h:i:s A' => __('settings.time_formats.12_hour_seconds') . ' (' . $now->format('h:i:s A') . ')',
        'g:i A' => __('settings.time_formats.12_hour_no_leading') . ' (' . $now->format('g:i A') . ')',
        'g:i:s A' => __('settings.time_formats.12_hour_no_leading_seconds') . ' (' . $now->format('g:i:s A') . ')',
    ];
?>

<?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => $name,'label' => $label,'value' => $value,'placeholder' => $placeholder,'hint' => $hint,'required' => $required,'disabled' => $disabled,'options' => $options,'wrapperClass' => $wrapperClass,'attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholder),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hint),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($required),'disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($disabled),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($options),'wrapper-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wrapperClass),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $attributes = $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $component = $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views/components/form/specialized/time-format-select.blade.php ENDPATH**/ ?>