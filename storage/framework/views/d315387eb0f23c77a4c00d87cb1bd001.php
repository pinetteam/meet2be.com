



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'date_format',
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
    'name' => 'date_format',
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
    $label = $label ?? __('settings.fields.date_format');
    $placeholder = $placeholder ?? __('common.select');
    
    // Common date formats with preview
    $now = now();
    $options = [
        'Y-m-d' => __('settings.date_formats.iso8601') . ' (' . $now->format('Y-m-d') . ')',
        'd/m/Y' => __('settings.date_formats.european') . ' (' . $now->format('d/m/Y') . ')',
        'm/d/Y' => __('settings.date_formats.us') . ' (' . $now->format('m/d/Y') . ')',
        'd.m.Y' => __('settings.date_formats.european_dot') . ' (' . $now->format('d.m.Y') . ')',
        'd-m-Y' => __('settings.date_formats.european_dash') . ' (' . $now->format('d-m-Y') . ')',
        'M j, Y' => __('settings.date_formats.long') . ' (' . $now->format('M j, Y') . ')',
        'F j, Y' => __('settings.date_formats.full') . ' (' . $now->format('F j, Y') . ')',
        'j M Y' => __('settings.date_formats.compact') . ' (' . $now->format('j M Y') . ')',
        'd M Y' => __('settings.date_formats.medium') . ' (' . $now->format('d M Y') . ')',
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
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views/components/form/specialized/date-format-select.blade.php ENDPATH**/ ?>