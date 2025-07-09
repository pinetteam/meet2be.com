/**
 * Portal Application Component
 * Main Alpine.js component for portal functionality
 */

import Alpine from 'alpinejs';

window.portalApp = function() {
    return {
        mobileMenuOpen: false,
        currentPage: window.location.pathname.split('/').pop() || 'dashboard',
        openSubmenus: Alpine.$persist([]).as('portal_submenus'),
        
        navigation: [
            {
                name: 'dashboard',
                label: window.translations?.portal?.navigation?.dashboard || 'Dashboard',
                href: '/portal',
                icon: 'fa-solid fa-chart-line'
            }
        ],
        
        init() {
            this.$store.theme.init();
            this.setCurrentPage();
        },
        
        setCurrentPage() {
            const path = window.location.pathname;
            const segments = path.split('/').filter(Boolean);
            this.currentPage = segments[segments.length - 1] || 'dashboard';
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
            this.$store.theme.toggle();
        }
    };
}; 