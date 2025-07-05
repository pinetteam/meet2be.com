import axios from 'axios';

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Wait for Alpine to be available
document.addEventListener('alpine:init', () => {
    // Alpine.js global stores for site
    Alpine.store('site', {
        user: null,
        
        init() {
            // Initialize site-specific data
        }
    });

    // Alpine.js components for site
    Alpine.data('siteHeader', () => ({
        mobileMenuOpen: false,
        
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        }
    }));
});

// Site-specific functionality
window.site = {
    // Add site-specific methods here
};

console.log('Site JS loaded'); 