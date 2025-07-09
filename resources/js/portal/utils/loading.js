/**
 * Loading State Management
 * Controls global loading indicators and overlays
 */

const loading = {
    /**
     * Show loading overlay with optional message
     * @param {string} message - Optional loading message
     */
    show(message = null) {
        window.dispatchEvent(new CustomEvent('loading', { detail: { message } }));
    },
    
    /**
     * Hide loading overlay
     */
    hide() {
        window.dispatchEvent(new Event('loaded'));
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
        window.dispatchEvent(new Event('loading'));
    },
    
    /**
     * Hide loading bar
     */
    hideBar() {
        window.dispatchEvent(new Event('loaded'));
    }
};

// Global access
window.loading = loading;

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

// Auto-show on form submissions
document.addEventListener('submit', e => {
    if (e.target.tagName === 'FORM' && !e.target.hasAttribute('data-no-loading')) {
        loading.show();
    }
});

// Show loading on AJAX requests
if (window.axios) {
    // Request interceptor
    window.axios.interceptors.request.use(
        (config) => {
            if (!config.noLoading) {
                loading.showBar();
            }
            return config;
        },
        (error) => {
            loading.hideBar();
            return Promise.reject(error);
        }
    );
    
    // Response interceptor
    window.axios.interceptors.response.use(
        (response) => {
            loading.hideBar();
            return response;
        },
        (error) => {
            loading.hideBar();
            return Promise.reject(error);
        }
    );
}

// Export for module usage
export default loading; 