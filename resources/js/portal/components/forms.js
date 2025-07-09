/**
 * Form Components
 * Alpine.js data components for form handling
 */

import Alpine from 'alpinejs';

// Form submission handler
Alpine.data('formHandler', (url = '', method = 'POST') => ({
    processing: false,
    errors: {},
    
    async submit(event) {
        event.preventDefault();
        this.processing = true;
        this.errors = {};
        
        try {
            const response = await window.axios({
                method,
                url: url || event.target.action,
                data: new FormData(event.target)
            });
            
            this.$dispatch('form-success', response.data);
            
            if (event.target.hasAttribute('data-reset')) {
                event.target.reset();
            }
        } catch (error) {
            if (error.response?.status === 422) {
                this.errors = error.response.data.errors || {};
            }
        } finally {
            this.processing = false;
        }
    },
    
    hasError(field) {
        return field in this.errors;
    },
    
    getError(field) {
        return this.errors[field]?.[0] || '';
    }
}));

// Auto-register form components
document.addEventListener('alpine:init', () => {
    // Register any additional form components here
}); 