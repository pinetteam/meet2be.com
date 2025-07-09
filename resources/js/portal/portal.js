import Alpine from 'alpinejs';
import '../services/datetime';

// Alpine'i window objesine ekle
window.Alpine = Alpine;

// Meet2Be: Searchable select component
window.searchableSelect = function(config) {
    return {
        // Configuration
        name: config.name || '',
        placeholder: config.placeholder || 'Select...',
        grouped: config.grouped || false,
        disabled: config.disabled || false,
        size: config.size || 'md',
        ajax: config.ajax || false,
        ajaxUrl: config.ajaxUrl || null,
        xModel: config.xModel || null,
        
        // State
        showDropdown: false,
        search: '',
        selectedValue: config.value || '',
        selectedDisplay: '',
        loading: false,
        options: [],
        errors: false,
        focusedIndex: -1,
        
        // Initialization
        init() {
            // Load initial options
            this.loadOptions();
            
            // Set initial display
            this.updateSelectedDisplay();
            
            // Handle x-model binding
            if (this.xModel) {
                this.$watch(this.xModel, (value) => {
                    this.selectedValue = value;
                    this.updateSelectedDisplay();
                });
            }
            
            // Handle clicks outside
            document.addEventListener('click', (e) => {
                if (!this.$el.contains(e.target)) {
                    this.closeDropdown();
                }
            });
        },
        
        // Load options
        async loadOptions() {
            if (this.ajax && this.ajaxUrl) {
                this.loading = true;
                try {
                    const response = await fetch(this.ajaxUrl);
                    const data = await response.json();
                    this.options = data;
                } catch (error) {
                    console.error('Failed to load options:', error);
                    this.errors = true;
                } finally {
                    this.loading = false;
                }
            } else {
                // Options are passed via HTML
                const selectEl = this.$el.querySelector('select.hidden');
                if (selectEl) {
                    this.options = this.parseSelectOptions(selectEl);
                }
            }
        },
        
        // Parse options from hidden select element
        parseSelectOptions(selectEl) {
            const options = [];
            
            if (this.grouped) {
                selectEl.querySelectorAll('optgroup').forEach(group => {
                    const groupOptions = [];
                    group.querySelectorAll('option').forEach(option => {
                        if (option.value) {
                            groupOptions.push({
                                value: option.value,
                                label: option.textContent.trim(),
                                selected: option.selected
                            });
                        }
                    });
                    
                    if (groupOptions.length > 0) {
                        options.push({
                            label: group.label,
                            options: groupOptions
                        });
                    }
                });
            } else {
                selectEl.querySelectorAll('option').forEach(option => {
                    if (option.value) {
                        options.push({
                            value: option.value,
                            label: option.textContent.trim(),
                            selected: option.selected
                        });
                    }
                });
            }
            
            return options;
        },
        
        // Update selected display text
        updateSelectedDisplay() {
            if (!this.selectedValue) {
                this.selectedDisplay = '';
                return;
            }
            
            if (this.grouped) {
                for (const group of this.options) {
                    const option = group.options.find(o => o.value === this.selectedValue);
                    if (option) {
                        this.selectedDisplay = option.label;
                        break;
                    }
                }
            } else {
                const option = this.options.find(o => o.value === this.selectedValue);
                if (option) {
                    this.selectedDisplay = option.label;
                }
            }
        },
        
        // Toggle dropdown
        toggleDropdown() {
            if (this.disabled) return;
            this.showDropdown = !this.showDropdown;
            
            if (this.showDropdown) {
                this.$nextTick(() => {
                    const searchInput = this.$el.querySelector('input[type="search"]');
                    if (searchInput) searchInput.focus();
                });
            }
        },
        
        // Close dropdown
        closeDropdown() {
            this.showDropdown = false;
            this.search = '';
            this.focusedIndex = -1;
        },
        
        // Select option
        selectOption(value) {
            this.selectedValue = value;
            this.updateSelectedDisplay();
            this.closeDropdown();
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            this.$el.querySelector('input[type="hidden"]').dispatchEvent(event);
        },
        
        // Get filtered options HTML
        get filteredOptionsHtml() {
            let html = '';
            const searchLower = this.search.toLowerCase();
            
            if (this.grouped) {
                this.options.forEach(group => {
                    const filteredOptions = group.options.filter(option => 
                        option.label.toLowerCase().includes(searchLower)
                    );
                    
                    if (filteredOptions.length > 0) {
                        html += `<li class="text-xs font-semibold text-gray-400 dark:text-gray-500 px-3 py-2 uppercase tracking-wider">${group.label}</li>`;
                        
                        filteredOptions.forEach((option, index) => {
                            const isSelected = option.value === this.selectedValue;
                            const isFocused = this.focusedIndex === index;
                            
                            html += `
                                <li>
                                    <button type="button" 
                                        @click="selectOption('${option.value}')"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 focus:bg-gray-50 dark:focus:bg-gray-700 focus:outline-none transition-colors duration-150 ${isSelected ? 'bg-blue-50 dark:bg-blue-900/50' : ''} ${isFocused ? 'bg-gray-50 dark:bg-gray-700' : ''}"
                                        role="option"
                                        aria-selected="${isSelected}">
                                        <span class="block truncate ${isSelected ? 'font-semibold' : ''}">${option.label}</span>
                                    </button>
                                </li>
                            `;
                        });
                    }
                });
            } else {
                const filteredOptions = this.options.filter(option => 
                    option.label.toLowerCase().includes(searchLower)
                );
                
                filteredOptions.forEach((option, index) => {
                    const isSelected = option.value === this.selectedValue;
                    const isFocused = this.focusedIndex === index;
                    
                    html += `
                        <li>
                            <button type="button" 
                                @click="selectOption('${option.value}')"
                                class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 focus:bg-gray-50 dark:focus:bg-gray-700 focus:outline-none transition-colors duration-150 ${isSelected ? 'bg-blue-50 dark:bg-blue-900/50' : ''} ${isFocused ? 'bg-gray-50 dark:bg-gray-700' : ''}"
                                role="option"
                                aria-selected="${isSelected}">
                                <span class="block truncate ${isSelected ? 'font-semibold' : ''}">${option.label}</span>
                            </button>
                        </li>
                    `;
                });
                
                if (filteredOptions.length === 0) {
                    html = '<li class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">No results found</li>';
                }
            }
            
            return html;
        },
        
        // Keyboard navigation
        handleKeydown(event) {
            if (!this.showDropdown) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    this.toggleDropdown();
                }
                return;
            }
            
            switch (event.key) {
                case 'Escape':
                    this.closeDropdown();
                    break;
                case 'ArrowDown':
                    event.preventDefault();
                    this.focusNext();
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.focusPrevious();
                    break;
                case 'Enter':
                    event.preventDefault();
                    if (this.focusedIndex >= 0) {
                        const options = this.grouped ? this.options.flatMap(g => g.options) : this.options;
                        const filteredOptions = options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
                        if (filteredOptions[this.focusedIndex]) {
                            this.selectOption(filteredOptions[this.focusedIndex].value);
                        }
                    }
                    break;
            }
        },
        
        focusNext() {
            const options = this.grouped ? this.options.flatMap(g => g.options) : this.options;
            const filteredOptions = options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
            
            if (this.focusedIndex < filteredOptions.length - 1) {
                this.focusedIndex++;
            } else {
                this.focusedIndex = 0;
            }
        },
        
        focusPrevious() {
            const options = this.grouped ? this.options.flatMap(g => g.options) : this.options;
            const filteredOptions = options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
            
            if (this.focusedIndex > 0) {
                this.focusedIndex--;
            } else {
                this.focusedIndex = filteredOptions.length - 1;
            }
        }
    };
};

// Meet2Be: Country select component
window.countrySelect = function(config) {
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
};

// Meet2Be: Phone input component
window.phoneInput = function(config) {
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
};

// Meet2Be: Timezone select component
window.timezoneSelect = function(config) {
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
};

// Portal navigation configuration
const navigationConfig = {
    items: [
        {
            name: 'dashboard',
            label: window.translations?.navigation?.dashboard || 'Dashboard',
            href: '/portal',
            icon: 'fa-solid fa-house'
        },
        {
            name: 'preparation',
            label: window.translations?.navigation?.preparation || 'Preparation',
            icon: 'fa-solid fa-clipboard-check',
            children: [
                { 
                    name: 'documents', 
                    label: window.translations?.navigation?.documents || 'Documents',
                    href: '/portal/documents',
                    icon: 'fa-solid fa-file-alt' 
                },
                { 
                    name: 'participants', 
                    label: window.translations?.navigation?.participants || 'Participants',
                    href: '/portal/participants',
                    icon: 'fa-solid fa-users' 
                }
            ]
        },
        {
            name: 'event_activity',
            label: window.translations?.navigation?.event_activity || 'Event & Activity',
            icon: 'fa-solid fa-calendar-days',
            children: [
                { 
                    name: 'announcements', 
                    label: window.translations?.navigation?.announcements || 'Announcements',
                    href: '/portal/announcements',
                    icon: 'fa-solid fa-bullhorn' 
                },
                { 
                    name: 'score_games', 
                    label: window.translations?.navigation?.score_games || 'Score Games',
                    href: '/portal/score-games',
                    icon: 'fa-solid fa-gamepad' 
                },
                { 
                    name: 'surveys', 
                    label: window.translations?.navigation?.surveys || 'Surveys',
                    href: '/portal/surveys',
                    icon: 'fa-solid fa-poll' 
                }
            ]
        },
        {
            name: 'environment',
            label: window.translations?.navigation?.environment || 'Environment',
            icon: 'fa-solid fa-building',
            children: [
                { 
                    name: 'halls', 
                    label: window.translations?.navigation?.halls || 'Halls',
                    href: '/portal/halls',
                    icon: 'fa-solid fa-door-open' 
                },
                { 
                    name: 'virtual_stands', 
                    label: window.translations?.navigation?.virtual_stands || 'Virtual Stands',
                    href: '/portal/virtual-stands',
                    icon: 'fa-solid fa-store' 
                }
            ]
        },
        {
            name: 'system_management',
            label: window.translations?.navigation?.system_management || 'System Management',
            icon: 'fa-solid fa-sliders',
            children: [
                { 
                    name: 'tenants', 
                    label: window.translations?.navigation?.tenants || 'Tenants',
                    href: '/portal/tenants',
                    icon: 'fa-solid fa-building-user' 
                },
                { 
                    name: 'countries', 
                    label: window.translations?.navigation?.countries || 'Countries',
                    href: '/portal/countries',
                    icon: 'fa-solid fa-globe' 
                },
                { 
                    name: 'languages', 
                    label: window.translations?.navigation?.languages || 'Languages',
                    href: '/portal/languages',
                    icon: 'fa-solid fa-language' 
                },
                { 
                    name: 'currencies', 
                    label: window.translations?.navigation?.currencies || 'Currencies',
                    href: '/portal/currencies',
                    icon: 'fa-solid fa-money-bill-wave' 
                },
                { 
                    name: 'timezones', 
                    label: window.translations?.navigation?.timezones || 'Timezones',
                    href: '/portal/timezones',
                    icon: 'fa-solid fa-clock' 
                }
            ]
        },
        {
            name: 'reports',
            label: window.translations?.navigation?.reports || 'Reports',
            href: '/portal/reports',
            icon: 'fa-solid fa-chart-bar'
        }
    ]
};

// Theme manager
const themeManager = {
    isDark() {
        return localStorage.getItem('darkMode') === 'true' || 
               (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    },
    
    setTheme(isDark) {
        localStorage.setItem('darkMode', isDark);
        this.applyTheme(isDark);
    },
    
    applyTheme(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    
    init() {
        this.applyTheme(this.isDark());
        
        // Watch for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('darkMode')) {
                this.applyTheme(e.matches);
            }
        });
    }
};

// Portal app definition
window.portalApp = function() {
    return {
        // State
        mobileMenuOpen: false,
        darkMode: themeManager.isDark(),
        currentPage: 'dashboard',
        openSubmenus: [],
        navigation: navigationConfig.items,
        
        // Methods
        toggleSubmenu(name) {
            const index = this.openSubmenus.indexOf(name);
            if (index > -1) {
                this.openSubmenus.splice(index, 1);
            } else {
                this.openSubmenus.push(name);
            }
        },
        
        isSubmenuOpen(name) {
            return this.openSubmenus.includes(name);
        },
        
        hasActiveChild(item) {
            if (!item.children) return false;
            return item.children.some(child => child.name === this.currentPage);
        },
        
        closeMobileMenu() {
            this.mobileMenuOpen = false;
        },
        
        toggleTheme() {
            this.darkMode = !this.darkMode;
            themeManager.setTheme(this.darkMode);
        },
        
        detectCurrentPage() {
            const path = window.location.pathname;
            
            // Check direct navigation items
            this.navigation.forEach(item => {
                if (item.href === path) {
                    this.currentPage = item.name;
                } else if (item.children) {
                    item.children.forEach(child => {
                        if (child.href === path) {
                            this.currentPage = child.name;
                            this.openSubmenus.push(item.name);
                        }
                    });
                }
            });
            
            // Check special pages
            if (path === '/portal/user') {
                this.currentPage = 'users';
            } else if (path.startsWith('/portal/setting')) {
                this.currentPage = 'settings';
            }
        },
        
        // Lifecycle
        init() {
            // Initialize theme
            themeManager.init();
            
            // Detect current page
            this.detectCurrentPage();
        }
    };
};

// Alpine.js global stores
Alpine.store('portal', {
    // Global portal state and methods
    user: null,
    notifications: [],
    locale: window.locale || 'tr',
    
    showToast(message, type = 'success') {
        // Toast notification implementation
        const toast = { message, type, id: Date.now() };
        this.notifications.push(toast);
        
        setTimeout(() => {
            this.notifications = this.notifications.filter(n => n.id !== toast.id);
        }, 3000);
    },
    
    changeLocale(locale) {
        // Locale değiştirme
        window.location.href = `/locale/${locale}?redirect=${window.location.pathname}`;
    }
});

// Helper functions
window.portal = {
    // Format currency
    formatCurrency(amount, currency = 'TRY') {
        const locale = window.locale === 'tr' ? 'tr-TR' : 'en-US';
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: currency
        }).format(amount);
    },
    
    // Format date
    formatDate(date) {
        const locale = window.locale === 'tr' ? 'tr-TR' : 'en-US';
        return new Date(date).toLocaleDateString(locale);
    },
    
    // Confirm dialog
    async confirm(message, title = null) {
        const defaultTitle = window.translations?.general?.confirm || 'Confirm';
        return window.confirm(`${title || defaultTitle}\n\n${message}`);
    },
    
    // Get translation
    t(key, replacements = {}) {
        const keys = key.split('.');
        let value = window.translations;
        
        for (const k of keys) {
            if (value && typeof value === 'object' && k in value) {
                value = value[k];
            } else {
                return key;
            }
        }
        
        if (typeof value === 'string') {
            // Replace placeholders
            return value.replace(/:(\w+)/g, (match, key) => {
                return replacements[key] || match;
            });
        }
        
        return key;
    }
};

console.log('Portal JS loaded');

// Initialize Alpine.js
Alpine.start(); 