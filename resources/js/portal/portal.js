// Meet2Be: Portal Main Application
// Author: Meet2Be Development Team
// Main entry point for portal application

// Import Alpine.js and plugins
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';

// Register Alpine plugins
Alpine.plugin(focus);
Alpine.plugin(persist);

// Initialize Alpine
window.Alpine = Alpine;

// Import utilities and register portalApp BEFORE Alpine starts
import portalApp from './utils/portal-app';
window.portalApp = portalApp;

// Import stores (will register on alpine:init event)
import './stores/theme';
import './stores/alerts';

// Import other utilities
import { initializePortalApp } from './utils/portal-app';

// Initialize portal app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    initializePortalApp();
});

// Start Alpine
Alpine.start(); 