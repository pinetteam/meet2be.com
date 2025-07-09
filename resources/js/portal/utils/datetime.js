/**
 * DateTime Formatting Utilities
 * Formats dates and times according to tenant preferences
 */

const settings = (() => {
    const meta = document.querySelector('meta[name="tenant-datetime"]');
    if (!meta) return { timezone: 'UTC', dateFormat: 'Y-m-d', timeFormat: 'H:i', locale: 'en' };
    
    try {
        return JSON.parse(meta.getAttribute('content'));
    } catch {
        return { timezone: 'UTC', dateFormat: 'Y-m-d', timeFormat: 'H:i', locale: 'en' };
    }
})();

const formatters = {
    Y: d => d.getFullYear(),
    y: d => String(d.getFullYear()).slice(-2),
    m: d => String(d.getMonth() + 1).padStart(2, '0'),
    n: d => d.getMonth() + 1,
    d: d => String(d.getDate()).padStart(2, '0'),
    j: d => d.getDate(),
    H: d => String(d.getHours()).padStart(2, '0'),
    G: d => d.getHours(),
    i: d => String(d.getMinutes()).padStart(2, '0'),
    s: d => String(d.getSeconds()).padStart(2, '0')
};

function format(date, pattern) {
    const d = date instanceof Date ? date : new Date(date);
    return pattern.replace(/[YymndjHGis]/g, match => formatters[match](d));
}

// Public API
window.formatDate = (date) => format(date, settings.dateFormat);
window.formatTime = (date) => format(date, settings.timeFormat);
window.formatDateTime = (date) => format(date, `${settings.dateFormat} ${settings.timeFormat}`);

// Get relative time
export function getRelativeTime(date) {
    const dateObj = date instanceof Date ? date : new Date(date);
    const now = new Date();
    const diff = now - dateObj;
    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    
    if (seconds < 60) {
        return window.translations?.time?.just_now || 'Just now';
    } else if (minutes < 60) {
        return `${minutes} ${window.translations?.time?.minutes_ago || 'minutes ago'}`;
    } else if (hours < 24) {
        return `${hours} ${window.translations?.time?.hours_ago || 'hours ago'}`;
    } else if (days < 7) {
        return `${days} ${window.translations?.time?.days_ago || 'days ago'}`;
    } else {
        return formatDate(dateObj);
    }
}

// Initialize datetime utilities
export function initDateTime() {
    // Add global helpers
    window.formatDate = formatDate;
    window.formatTime = formatTime;
    window.formatDateTime = formatDateTime;
    window.getRelativeTime = getRelativeTime;
}

// Auto-initialize
initDateTime();

// Export for module usage
export default {
    getTenantDateTimeSettings: () => settings,
    formatDate,
    formatTime,
    formatDateTime,
    getRelativeTime,
    initDateTime
}; 