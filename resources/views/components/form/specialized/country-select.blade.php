{{-- Meet2Be: Country select with flag display --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Country dropdown with flag icons and search functionality --}}

@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'model' => null,
    'countries' => null,
    'wrapperClass' => ''
])

@php
    $placeholder = $placeholder ?? __('common.select');
    $countries = $countries ?? App\Models\System\Country::orderBy('name_en')->get();
    $selectedValue = old($name, $value);
    
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass"
    :field-id="$fieldId">
    
    <div 
        x-data="{
            open: false,
            search: '',
            selectedId: '{{ $selectedValue }}',
            countries: {{ Js::from($countries) }},
            flagLoaded: {},
            
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
                if (!this.search) return this.countries;
                
                const searchLower = this.search.toLowerCase();
                return this.countries.filter(country => 
                    country.name_en.toLowerCase().includes(searchLower) ||
                    country.iso2.toLowerCase().includes(searchLower) ||
                    country.iso3.toLowerCase().includes(searchLower)
                );
            },
            
            get selectedCountry() {
                return this.countries.find(c => c.id === this.selectedId);
            },
            
            selectCountry(countryId) {
                this.selectedId = countryId;
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
            }
        }"
        x-modelable="selectedId"
        {{ $attributes->whereStartsWith('x-model') }}
        @click.away="closeDropdown()"
        class="relative"
    >
        {{-- Select Button --}}
        <button
            type="button"
            id="{{ $fieldId }}"
            @click="toggleDropdown()"
            {{ $disabled ? 'disabled' : '' }}
            class="relative w-full px-3 py-2 text-left {{ $disabled ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700' }} border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-150"
            :class="{ 'cursor-not-allowed opacity-60': {{ $disabled ? 'true' : 'false' }} }"
        >
            <span class="flex items-center">
                <span x-show="!selectedCountry" class="text-gray-400 dark:text-gray-500">
                    {{ $placeholder }}
                </span>
                
                <template x-if="selectedCountry">
                    <span class="flex items-center flex-1">
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
                        
                        <span class="block truncate text-gray-900 dark:text-white" x-text="selectedCountry.name_en"></span>
                    </span>
                </template>
            </span>
            
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <i class="fas fa-chevron-down text-gray-400"></i>
            </span>
        </button>
        
        {{-- Hidden input --}}
        <input 
            type="hidden" 
            name="{{ $name }}" 
            id="{{ $fieldId }}_hidden"
            :value="selectedId"
            @if($required) required @endif
        />
        
        {{-- Dropdown --}}
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
                    id="{{ $fieldId }}_search"
                    name="{{ $name }}_search"
                    x-model="search"
                    x-ref="searchInput"
                    @click.stop
                    placeholder="{{ __('common.search') }}"
                    autocomplete="off"
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
                            :class="{ 'bg-blue-50 dark:bg-blue-900/50': selectedId === country.id }"
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
                            
                            <span class="flex-1 text-sm text-gray-900 dark:text-white" x-text="country.name_en"></span>
                            
                            <i x-show="selectedId === country.id" class="fas fa-check ml-2 text-blue-600 dark:text-blue-400"></i>
                        </button>
                    </li>
                </template>
                
                <li x-show="filteredCountries.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                    {{ __('common.no_results') }}
                </li>
            </ul>
        </div>
    </div>
    
</x-form.base.field-wrapper> 