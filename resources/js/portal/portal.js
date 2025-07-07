import Alpine from 'alpinejs';
import '../services/datetime';

// Alpine'i window objesine ekle
window.Alpine = Alpine;

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