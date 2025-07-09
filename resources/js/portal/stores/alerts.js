// Meet2Be: Alerts Store
// Author: Meet2Be Development Team
// Global alert/notification management

import Alpine from 'alpinejs';

// Register store after Alpine is available
document.addEventListener('alpine:init', () => {
    Alpine.store('alerts', {
        items: [],
        counter: 0,
        
        add(message, type = 'info', options = {}) {
            const id = ++this.counter;
            const alert = {
                id,
                type,
                message,
                title: options.title || null,
                list: options.list || null,
                dismissible: options.dismissible !== false,
                duration: options.duration !== undefined ? options.duration : 5000
            };
            
            this.items.push(alert);
            
            // Auto dismiss if duration is set
            if (alert.duration > 0) {
                setTimeout(() => this.remove(id), alert.duration);
            }
        },
        
        remove(id) {
            this.items = this.items.filter(alert => alert.id !== id);
        },
        
        clear() {
            this.items = [];
        },
        
        // Helper methods
        success(message, options = {}) {
            this.add(message, 'success', options);
        },
        
        error(message, options = {}) {
            this.add(message, 'error', options);
        },
        
        warning(message, options = {}) {
            this.add(message, 'warning', options);
        },
        
        info(message, options = {}) {
            this.add(message, 'info', options);
        },
        
        // Add validation errors
        addErrors(errors) {
            const errorMessages = [];
            for (const field in errors) {
                errors[field].forEach(error => {
                    errorMessages.push(error);
                });
            }
            
            this.error(null, {
                title: window.translations?.validation?.errors_occurred || 'Validation errors occurred',
                list: errorMessages,
                dismissible: true,
                duration: 0
            });
            
            // Scroll to top to show errors
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}); 