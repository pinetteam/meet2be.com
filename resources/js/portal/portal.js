/**
 * Portal Application Entry Point
 * Initializes core dependencies and bootstraps the application
 */

import Alpine from 'alpinejs';
import axios from 'axios';

// Core utilities
import './utils/csrf';
import './utils/datetime';
import './utils/loading';

// Global stores
import './stores/theme';
import './stores/alerts';

// Application components
import './utils/portal-app';
import './components/forms';

// Configure axios defaults
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Bootstrap Alpine.js
window.Alpine = Alpine;
Alpine.start(); 