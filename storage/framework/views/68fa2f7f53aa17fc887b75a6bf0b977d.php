



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'model' => null,
    'countries' => null,
    'defaultCountry' => 'TR',
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
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'model' => null,
    'countries' => null,
    'defaultCountry' => 'TR',
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
    $placeholder = $placeholder ?? __('common.phone_placeholder');
    $countries = $countries ?? App\Models\System\Country::orderBy('name_en')->get();
    
    // Parse existing value to extract country code
    $phoneNumber = old($name, $value);
    $selectedCountryId = null;
    $phoneOnly = $phoneNumber;
    
    if ($phoneNumber && str_starts_with($phoneNumber, '+')) {
        // Try to find country by phone code
        foreach ($countries as $country) {
            if (str_starts_with($phoneNumber, '+' . $country->phone_code)) {
                $selectedCountryId = $country->id;
                $phoneOnly = substr($phoneNumber, strlen($country->phone_code) + 1);
                break;
            }
        }
    }
    
    // If no country found, use default
    if (!$selectedCountryId) {
        $defaultCountryObj = $countries->firstWhere('iso2', $defaultCountry);
        $selectedCountryId = $defaultCountryObj ? $defaultCountryObj->id : $countries->first()->id;
    }
    
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
            countryDropdownOpen: false,
            countrySearch: '',
            selectedCountryId: '<?php echo e($selectedCountryId); ?>',
            phoneNumber: '<?php echo e($phoneOnly); ?>',
            countries: <?php echo e(Js::from($countries)); ?>,
            flagLoaded: {},
            focused: false,
            
            init() {
                // Preload flag images
                this.countries.forEach(country => {
                    const img = new Image();
                    img.onload = () => {
                        this.flagLoaded[country.iso2] = true;
                    };
                    img.onerror = () => {
                        this.flagLoaded[country.iso2] = false;
                    };
                    img.src = `/assets/images/flags/32x24/${country.iso2.toLowerCase()}.png`;
                });
            },
            
            get filteredCountries() {
                if (!this.countrySearch) return this.countries;
                
                const searchLower = this.countrySearch.toLowerCase();
                return this.countries.filter(country => 
                    country.name_en.toLowerCase().includes(searchLower) ||
                    country.iso2.toLowerCase().includes(searchLower) ||
                    country.iso3.toLowerCase().includes(searchLower) ||
                    country.phone_code.includes(searchLower)
                );
            },
            
            get selectedCountry() {
                return this.countries.find(c => c.id === this.selectedCountryId);
            },
            
            get fullPhoneNumber() {
                const country = this.selectedCountry;
                if (!country || !this.phoneNumber) return '';
                return `+${country.phone_code}${this.phoneNumber}`;
            },
            
            selectCountry(countryId) {
                this.selectedCountryId = countryId;
                this.countryDropdownOpen = false;
                this.countrySearch = '';
                this.$nextTick(() => {
                    this.$refs.phoneInput?.focus();
                });
            },
            
            toggleDropdown() {
                this.countryDropdownOpen = !this.countryDropdownOpen;
                if (this.countryDropdownOpen) {
                    this.$nextTick(() => {
                        this.$refs.countrySearchInput?.focus();
                    });
                }
            },
            
            closeDropdown() {
                this.countryDropdownOpen = false;
                this.countrySearch = '';
            }
        }"
        x-modelable="fullPhoneNumber"
        <?php echo e($attributes->whereStartsWith('x-model')); ?>

        @click.away="closeDropdown()"
        class="relative"
        :class="{ 'ring-2 ring-blue-500 rounded-md': focused }"
    >
        <div class="flex">
            
            <button
                type="button"
                @click="toggleDropdown()"
                @focus="focused = true"
                @blur="focused = false"
                <?php echo e($disabled ? 'disabled' : ''); ?>

                class="relative inline-flex items-center px-3 py-2 border border-r-0 <?php echo e($disabled ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700'); ?> border-gray-300 dark:border-gray-600 rounded-l-md focus:outline-none focus:z-10 transition-colors duration-150"
                :class="{ 
                    'border-gray-300 dark:border-gray-600': !focused, 
                    'border-blue-500': focused,
                    'cursor-not-allowed opacity-60': <?php echo e($disabled ? 'true' : 'false'); ?>

                }"
            >
                <template x-if="selectedCountry">
                    <div class="flex items-center">
                        <template x-if="flagLoaded[selectedCountry.iso2] !== false">
                            <img 
                                :src="`/assets/images/flags/32x24/${selectedCountry.iso2.toLowerCase()}.png`" 
                                :alt="selectedCountry.name_en"
                                x-on:error="flagLoaded[selectedCountry.iso2] = false"
                                class="w-5 h-4 mr-2 flex-shrink-0"
                            />
                        </template>
                        
                        <template x-if="flagLoaded[selectedCountry.iso2] === false">
                            <span class="inline-flex items-center justify-center w-5 h-4 mr-2 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded flex-shrink-0">
                                <span x-text="selectedCountry.iso2"></span>
                            </span>
                        </template>
                        
                        <span class="text-sm text-gray-700 dark:text-gray-300" x-text="`+${selectedCountry.phone_code}`"></span>
                        <i class="fas fa-chevron-down ml-2 text-xs text-gray-400"></i>
                    </div>
                </template>
            </button>
            
            
            <input
                type="tel"
                name="<?php echo e($name); ?>_display"
                x-ref="phoneInput"
                x-model="phoneNumber"
                @focus="focused = true"
                @blur="focused = false"
                <?php if($placeholder): ?> placeholder="<?php echo e($placeholder); ?>" <?php endif; ?>
                <?php if($required): ?> required <?php endif; ?>
                <?php if($disabled): ?> disabled <?php endif; ?>
                <?php if($readonly): ?> readonly <?php endif; ?>
                class="flex-1 px-3 py-2 border <?php echo e($disabled ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700'); ?> border-gray-300 dark:border-gray-600 rounded-r-md focus:outline-none focus:z-10 dark:text-white sm:text-sm transition-colors duration-150"
                :class="{ 
                    'border-gray-300 dark:border-gray-600': !focused, 
                    'border-blue-500': focused,
                    'cursor-not-allowed opacity-60': <?php echo e($disabled ? 'true' : 'false'); ?>

                }"
            />
        </div>
        
        
        <input 
            type="hidden" 
            name="<?php echo e($name); ?>" 
            id="<?php echo e($fieldId); ?>"
            :value="fullPhoneNumber"
        />
        
        
        <div
            x-show="countryDropdownOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 mt-1 w-72 bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-200 dark:border-gray-600"
            style="display: none;"
        >
            <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                <input
                    type="text"
                    x-model="countrySearch"
                    x-ref="countrySearchInput"
                    @click.stop
                    placeholder="<?php echo e(__('common.search')); ?>"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                />
            </div>
            
            <ul class="max-h-60 overflow-auto py-1">
                <template x-for="country in filteredCountries" :key="country.id">
                    <li>
                        <button
                            type="button"
                            @click="selectCountry(country.id)"
                            class="w-full px-3 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-600 transition-colors duration-150 flex items-center"
                            :class="{ 'bg-blue-50 dark:bg-blue-900/50': selectedCountryId === country.id }"
                        >
                            <template x-if="flagLoaded[country.iso2] !== false">
                                <img 
                                    :src="`/assets/images/flags/32x24/${country.iso2.toLowerCase()}.png`" 
                                    :alt="country.name_en"
                                    x-on:error="flagLoaded[country.iso2] = false"
                                    class="w-5 h-4 mr-3 flex-shrink-0"
                                />
                            </template>
                            
                            <template x-if="flagLoaded[country.iso2] === false">
                                <span class="inline-flex items-center justify-center w-5 h-4 mr-3 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded flex-shrink-0">
                                    <span x-text="country.iso2"></span>
                                </span>
                            </template>
                            
                            <span class="flex-1">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white" x-text="country.name_en"></span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400" x-text="`+${country.phone_code}`"></span>
                            </span>
                            
                            <i x-show="selectedCountryId === country.id" class="fas fa-check ml-2 text-blue-600 dark:text-blue-400"></i>
                        </button>
                    </li>
                </template>
                
                <li x-show="filteredCountries.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
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
<?php endif; ?> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views\components\form\specialized\phone-input.blade.php ENDPATH**/ ?>