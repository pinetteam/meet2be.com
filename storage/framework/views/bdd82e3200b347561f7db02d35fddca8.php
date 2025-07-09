



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'timezone_id',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'timezones' => null,
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
    'name' => 'timezone_id',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'timezones' => null,
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
    $label = $label ?? __('settings.fields.timezone');
    $placeholder = $placeholder ?? __('common.select');
    $selectedValue = old($name, $value);
    
    // Get all timezones if not provided
    $timezones = $timezones ?? App\Models\System\Timezone::orderBy('name')->get();
    
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
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
    
    <div 
        x-data="{
            open: false,
            search: '',
            selectedId: '<?php echo e($selectedValue); ?>',
            timezones: <?php echo e(Js::from($timezones)); ?>,
            
            get filteredTimezones() {
                if (!this.search) return this.timezones;
                
                const searchLower = this.search.toLowerCase();
                return this.timezones.filter(tz => 
                    tz.name.toLowerCase().includes(searchLower) ||
                    tz.offset.toString().includes(searchLower)
                );
            },
            
            get groupedTimezones() {
                const groups = {};
                this.filteredTimezones.forEach(tz => {
                    const parts = tz.name.split('/');
                    const region = parts[0] || 'Other';
                    
                    if (!groups[region]) {
                        groups[region] = [];
                    }
                    groups[region].push(tz);
                });
                return groups;
            },
            
            get selectedTimezone() {
                return this.timezones.find(tz => tz.id === this.selectedId);
            },
            
            selectTimezone(timezoneId) {
                this.selectedId = timezoneId;
                this.open = false;
                this.search = '';
            },
            
            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.$nextTick(() => {
                        this.$refs.searchInput?.focus();
                    });
                }
            },
            
            closeDropdown() {
                this.open = false;
                this.search = '';
            },
            
            formatTimezone(tz) {
                const offset = tz.offset >= 0 ? '+' + tz.offset.toFixed(1) : tz.offset.toFixed(1);
                return tz.name + ' (UTC' + offset + ')';
            }
        }"
        x-modelable="selectedId"
        <?php echo e($attributes->whereStartsWith('x-model')); ?>

        @click.away="closeDropdown()"
        class="relative"
    >
        
        <button
            type="button"
            @click="toggleDropdown()"
            <?php echo e($disabled ? 'disabled' : ''); ?>

            class="relative w-full px-3 py-2 text-left <?php echo e($disabled ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700'); ?> border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-150"
            :class="{ 'cursor-not-allowed opacity-60': <?php echo e($disabled ? 'true' : 'false'); ?> }"
        >
            <span x-show="!selectedTimezone" class="block truncate text-gray-400 dark:text-gray-500">
                <?php echo e($placeholder); ?>

            </span>
            
            <span x-show="selectedTimezone" class="block truncate text-gray-900 dark:text-white" x-text="selectedTimezone ? formatTimezone(selectedTimezone) : ''"></span>
            
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <i class="fas fa-chevron-down text-gray-400"></i>
            </span>
        </button>
        
        
        <input 
            type="hidden" 
            name="<?php echo e($name); ?>" 
            id="<?php echo e($fieldId); ?>"
            :value="selectedId"
            <?php if($required): ?> required <?php endif; ?>
        />
        
        
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-200 dark:border-gray-600"
            style="display: none;"
        >
            
            <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                <input
                    type="text"
                    x-model="search"
                    x-ref="searchInput"
                    @click.stop
                    placeholder="<?php echo e(__('common.search')); ?>"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                />
            </div>
            
            
            <ul class="max-h-60 overflow-auto py-1">
                <template x-for="(timezones, region) in groupedTimezones" :key="region">
                    <li>
                        <div class="px-3 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-800">
                            <span x-text="region"></span>
                        </div>
                        <template x-for="timezone in timezones" :key="timezone.id">
                            <button
                                type="button"
                                @click="selectTimezone(timezone.id)"
                                class="w-full px-3 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-600 transition-colors duration-150 flex items-center justify-between"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/50': selectedId === timezone.id }"
                            >
                                <span class="text-sm text-gray-900 dark:text-white" x-text="formatTimezone(timezone)"></span>
                                <i x-show="selectedId === timezone.id" class="fas fa-check ml-2 text-blue-600 dark:text-blue-400"></i>
                            </button>
                        </template>
                    </li>
                </template>
                
                <li x-show="Object.keys(groupedTimezones).length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                    <?php echo e(__('common.no_results')); ?>

                </li>
            </ul>
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
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\specialized\timezone-select.blade.php ENDPATH**/ ?>