import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import mask from '@alpinejs/mask'
import axios from 'axios';
import '../services/datetime';

Alpine.plugin(collapse)
Alpine.plugin(mask)

// Alpine'i window objesine ekle
window.Alpine = Alpine;

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Wait for Alpine to be available
document.addEventListener('alpine:init', () => {
    // Alpine.js global stores for portal
    Alpine.store('portal', {
        sidebarOpen: true,
        notifications: [],
        
        init() {
            // Initialize portal-specific data
        },
        
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        }
    });

    // Alpine.js components for portal
    Alpine.data('portalSidebar', () => ({
        activeMenu: window.location.pathname,
        
        isActive(path) {
            return this.activeMenu.startsWith(path);
        }
    }));

    Alpine.data('portalNotifications', () => ({
        show: false,
        
        toggle() {
            this.show = !this.show;
        },
        
        markAsRead(id) {
            // Mark notification as read
        }
    }));
});

// Portal-specific functionality
window.portal = {
    // Add portal-specific methods here
    showToast(message, type = 'success') {
        // Implement toast notification
    }
};

console.log('Portal JS loaded');

// Alpine'i başlat
Alpine.start(); 