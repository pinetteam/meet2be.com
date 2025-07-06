import Alpine from 'alpinejs';
import axios from 'axios';

// Alpine'i window objesine ekle
window.Alpine = Alpine;

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Wait for Alpine to be available
document.addEventListener('alpine:init', () => {
    // Alpine.js global stores for portal
    Alpine.store('portal', {
        user: null,
        sidebarOpen: true,
        notifications: [],
        
        init() {
            // Initialize portal-specific data
            this.loadUser();
        },
        
        async loadUser() {
            try {
                const response = await axios.get('/api/user');
                this.user = response.data;
            } catch (error) {
                console.error('Failed to load user:', error);
            }
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