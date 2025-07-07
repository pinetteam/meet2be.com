<?php

use App\Services\DateTime\DateTimeManager;
use App\Services\DateTime\TenantDateTime;

if (!function_exists('dt')) {
    /**
     * Get DateTimeManager instance or parse a date
     */
    function dt($datetime = null): DateTimeManager|TenantDateTime
    {
        $manager = app(DateTimeManager::class);
        
        if ($datetime === null) {
            return $manager;
        }
        
        return $manager->parse($datetime);
    }
}

if (!function_exists('tenant_now')) {
    /**
     * Get current time as TenantDateTime
     */
    function tenant_now(): TenantDateTime
    {
        return dt()->now();
    }
}

if (!function_exists('tenant_today')) {
    /**
     * Get today as TenantDateTime
     */
    function tenant_today(): TenantDateTime
    {
        return dt()->today();
    }
}

if (!function_exists('format_date')) {
    /**
     * Format a date using tenant settings
     */
    function format_date($date): ?string
    {
        if (!$date) {
            return null;
        }
        
        return dt($date)->toDateString();
    }
}

if (!function_exists('format_time')) {
    /**
     * Format a time using tenant settings
     */
    function format_time($date): ?string
    {
        if (!$date) {
            return null;
        }
        
        return dt($date)->toTimeString();
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format a datetime using tenant settings
     */
    function format_datetime($date): ?string
    {
        if (!$date) {
            return null;
        }
        
        return dt($date)->toDateTimeString();
    }
}

if (!function_exists('format_relative')) {
    /**
     * Format as relative time
     */
    function format_relative($date): ?string
    {
        if (!$date) {
            return null;
        }
        
        return dt($date)->toRelativeString();
    }
}

if (!function_exists('date_for_human')) {
    /**
     * Get human-readable date format
     */
    function date_for_human($date): ?string
    {
        if (!$date) {
            return null;
        }
        
        $tenantDate = dt($date);
        
        if ($tenantDate->isToday()) {
            return __('common.dates.today') . ', ' . $tenantDate->toTimeString();
        }
        
        if ($tenantDate->isYesterday()) {
            return __('common.dates.yesterday') . ', ' . $tenantDate->toTimeString();
        }
        
        if ($tenantDate->isTomorrow()) {
            return __('common.dates.tomorrow') . ', ' . $tenantDate->toTimeString();
        }
        
        return $tenantDate->toRelativeString();
    }
} 