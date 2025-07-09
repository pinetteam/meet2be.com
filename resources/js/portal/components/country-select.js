// Meet2Be: Country Select Component
// Author: Meet2Be Development Team
// Country selection with flags

export default function countrySelect(config) {
    return {
        // State
        open: false,
        search: '',
        selected: config.value || '',
        countries: config.countries || [],
        flagLoaded: {},
        
        // Computed
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
            return country ? country.name : config.placeholder || 'Select';
        },
        
        // Methods
        selectCountry(countryId) {
            this.selected = countryId;
            this.open = false;
            this.search = '';
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