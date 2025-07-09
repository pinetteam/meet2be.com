/**
 * CSRF Token Management
 * Handles automatic CSRF token injection for all HTTP requests
 */

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (token) {
    // Configure axios
    if (window.axios) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    }
    
    // Configure native fetch
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        let [resource, config] = args;
        config = config || {};
        config.headers = config.headers || {};
        config.headers['X-CSRF-TOKEN'] = token;
        return originalFetch(resource, config);
    };
    
    // Expose token globally
    window.csrfToken = token;
} 