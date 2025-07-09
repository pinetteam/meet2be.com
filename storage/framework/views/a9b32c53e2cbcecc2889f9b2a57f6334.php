



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
    'readonly' => false,
    'autofocus' => false,
    'autocomplete' => 'current-password',
    'minlength' => null,
    'maxlength' => null,
    'pattern' => null,
    'model' => null,
    'size' => 'md',
    'showToggle' => true,
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
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'autocomplete' => 'current-password',
    'minlength' => null,
    'maxlength' => null,
    'pattern' => null,
    'model' => null,
    'size' => 'md',
    'showToggle' => true,
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
    
    <div x-data="{ showPassword: false }" class="relative">
        <?php if (isset($component)) { $__componentOriginal8e4e3ad1c3439490d213f591725cf909 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e4e3ad1c3439490d213f591725cf909 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.base.input-base','data' => ['name' => $name,'xBind:type' => 'showPassword ? \'text\' : \'password\'','value' => $value,'placeholder' => $placeholder,'required' => $required,'disabled' => $disabled,'readonly' => $readonly,'autofocus' => $autofocus,'autocomplete' => $autocomplete,'minlength' => $minlength,'maxlength' => $maxlength,'pattern' => $pattern,'model' => $model,'size' => $size,'icon' => 'fa-solid fa-lock','inputClass' => $showToggle ? 'pr-10' : '','attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.base.input-base'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'x-bind:type' => 'showPassword ? \'text\' : \'password\'','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholder),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($required),'disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($disabled),'readonly' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($readonly),'autofocus' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($autofocus),'autocomplete' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($autocomplete),'minlength' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($minlength),'maxlength' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($maxlength),'pattern' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pattern),'model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($model),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($size),'icon' => 'fa-solid fa-lock','input-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showToggle ? 'pr-10' : ''),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e4e3ad1c3439490d213f591725cf909)): ?>
<?php $attributes = $__attributesOriginal8e4e3ad1c3439490d213f591725cf909; ?>
<?php unset($__attributesOriginal8e4e3ad1c3439490d213f591725cf909); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e4e3ad1c3439490d213f591725cf909)): ?>
<?php $component = $__componentOriginal8e4e3ad1c3439490d213f591725cf909; ?>
<?php unset($__componentOriginal8e4e3ad1c3439490d213f591725cf909); ?>
<?php endif; ?>
            
        <?php if($showToggle && !$disabled && !$readonly): ?>
            <button type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    tabindex="-1">
                <i x-show="!showPassword" class="fa-solid fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 <?php echo e($size === 'sm' ? 'text-xs' : 'text-sm'); ?>"></i>
                <i x-show="showPassword" class="fa-solid fa-eye-slash text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 <?php echo e($size === 'sm' ? 'text-xs' : 'text-sm'); ?>"></i>
            </button>
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
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\input\password.blade.php ENDPATH**/ ?>