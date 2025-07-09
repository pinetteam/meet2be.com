{{-- Meet2Be: Phone input with country selector --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Advanced phone input with country code selection --}}

@props([
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
])

@php
    $placeholder = $placeholder ?? __('common.phone_placeholder');
    $countries = $countries ?? App\Models\System\Country::orderBy('name')->get();
    
    // Parse existing value to extract country code
    $phoneNumber = old($name, $value);
    $selectedCountryId = null;
    $phoneOnly = $phoneNumber;
    
    if ($phoneNumber && str_starts_with($phoneNumber, '+')) {
        foreach ($countries as $country) {
            if (str_starts_with($phoneNumber, '+' . $country->phone_code)) {
                $selectedCountryId = $country->id;
                $phoneOnly = substr($phoneNumber, strlen('+' . $country->phone_code));
                break;
            }
        }
    }
    
    if (!$selectedCountryId) {
        $defaultCountryModel = $countries->firstWhere('iso2', $defaultCountry);
        $selectedCountryId = $defaultCountryModel ? $defaultCountryModel->id : $countries->first()->id;
    }
    
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
        phoneNumber: '{{ $phoneOnly }}',
        selectedCountryId: '{{ $selectedCountryId }}',
        countries: {{ Js::from($countries) }},
        dropdownOpen: false,
        search: '',
        focused: false,
        flagLoaded: {},
        
        get selectedCountry() {
            return this.countries.find(c => c.id === this.selectedCountryId) || this.countries[0];
        },
        
        get filteredCountries() {
            if (!this.search) return this.countries;
            
            const searchLower = this.search.toLowerCase();
            return this.countries.filter(country => 
                country.name.toLowerCase().includes(searchLower) ||
                country.iso2.toLowerCase().includes(searchLower) ||
                country.iso3.toLowerCase().includes(searchLower) ||
                country.phone_code.includes(searchLower)
            );
        },
        
        get fullPhoneNumber() {
            const country = this.selectedCountry;
            if (!country || !this.phoneNumber) return '';
            return '+' + country.phone_code + this.phoneNumber;
        },
        
        selectCountry(countryId) {
            this.selectedCountryId = countryId;
            this.dropdownOpen = false;
            this.search = '';
            this.$nextTick(() => {
                this.$refs.phoneInput.focus();
            });
        },
        
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
            if (this.dropdownOpen) {
                this.$nextTick(() => {
                    this.$refs.searchInput.focus();
                });
            }
        },
        
        closeDropdown() {
            this.dropdownOpen = false;
            this.search = '';
        },
        
        formatPhoneNumber(value) {
            // Remove all non-digit characters except + at the beginning
            return value.replace(/[^\d]/g, '');
        }
    }" 
    @click.away="closeDropdown()"
    class="relative">
        
        <input type="hidden" name="{{ $name }}" id="{{ $fieldId }}" :value="fullPhoneNumber" autocomplete="tel">
        
        <div class="flex rounded-md shadow-sm"
             :class="{ 'ring-2 ring-blue-500': focused }">
            {{-- Country Selector --}}
            <button
                type="button"
                @click="toggleDropdown()"
                @if($disabled)
                    disabled
                @endif
                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none {{ $disabled ? 'cursor-not-allowed' : 'cursor-pointer' }} transition-colors duration-150"
                :class="{ 'border-blue-500 dark:border-blue-500': focused }">
                
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
                
                <span x-text="'+' + selectedCountry.phone_code" class="mr-1"></span>
                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
            </button>
            
            {{-- Phone Number Input --}}
            <input
                type="tel"
                x-ref="phoneInput"
                x-model="phoneNumber"
                @input="phoneNumber = formatPhoneNumber($event.target.value)"
                @focus="focused = true"
                @blur="focused = false"
                placeholder="{{ $placeholder }}"
                @if($required)
                    required
                @endif
                @if($disabled)
                    disabled
                @endif
                @if($readonly)
                    readonly
                @endif
                @if($model)
                    x-modelable="fullPhoneNumber"
                @endif
                autocomplete="tel-national"
                class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-none rounded-r-md focus:outline-none dark:bg-gray-700 dark:text-white transition-colors duration-150 {{ $disabled || $readonly ? 'bg-gray-50 dark:bg-gray-600 cursor-not-allowed' : '' }}"
                :class="{ 'border-blue-500 dark:border-blue-500': focused }">
        </div>
        
        {{-- Country Dropdown --}}
        <div
            x-show="dropdownOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-200 dark:border-gray-600"
            style="display: none;">
            
            {{-- Search Input --}}
            <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                <input
                    type="text"
                    x-ref="searchInput"
                    x-model="search"
                    @click.stop
                    placeholder="{{ __('common.search') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
            </div>
            
            {{-- Country List --}}
            <ul class="max-h-60 overflow-auto py-1">
                <template x-for="country in filteredCountries" :key="country.id">
                    <li>
                        <button
                            type="button"
                            @click="selectCountry(country.id)"
                            class="w-full px-3 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-600 transition-colors duration-150 flex items-center"
                            :class="{ 'bg-blue-50 dark:bg-blue-900/50': selectedCountryId === country.id }">
                            
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
                            <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'+' + country.phone_code"></span>
                            
                            <i x-show="selectedCountryId === country.id" class="fas fa-check ml-2 text-blue-600 dark:text-blue-400"></i>
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