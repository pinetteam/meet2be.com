// Meet2Be: Translation Helper
// Author: Meet2Be Development Team
// Simple translation utilities using Laravel's provided translations

/**
 * Get translation by key
 */
export function trans(key, replacements = {}) {
    const keys = key.split('.');
    let value = window.translations || {};
    
    for (const k of keys) {
        if (value && typeof value === 'object' && k in value) {
            value = value[k];
        } else {
            return key;
        }
    }
    
    if (typeof value === 'string') {
        return value.replace(/:(\w+)/g, (match, key) => {
            return replacements[key] || match;
        });
    }
    
    return key;
}

/**
 * Get current locale
 */
export function getLocale() {
    return window.locale || 'en';
}

/**
 * Get datetime formats
 */
export function getDateTimeFormats() {
    return window.datetimeFormats || {
        timezone: 'UTC',
        dateFormat: 'Y-m-d',
        timeFormat: 'H:i',
        locale: 'en'
    };
}

/**
 * Format date using tenant settings
 */
export function formatDate(date, includeTime = false) {
    if (!date) return '';
    
    const d = new Date(date);
    const formats = getDateTimeFormats();
    const dateFormat = formats.dateFormat || 'Y-m-d';
    const timeFormat = formats.timeFormat || 'H:i';
    
    // Convert PHP format to JS format
    let jsFormat = dateFormat
        .replace('Y', d.getFullYear())
        .replace('m', String(d.getMonth() + 1).padStart(2, '0'))
        .replace('n', d.getMonth() + 1)
        .replace('d', String(d.getDate()).padStart(2, '0'))
        .replace('j', d.getDate());
    
    if (includeTime) {
        const hours = d.getHours();
        const minutes = String(d.getMinutes()).padStart(2, '0');
        const seconds = String(d.getSeconds()).padStart(2, '0');
        
        let jsTimeFormat = timeFormat
            .replace('H', String(hours).padStart(2, '0'))
            .replace('G', hours)
            .replace('h', String(hours % 12 || 12).padStart(2, '0'))
            .replace('g', hours % 12 || 12)
            .replace('i', minutes)
            .replace('s', seconds)
            .replace('A', hours >= 12 ? 'PM' : 'AM')
            .replace('a', hours >= 12 ? 'pm' : 'am');
        
        jsFormat += ' ' + jsTimeFormat;
    }
    
    return jsFormat;
}

/**
 * Format currency
 */
export function formatCurrency(amount, currency = 'TRY') {
    return new Intl.NumberFormat(getLocale(), {
        style: 'currency',
        currency: currency
    }).format(amount);
}

// Alias for backward compatibility
export const t = trans; 