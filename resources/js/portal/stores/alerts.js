/**
 * Alerts Store
 * Manages global alert notifications
 */

import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.store('alerts', {
        items: [],
        counter: 0,
        
        add(alert) {
            const id = ++this.counter;
            const item = {
                id,
                type: alert.type || 'info',
                title: alert.title,
                message: alert.message,
                list: alert.list || [],
                dismissible: alert.dismissible !== false,
                duration: alert.duration || 5000
            };
            
            this.items.push(item);
            
            if (item.duration > 0) {
                setTimeout(() => this.remove(id), item.duration);
            }
            
            return id;
        },
        
        remove(id) {
            this.items = this.items.filter(item => item.id !== id);
        },
        
        clear() {
            this.items = [];
        },
        
        // Helper methods
        success(message, options = {}) {
            return this.add({
                type: 'success',
                message,
                ...options
            });
        },
        
        error(message, options = {}) {
            return this.add({
                type: 'error',
                message,
                ...options
            });
        },
        
        warning(message, options = {}) {
            return this.add({
                type: 'warning',
                message,
                ...options
            });
        },
        
        info(message, options = {}) {
            return this.add({
                type: 'info',
                message,
                ...options
            });
        }
    });
}); 