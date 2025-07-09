// Meet2Be: Portal Application
// Author: Meet2Be Development Team
// Main portal application logic

import searchableSelect from '../components/select';
import countrySelect from '../components/country-select';
import phoneInput from '../components/phone-input';
import timezoneSelect from '../components/timezone-select';
import { formatCurrency, formatDate, t } from './translations';

/**
 * Portal App Component
 */
const portalApp = () => ({
    sidebarOpen: false,
    searchOpen: false,
    searchQuery: '',
    navigation: [],
    currentPage: '',
    openSubmenus: [],
    mobileMenuOpen: false,
    
    init() {
        // Initialize navigation
        this.initNavigation();
        
        // Initialize sidebar state from localStorage
        this.sidebarOpen = localStorage.getItem('sidebarOpen') !== 'false';
        
        // Watch for sidebar state changes
        this.$watch('sidebarOpen', value => {
            localStorage.setItem('sidebarOpen', value);
        });
        
        // Detect current page
        this.detectCurrentPage();
    },
    
    initNavigation() {
        this.navigation = [
            {
                name: 'dashboard',
                label: window.translations?.portal?.navigation?.dashboard || 'Dashboard',
                href: '/portal',
                icon: 'fa-solid fa-house'
            },
            {
                name: 'events',
                label: window.translations?.portal?.navigation?.events || 'Events',
                icon: 'fa-solid fa-calendar-days',
                children: [
                    {
                        name: 'events-list',
                        label: window.translations?.portal?.navigation?.all_events || 'All Events',
                        href: '/portal/events',
                        icon: 'fa-solid fa-list'
                    },
                    {
                        name: 'events-create',
                        label: window.translations?.portal?.navigation?.create_event || 'Create Event',
                        href: '/portal/events/create',
                        icon: 'fa-solid fa-plus'
                    }
                ]
            }
        ];
    },
    
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },
    
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
        return item.children?.some(child => child.name === this.currentPage);
    },
    
    closeMobileMenu() {
        this.mobileMenuOpen = false;
    },
    
    toggleTheme() {
        Alpine.store('theme').toggle();
    },
    
    detectCurrentPage() {
        const path = window.location.pathname;
        
        // Check direct matches
        this.navigation.forEach(item => {
            if (item.href === path) {
                this.currentPage = item.name;
            }
            
            // Check children
            if (item.children) {
                item.children.forEach(child => {
                    if (child.href === path) {
                        this.currentPage = child.name;
                        this.openSubmenus.push(item.name);
                    }
                });
            }
        });
    },
    
    toggleSearch() {
        this.searchOpen = !this.searchOpen;
        if (this.searchOpen) {
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        }
    },
    
    performSearch() {
        if (this.searchQuery.trim()) {
            // Implement search logic here
            console.log('Searching for:', this.searchQuery);
        }
    }
});

/**
 * Initialize portal application
 */
export function initializePortalApp() {
    // Register components in window
    window.searchableSelect = searchableSelect;
    window.countrySelect = countrySelect;
    window.phoneInput = phoneInput;
    window.timezoneSelect = timezoneSelect;
    
    // Register utilities
    window.formatCurrency = formatCurrency;
    window.formatDate = formatDate;
    window.t = t;
    
    // FontAwesome configuration (if needed)
    if (window.FontAwesome) {
        window.FontAwesome.config = {
            searchPseudoElements: true,
            observeMutations: true
        };
    }
}

export default portalApp; 