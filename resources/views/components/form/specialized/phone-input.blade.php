{{-- Meet2Be: Phone input component with country selector --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Usage: <x-form.phone-input name="phone" label="Phone Number" :value="$user->phone" required /> --}}

@props([
    'name',
    'label',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'hint' => '',
    'countries' => null,
    'model' => null // For Alpine.js x-model binding
])

@php
    // Meet2Be: Parse phone number to extract country code and number
    $phoneCountryId = null;
    $phoneNumber = $value;
    
    if ($value && str_starts_with($value, '+')) {
        // Extract country code from phone number
        $countries = $countries ?? \App\Models\System\Country::whereNotNull('phone_code')->orderBy('name_en')->get();
        foreach ($countries as $country) {
            if ($country->phone_code && str_starts_with($value, '+' . $country->phone_code)) {
                $phoneCountryId = $country->id;
                $phoneNumber = substr($value, strlen($country->phone_code) + 1);
                break;
            }
        }
    }
    
    // Default to user's country if no country detected
    if (!$phoneCountryId && auth()->user() && auth()->user()->tenant) {
        $phoneCountryId = auth()->user()->tenant->country_id;
    }
@endphp

<div x-data="phoneInput(@js([
    'countryId' => $phoneCountryId,
    'phoneNumber' => $phoneNumber,
    'fieldName' => $name
]))">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <div class="flex rounded-md shadow-sm">
        {{-- Country Selector --}}
        <div class="relative">
            <button type="button"
                    @click="toggleDropdown()"
                    class="relative inline-flex items-center px-3 py-2 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    :disabled="@js($disabled)">
                <template x-if="selectedCountry">
                    <div class="flex items-center">
                        <template x-if="flagLoaded[selectedCountry.iso2]">
                            <img :src="`/assets/images/flags/32x24/${selectedCountry.iso2.toLowerCase()}.png`" 
                                 :alt="selectedCountry.name_en"
                                 class="w-5 h-4 mr-2 rounded-sm">
                        </template>
                        <template x-if="!flagLoaded[selectedCountry.iso2]">
                            <span class="inline-flex items-center justify-center w-5 h-4 mr-2 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded-sm text-gray-600 dark:text-gray-300"
                                  x-text="selectedCountry.iso2.toUpperCase()"></span>
                        </template>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="`+${selectedCountry.phone_code}`"></span>
                    </div>
                </template>
                <template x-if="!selectedCountry">
                    <span class="text-sm">{{ __('common.select') }}</span>
                </template>
                <i class="fa-solid fa-chevron-down ml-2 text-xs transition-transform duration-200" 
                   :class="{ 'rotate-180': showCountryDropdown }"></i>
            </button>
            
            {{-- Country Dropdown --}}
            <div x-show="showCountryDropdown"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 @click.away="closeDropdown()"
                 class="absolute z-50 mt-1 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 max-h-96 overflow-hidden flex flex-col"
                 style="display: none;">
                
                {{-- Search Input --}}
                <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                    <input type="text"
                           x-ref="countrySearchInput"
                           x-model="countrySearch"
                           @click.stop
                           @keydown.escape="closeDropdown()"
                           class="w-full px-3 py-1.5 text-sm border-gray-300 dark:border-gray-600 rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="{{ __('common.search') }}...">
                </div>
                
                {{-- Country List --}}
                <div class="overflow-y-auto flex-1">
                    <template x-for="country in filteredCountries" :key="country.id">
                        <button type="button"
                                @click="selectCountry(country)"
                                class="w-full px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 focus:outline-none transition-colors duration-150"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/20': country.id === countryId }">
                            <div class="flex items-center">
                                <template x-if="flagLoaded[country.iso2]">
                                    <img :src="`/assets/images/flags/32x24/${country.iso2.toLowerCase()}.png`" 
                                         :alt="country.name_en"
                                         class="w-5 h-4 mr-3 rounded-sm">
                                </template>
                                <template x-if="!flagLoaded[country.iso2]">
                                    <span class="inline-flex items-center justify-center w-5 h-4 mr-3 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded-sm text-gray-600 dark:text-gray-300"
                                          x-text="country.iso2.toUpperCase()"></span>
                                </template>
                                <span class="text-sm text-gray-900 dark:text-white" x-text="country.name_en"></span>
                                <span class="ml-auto text-sm text-gray-500 dark:text-gray-400" x-text="`+${country.phone_code}`"></span>
                            </div>
                        </button>
                    </template>
                    <div x-show="filteredCountries.length === 0" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">
                        {{ __('common.no_results') }}
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Phone Number Input --}}
        <input type="tel"
               id="{{ $name }}"
               x-model="phoneNumber"
               @input="updateFullNumber"
               placeholder="{{ __('common.phone_placeholder') }}"
               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white"
               @if($required) required @endif
               @if($disabled) disabled @endif>
        
        {{-- Hidden input with full phone number --}}
        <input type="hidden" 
               name="{{ $name }}" 
               x-model="fullPhoneNumber"
               @if($model) x-effect="$wire.set('{{ $model }}', fullPhoneNumber)" @endif>
    </div>
    
    @if($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<script>
// Meet2Be: Phone input component logic
function phoneInput(initialData) {
    return {
        countryId: initialData.countryId,
        phoneNumber: initialData.phoneNumber || '',
        fullPhoneNumber: '',
        showCountryDropdown: false,
        countrySearch: '',
        countries: @json($countries ?? \App\Models\System\Country::whereNotNull('phone_code')->orderBy('name_en')->get()),
        flagLoaded: {},
        
        init() {
            this.updateFullNumber();
            
            // Meet2Be: Set initial full phone number if value exists
            if (initialData.phoneNumber && this.selectedCountry) {
                this.fullPhoneNumber = `+${this.selectedCountry.phone_code}${initialData.phoneNumber}`;
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
            return this.countries.find(c => c.id === this.countryId);
        },
        
        get filteredCountries() {
            if (!this.countrySearch) return this.countries;
            
            const search = this.countrySearch.toLowerCase();
            return this.countries.filter(country => 
                country.name_en.toLowerCase().includes(search) ||
                country.iso2.toLowerCase().includes(search) ||
                country.iso3.toLowerCase().includes(search) ||
                country.phone_code.includes(search)
            );
        },
        
        toggleDropdown() {
            this.showCountryDropdown = !this.showCountryDropdown;
            if (this.showCountryDropdown) {
                this.$nextTick(() => {
                    this.$refs.countrySearchInput?.focus();
                });
            }
        },
        
        closeDropdown() {
            this.showCountryDropdown = false;
            this.countrySearch = '';
        },
        
        selectCountry(country) {
            this.countryId = country.id;
            this.closeDropdown();
            this.updateFullNumber();
        },
        
        updateFullNumber() {
            if (this.selectedCountry && this.phoneNumber) {
                // Meet2Be: Remove any non-numeric characters from phone number
                const cleanNumber = this.phoneNumber.replace(/\D/g, '');
                this.fullPhoneNumber = `+${this.selectedCountry.phone_code}${cleanNumber}`;
            } else {
                this.fullPhoneNumber = '';
            }
        }
    }
}
</script> 