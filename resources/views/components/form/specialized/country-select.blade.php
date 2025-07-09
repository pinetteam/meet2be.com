{{-- Meet2Be: Country select component with flags --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Country selector with flag display --}}

@props([
    'name' => 'country_id',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'countries' => null,
    'showFlag' => true,
    'showCode' => false
])

@php
    $label = $label ?? __('common.labels.country');
    $placeholder = $placeholder ?? __('common.messages.select_option');
    $countries = $countries ?? \App\Models\System\Country::orderBy('name_en')->get();
@endphp

<x-form.select
    :name="$name"
    :label="$label"
    :value="$value"
    :placeholder="$placeholder"
    :hint="$hint"
    :required="$required"
    :disabled="$disabled"
    :model="$model"
    :size="$size"
    :wrapper-class="$wrapperClass"
    searchable>
    
    @foreach($countries as $country)
        <option value="{{ $country->id }}"
                data-flag="{{ $country->iso2 }}">
            {{ $country->name_en }}
            @if($showCode)
                ({{ $country->iso2 }})
            @endif
        </option>
    @endforeach
    
</x-form.select>

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
            // Meet2Be: Handle x-model binding
            if (initialData.xModel) {
                // Watch external model changes
                this.$watch('$parent.' + initialData.xModel, (value) => {
                    if (value !== this.selectedValue) {
                        this.selectedValue = value;
                    }
                });
                
                // Update parent model when selection changes
                this.$watch('selectedValue', (value) => {
                    this.$parent[initialData.xModel] = value;
                });
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