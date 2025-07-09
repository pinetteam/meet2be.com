/**
 * Loading State Management
 * Controls global loading indicators and overlays
 */

import Alpine from 'alpinejs';

Alpine.store('loading', {
    isLoading: false,
    message: null,
    
    /**
     * Show loading overlay with optional message
     * @param {string} message - Optional loading message
     */
    show(message = null) {
        this.isLoading = true;
        this.message = message;
    },
    
    /**
     * Hide loading overlay
     */
    hide() {
        this.isLoading = false;
        this.message = null;
    },
    
    /**
     * Execute async function with loading overlay
     * @param {Function} fn - Async function to execute
     * @param {string} message - Loading message
     * @returns {Promise}
     */
    async wrap(fn, message = null) {
        this.show(message);
        try {
            return await fn();
        } finally {
            this.hide();
        }
    },
    
    /**
     * Show loading bar at top of page
     */
    showBar() {
        this.isLoading = true;
    },
    
    /**
     * Hide loading bar
     */
    hideBar() {
        this.isLoading = false;
    }
});

// Global access
window.loading = Alpine.store('loading');

// Auto-hide loading on page navigation
document.addEventListener('DOMContentLoaded', () => {
    // Hide initial page loading
    setTimeout(() => {
        const pageLoading = document.querySelector('[x-data*="pageLoading"]');
        if (pageLoading && pageLoading.__x) {
            pageLoading.__x.$data.pageLoading = false;
        }
    }, 100);
});

// Export for module usage
export default Alpine.store('loading'); 