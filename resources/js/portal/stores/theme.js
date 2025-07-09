// Meet2Be: Theme Store
// Author: Meet2Be Development Team
// Manages theme (light/dark mode) state

import Alpine from 'alpinejs';

// Register store after Alpine is available
document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        dark: Alpine.$persist(false).as('theme_dark'),
        
        init() {
            // Apply theme on initialization
            this.apply();
            
            // Watch for changes
            Alpine.effect(() => {
                this.apply();
            });
        },
        
        toggle() {
            this.dark = !this.dark;
        },
        
        apply() {
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });
}); 