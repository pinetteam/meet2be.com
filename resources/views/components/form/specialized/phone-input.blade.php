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
    
    // Prepare countries data with localized names
    $countriesData = $countries->map(function($country) {
        return [
            'id' => $country->id,
            'iso2' => $country->iso2,
            'iso3' => $country->iso3,
            'name' => $country->getName(),
            'name_en' => $country->name_en,
            'phone_code' => $country->phone_code,
        ];
    });
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
            countryDropdownOpen: false,
            countrySearch: '',
            selectedCountryId: '{{ $selectedCountryId }}',
            phoneNumber: '{{ $phoneOnly }}',
            countries: {{ Js::from($countriesData) }},
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
                    country.name.toLowerCase().includes(searchLower) ||
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
        {{ $attributes->whereStartsWith('x-model') }}
        @click.away="closeDropdown()"
        class="relative"
        :class="{ 'ring-2 ring-blue-500 rounded-md': focused }"
    >
        <div class="flex">
            {{-- Country Selector --}}
            <button
                type="button"
                @click="toggleDropdown()"
                @focus="focused = true"
                @blur="focused = false"
                {{ $disabled ? 'disabled' : '' }}
                class="relative inline-flex items-center px-3 py-2 border border-r-0 {{ $disabled ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700' }} border-gray-300 dark:border-gray-600 rounded-l-md focus:outline-none focus:z-10 transition-colors duration-150"
                :class="{ 
                    'border-gray-300 dark:border-gray-600': !focused, 
                    'border-blue-500': focused,
                    'cursor-not-allowed opacity-60': {{ $disabled ? 'true' : 'false' }}
                }"
            >
                <template x-if="selectedCountry">
                    <div class="flex items-center">
                        <template x-if="flagLoaded[selectedCountry.iso2] !== false">
                            <img 
                                :src="`/assets/images/flags/32x24/${selectedCountry.iso2.toLowerCase()}.png`" 
                                :alt="selectedCountry.name"
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
            
            {{-- Phone Number Input --}}
            <input
                type="tel"
                id="{{ $fieldId }}"
                name="{{ $name }}_display"
                x-ref="phoneInput"
                x-model="phoneNumber"
                @focus="focused = true"
                @blur="focused = false"
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                autocomplete="tel-national"
                class="flex-1 px-3 py-2 border {{ $disabled ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700' }} border-gray-300 dark:border-gray-600 rounded-r-md focus:outline-none focus:z-10 dark:text-white sm:text-sm transition-colors duration-150"
                :class="{ 
                    'border-gray-300 dark:border-gray-600': !focused, 
                    'border-blue-500': focused,
                    'cursor-not-allowed opacity-60': {{ $disabled ? 'true' : 'false' }}
                }"
            />
        </div>
        
        {{-- Hidden input for full phone number --}}
        <input 
            type="hidden" 
            name="{{ $name }}" 
            id="{{ $fieldId }}_hidden"
            :value="fullPhoneNumber"
        />
        
        {{-- Country Dropdown --}}
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
                    id="{{ $fieldId }}_country_search"
                    name="{{ $name }}_country_search"
                    x-model="countrySearch"
                    x-ref="countrySearchInput"
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
                            :class="{ 'bg-blue-50 dark:bg-blue-900/50': selectedCountryId === country.id }"
                        >
                            <template x-if="flagLoaded[country.iso2] !== false">
                                <img 
                                    :src="`/assets/images/flags/32x24/${country.iso2.toLowerCase()}.png`" 
                                    :alt="country.name"
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
                                <span class="block text-sm font-medium text-gray-900 dark:text-white" x-text="country.name"></span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400" x-text="`+${country.phone_code}`"></span>
                            </span>
                            
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