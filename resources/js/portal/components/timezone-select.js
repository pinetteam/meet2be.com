// Meet2Be: Timezone Select Component
// Author: Meet2Be Development Team
// Timezone selection with grouping

export default function timezoneSelect(config) {
    return {
        // State
        open: false,
        search: '',
        selected: config.value || '',
        timezones: config.timezones || [],
        
        // Computed
        get filteredTimezones() {
            if (!this.search) return this.timezones;
            
            const searchLower = this.search.toLowerCase();
            const filtered = {};
            
            Object.entries(this.timezones).forEach(([region, zones]) => {
                const filteredZones = zones.filter(zone => 
                    zone.name.toLowerCase().includes(searchLower) ||
                    zone.offset.toLowerCase().includes(searchLower)
                );
                
                if (filteredZones.length > 0) {
                    filtered[region] = filteredZones;
                }
            });
            
            return filtered;
        },
        
        get selectedTimezone() {
            for (const [region, zones] of Object.entries(this.timezones)) {
                const zone = zones.find(z => z.id === this.selected);
                if (zone) return zone;
            }
            return null;
        },
        
        get displayText() {
            const timezone = this.selectedTimezone;
            return timezone ? `${timezone.name} (${timezone.offset})` : config.placeholder || 'Select a timezone';
        },
        
        // Methods
        selectTimezone(timezoneId) {
            this.selected = timezoneId;
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
    };
} 