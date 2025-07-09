/**
 * Theme Store
 * Manages dark/light theme state with persistence
 */

import Alpine from 'alpinejs';

// Register store after Alpine is available
document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        dark: localStorage.getItem('theme_dark') === 'true',
        
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme_dark', this.dark);
            document.documentElement.classList.toggle('dark', this.dark);
        },
        
        init() {
            document.documentElement.classList.toggle('dark', this.dark);
        }
    });
}); 