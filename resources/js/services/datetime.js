/**
 * DateTime SDK for Frontend
 * Syncs with backend tenant settings
 */
class DateTimeManager {
    constructor() {
        this.timezone = null;
        this.dateFormat = null;
        this.timeFormat = null;
        this.locale = null;
        this.initialized = false;
        
        this.init();
    }
    
    init() {
        // Get settings from meta tags or config
        const meta = document.querySelector('meta[name="tenant-datetime"]');
        if (meta) {
            const config = JSON.parse(meta.content);
            this.timezone = config.timezone;
            this.dateFormat = config.dateFormat;
            this.timeFormat = config.timeFormat;
            this.locale = config.locale;
            this.initialized = true;
        }
    }
    
    /**
     * Parse date string or Date object
     */
    parse(date) {
        if (!date) return null;
        
        if (date instanceof Date) {
            return date;
        }
        
        if (typeof date === 'string') {
            return new Date(date);
        }
        
        return new Date();
    }
    
    /**
     * Format date using tenant settings
     */
    formatDate(date) {
        const parsed = this.parse(date);
        if (!parsed) return '';
        
        // Use native Intl.DateTimeFormat for better localization
        const options = this.getDateOptions();
        return new Intl.DateTimeFormat(this.locale || 'en', options).format(parsed);
    }
    
    /**
     * Format time using tenant settings
     */
    formatTime(date) {
        const parsed = this.parse(date);
        if (!parsed) return '';
        
        const options = this.getTimeOptions();
        return new Intl.DateTimeFormat(this.locale || 'en', options).format(parsed);
    }
    
    /**
     * Format datetime using tenant settings
     */
    formatDateTime(date) {
        const parsed = this.parse(date);
        if (!parsed) return '';
        
        return `${this.formatDate(parsed)} ${this.formatTime(parsed)}`;
    }
    
    /**
     * Format relative time
     */
    formatRelative(date) {
        const parsed = this.parse(date);
        if (!parsed) return '';
        
        const now = new Date();
        const diffMs = now - parsed;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        // Use Intl.RelativeTimeFormat for better localization
        const rtf = new Intl.RelativeTimeFormat(this.locale || 'en', { numeric: 'auto' });
        
        if (Math.abs(diffDay) >= 7) {
            return this.formatDate(parsed);
        } else if (Math.abs(diffDay) >= 1) {
            return rtf.format(-diffDay, 'day');
        } else if (Math.abs(diffHour) >= 1) {
            return rtf.format(-diffHour, 'hour');
        } else if (Math.abs(diffMin) >= 1) {
            return rtf.format(-diffMin, 'minute');
        } else {
            return rtf.format(-diffSec, 'second');
        }
    }
    
    /**
     * Get date formatting options based on format string
     */
    getDateOptions() {
        const formatMap = {
            'Y-m-d': { year: 'numeric', month: '2-digit', day: '2-digit' },
            'd/m/Y': { day: '2-digit', month: '2-digit', year: 'numeric' },
            'm/d/Y': { month: '2-digit', day: '2-digit', year: 'numeric' },
            'd.m.Y': { day: '2-digit', month: '2-digit', year: 'numeric' },
            'd-m-Y': { day: '2-digit', month: '2-digit', year: 'numeric' },
            'M j, Y': { month: 'short', day: 'numeric', year: 'numeric' },
            'F j, Y': { month: 'long', day: 'numeric', year: 'numeric' },
            'j F Y': { day: 'numeric', month: 'long', year: 'numeric' }
        };
        
        return formatMap[this.dateFormat] || formatMap['Y-m-d'];
    }
    
    /**
     * Get time formatting options based on format string
     */
    getTimeOptions() {
        const formatMap = {
            'H:i': { hour: '2-digit', minute: '2-digit', hour12: false },
            'H:i:s': { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false },
            'g:i A': { hour: 'numeric', minute: '2-digit', hour12: true },
            'g:i:s A': { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true },
            'h:i A': { hour: '2-digit', minute: '2-digit', hour12: true },
            'h:i:s A': { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }
        };
        
        return formatMap[this.timeFormat] || formatMap['H:i'];
    }
    
    /**
     * Check if date is today
     */
    isToday(date) {
        const parsed = this.parse(date);
        if (!parsed) return false;
        
        const today = new Date();
        return parsed.toDateString() === today.toDateString();
    }
    
    /**
     * Check if date is yesterday
     */
    isYesterday(date) {
        const parsed = this.parse(date);
        if (!parsed) return false;
        
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        return parsed.toDateString() === yesterday.toDateString();
    }
    
    /**
     * Check if date is tomorrow
     */
    isTomorrow(date) {
        const parsed = this.parse(date);
        if (!parsed) return false;
        
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        return parsed.toDateString() === tomorrow.toDateString();
    }
}

// Create global instance
window.DateTime = new DateTimeManager();

// Helper functions for easy access
window.formatDate = (date) => window.DateTime.formatDate(date);
window.formatTime = (date) => window.DateTime.formatTime(date);
window.formatDateTime = (date) => window.DateTime.formatDateTime(date);
window.formatRelative = (date) => window.DateTime.formatRelative(date);

// Alpine.js integration
document.addEventListener('alpine:init', () => {
    Alpine.magic('date', () => (date) => window.DateTime.formatDate(date));
    Alpine.magic('time', () => (date) => window.DateTime.formatTime(date));
    Alpine.magic('datetime', () => (date) => window.DateTime.formatDateTime(date));
    Alpine.magic('relative', () => (date) => window.DateTime.formatRelative(date));
});

export default DateTimeManager; 