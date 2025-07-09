// Meet2Be: Phone Input Component
// Author: Meet2Be Development Team
// International phone input with country selection

export default function phoneInput(config) {
    return {
        // State
        countryDropdownOpen: false,
        countrySearch: '',
        selectedCountryId: config.selectedCountryId || null,
        phoneNumber: config.phoneNumber || '',
        countries: config.countries || [],
        flagLoaded: {},
        focused: false,
        
        // Computed
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
        
        // Methods
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
        },
        
        // Initialize
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
        }
    };
} 