// Meet2Be: Helper Functions
// Author: Meet2Be Development Team
// Common utility functions for portal

import { trans } from './translations';

/**
 * Show notification
 */
export function notify(message, type = 'info') {
    Alpine.store('alerts').add(message, type);
}

/**
 * Show validation errors
 */
export function showValidationErrors(errors) {
    Alpine.store('alerts').addErrors(errors);
}

/**
 * Toggle sidebar
 */
export function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    
    sidebar?.classList.toggle('translate-x-0');
    sidebar?.classList.toggle('-translate-x-full');
    mainContent?.classList.toggle('lg:ml-64');
}

/**
 * Global search handler
 */
export function globalSearch(query) {
    if (!query || query.trim() === '') return;
    
    // Implement search logic
    console.log('Searching for:', query);
}

/**
 * Close dropdown on outside click
 */
export function closeDropdown(event) {
    const dropdowns = document.querySelectorAll('[x-data*="open"]');
    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target)) {
            dropdown.__x?.updateExpression(() => dropdown.__x.$data.open = false);
        }
    });
}

// Export trans function from translations
export { trans as t } from './translations';
export { formatCurrency, formatDate } from './translations'; 