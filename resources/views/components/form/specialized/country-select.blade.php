{{-- Meet2Be: Country select with flags --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Searchable country dropdown with flag display --}}

@props([
    'name',
    'label' => null,
    'selected' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'countries' => null,
    'searchPlaceholder' => null,
    'noResultsText' => null,
    'wrapperClass' => ''
])

@php
    $placeholder = $placeholder ?? __('common.select');
    $searchPlaceholder = $searchPlaceholder ?? __('common.search');
    $noResultsText = $noResultsText ?? __('common.no_results');
    $countries = $countries ?? App\Models\System\Country::orderBy('name')->get();
    
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div x-data="{
        open: false,
        search: '',
        selected: {{ $model ?? "'" . old($name, $selected) . "'" }},
        countries: {{ Js::from($countries) }},
        flagLoaded: {},
        
        get filteredCountries() {
            if (!this.search) return this.countries;
            
            const searchLower = this.search.toLowerCase();
            return this.countries.filter(country => 
                country.name.toLowerCase().includes(searchLower) ||
                country.iso2.toLowerCase().includes(searchLower) ||
                country.iso3.toLowerCase().includes(searchLower)
            );
        },
        
        get selectedCountry() {
            return this.countries.find(c => c.id === this.selected);
        },
        
        get displayText() {
            const country = this.selectedCountry;
            return country ? country.name : '{{ $placeholder }}';
        },
        
        selectCountry(countryId) {
            this.selected = countryId;
            this.open = false;
            this.search = '';
        }
    }" 
    @click.away="open = false"
    class="relative">
        
        <input type="hidden" name="{{ $name }}" id="{{ $fieldId }}" :value="selected" autocomplete="country">
        
        <button
            type="button"
            @click="open = !open"
            :aria-expanded="open"
            aria-haspopup="listbox"
            @if($disabled) disabled @endif
            class="relative w-full py-2 pl-3 pr-10 text-left bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-150 {{ $disabled ? 'bg-gray-50 dark:bg-gray-600 cursor-not-allowed' : '' }}">
            
            <div class="flex items-center">
                <template x-if="selectedCountry">
                    <div class="flex items-center">
                        <template x-if="flagLoaded[selectedCountry.iso2] !== false">
                            <img 
                                :src="`/assets/images/flags/32x24/${selectedCountry.iso2.toLowerCase()}.png`" 
                                :alt="selectedCountry.name"
                                @error="flagLoaded[selectedCountry.iso2] = false"
                                class="w-5 h-4 mr-2">
                        </template>
                        
                        <template x-if="flagLoaded[selectedCountry.iso2] === false">
                            <span class="inline-flex items-center justify-center w-5 h-4 mr-2 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded">
                                <span x-text="selectedCountry.iso2"></span>
                            </span>
                        </template>
                        
                        <span x-text="selectedCountry.name"></span>
                    </div>
                </template>
                
                <template x-if="!selectedCountry">
                    <span class="text-gray-500">{{ $placeholder }}</span>
                </template>
            </div>
            
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <i class="fas fa-chevron-down text-gray-400"></i>
            </span>
        </button>
        
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-200 dark:border-gray-600"
            style="display: none;">
            
            <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                <input
                    type="text"
                    x-model="search"
                    @click.stop
                    placeholder="{{ $searchPlaceholder }}"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
            </div>
            
            <ul class="max-h-60 overflow-auto py-1">
                <template x-for="country in filteredCountries" :key="country.id">
                    <li>
                        <button
                            type="button"
                            @click="selectCountry(country.id)"
                            class="w-full px-3 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-600 transition-colors duration-150 flex items-center"
                            :class="{ 'bg-blue-50 dark:bg-blue-900/50': selected === country.id }">
                            
                            <template x-if="flagLoaded[country.iso2] !== false">
                                <img 
                                    :src="`/assets/images/flags/32x24/${country.iso2.toLowerCase()}.png`" 
                                    :alt="country.name"
                                    @error="flagLoaded[country.iso2] = false"
                                    class="w-5 h-4 mr-3">
                            </template>
                            
                            <template x-if="flagLoaded[country.iso2] === false">
                                <span class="inline-flex items-center justify-center w-5 h-4 mr-3 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded">
                                    <span x-text="country.iso2"></span>
                                </span>
                            </template>
                            
                            <span class="flex-1" x-text="country.name"></span>
                            
                            <i x-show="selected === country.id" class="fas fa-check ml-2 text-blue-600 dark:text-blue-400"></i>
                        </button>
                    </li>
                </template>
                
                <li x-show="filteredCountries.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                    {{ $noResultsText }}
                </li>
            </ul>
        </div>
    </div>
    
</x-form.base.field-wrapper>

<script>
// Meet2Be: Country select component logic
function countrySelect(initialData) {
    return {
        selectedValue: initialData.value,
        showDropdown: false,
        search: '',
        focusedIndex: -1,
        countries: @json($countries),
        flagLoaded: {},
        
        init() {
            // Meet2Be: Handle Alpine.js model binding
            if (initialData.model) {
                // Get the parent component context
                let parent = this.$el.closest('[x-data]').__x;
                if (parent) {
                    // Watch parent model changes
                    this.$watch(() => {
                        const keys = initialData.model.split('.');
                        let value = parent.$data;
                        for (const key of keys) {
                            value = value?.[key];
                        }
                        return value;
                    }, (value) => {
                        if (value !== this.selectedValue) {
                            this.selectedValue = value;
                        }
                    });
                    
                    // Update parent model when selection changes
                    this.$watch('selectedValue', (value) => {
                        const keys = initialData.model.split('.');
                        let obj = parent.$data;
                        for (let i = 0; i < keys.length - 1; i++) {
                            obj = obj[keys[i]];
                        }
                        obj[keys[keys.length - 1]] = value;
                    });
                }
            }
            
            // Meet2Be: Preload flag status for all countries
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
        
        get selectedCountry() {
            return this.countries.find(c => c.id === this.selectedValue);
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
        
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
            if (this.showDropdown) {
                this.$nextTick(() => {
                    this.$refs.searchInput?.focus();
                    // Set focused index to selected item
                    const selectedIndex = this.filteredCountries.findIndex(c => c.id === this.selectedValue);
                    this.focusedIndex = selectedIndex >= 0 ? selectedIndex : 0;
                });
            } else {
                this.search = '';
                this.focusedIndex = -1;
            }
        },
        
        closeDropdown() {
            this.showDropdown = false;
            this.search = '';
            this.focusedIndex = -1;
        },
        
        selectCountry(country) {
            this.selectedValue = country.id;
            this.closeDropdown();
        },
        
        selectFocused() {
            if (this.focusedIndex >= 0 && this.focusedIndex < this.filteredCountries.length) {
                this.selectCountry(this.filteredCountries[this.focusedIndex]);
            }
        },
        
        focusNext() {
            if (this.focusedIndex < this.filteredCountries.length - 1) {
                this.focusedIndex++;
            } else {
                this.focusedIndex = 0;
            }
        },
        
        focusPrevious() {
            if (this.focusedIndex > 0) {
                this.focusedIndex--;
            } else {
                this.focusedIndex = this.filteredCountries.length - 1;
            }
        }
    }
}
</script>

@push('styles')
<style>
/* Meet2Be: Custom country select styles */
.country-select-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.country-flag {
    width: 20px;
    height: 15px;
    border-radius: 2px;
    object-fit: cover;
}

.country-flag-placeholder {
    width: 20px;
    height: 15px;
    border-radius: 2px;
    background-color: #e5e7eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 600;
    color: #6b7280;
}
</style>
@endpush 