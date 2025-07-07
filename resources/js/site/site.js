import Alpine from 'alpinejs';
import axios from 'axios';
import mask from '@alpinejs/mask';

// Alpine.js plugins
Alpine.plugin(mask);

// Alpine'i window objesine ekle
window.Alpine = Alpine;

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

// Global helpers for date/time formatting
window.formatDateTime = function(dateString) {
    if (!dateString) return '';
    
    // Get tenant formats from meta tags or data attributes
    const dateFormat = document.querySelector('meta[name="tenant-date-format"]')?.content || 'Y-m-d';
    const timeFormat = document.querySelector('meta[name="tenant-time-format"]')?.content || 'H:i';
    
    // Convert PHP format to moment.js format (simplified version)
    const formatMap = {
        'd': 'DD', 'j': 'D', 'm': 'MM', 'n': 'M', 'Y': 'YYYY', 'y': 'YY',
        'H': 'HH', 'h': 'hh', 'i': 'mm', 's': 'ss', 'A': 'A', 'a': 'a',
        'M': 'MMM', 'F': 'MMMM'
    };
    
    let jsDateFormat = dateFormat;
    let jsTimeFormat = timeFormat;
    
    Object.entries(formatMap).forEach(([php, js]) => {
        jsDateFormat = jsDateFormat.replace(php, js);
        jsTimeFormat = jsTimeFormat.replace(php, js);
    });
    
    // Format using native JS (for now)
    const date = new Date(dateString);
    return date.toLocaleString(); // Bu kısmı daha sonra geliştirebiliriz
};

window.formatDate = function(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString();
};

window.formatTime = function(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString();
};

// Alpine'i başlat
Alpine.start(); 