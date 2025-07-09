/**
 * Site Application Entry Point
 * Initializes public-facing site functionality
 */

import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;

// Login form component
Alpine.data('loginForm', () => ({
    email: '',
    password: '',
    remember: false,
    loading: false,
    errors: {},
    
    async submit() {
        this.loading = true;
        this.errors = {};
        
        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: this.email,
                    password: this.password,
                    remember: this.remember
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                window.location.href = data.redirect || '/portal';
            } else if (response.status === 422) {
                this.errors = data.errors || {};
            }
        } catch (error) {
            console.error('Login error:', error);
        } finally {
            this.loading = false;
        }
    }
}));

// Start Alpine
Alpine.start(); 