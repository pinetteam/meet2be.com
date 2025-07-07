<?php

namespace App\Traits;

use App\Services\DateTime\DateTimeManager;
use App\Services\DateTime\TenantDateTime;
use Carbon\Carbon;

trait HasDateTime
{
    /**
     * Date attributes that should be automatically converted to TenantDateTime
     */
    protected array $tenantDates = ['created_at', 'updated_at'];
    
    /**
     * Get DateTime manager instance
     */
    protected function getTenantDateTimeManager(): DateTimeManager
    {
        return app(DateTimeManager::class);
    }
    
    /**
     * Convert a date to TenantDateTime
     */
    public function toTenantDateTime($date): ?TenantDateTime
    {
        if (!$date) {
            return null;
        }
        
        return $this->getTenantDateTimeManager()->parse($date);
    }
    
    /**
     * Get a date attribute as TenantDateTime
     */
    public function getTenantDate(string $attribute): ?TenantDateTime
    {
        $date = $this->getAttribute($attribute);
        
        if (!$date) {
            return null;
        }
        
        return $this->toTenantDateTime($date);
    }
    
    /**
     * Format a date attribute
     */
    public function formatDate(string $attribute, ?string $format = null): ?string
    {
        $date = $this->getTenantDate($attribute);
        
        if (!$date) {
            return null;
        }
        
        return $format ? $date->format($format) : $date->toDateString();
    }
    
    /**
     * Format a time attribute
     */
    public function formatTime(string $attribute, ?string $format = null): ?string
    {
        $date = $this->getTenantDate($attribute);
        
        if (!$date) {
            return null;
        }
        
        return $format ? $date->format($format) : $date->toTimeString();
    }
    
    /**
     * Format a datetime attribute
     */
    public function formatDateTime(string $attribute, ?string $format = null): ?string
    {
        $date = $this->getTenantDate($attribute);
        
        if (!$date) {
            return null;
        }
        
        return $format ? $date->format($format) : $date->toDateTimeString();
    }
    
    /**
     * Get relative time for an attribute
     */
    public function formatRelative(string $attribute): ?string
    {
        $date = $this->getTenantDate($attribute);
        
        if (!$date) {
            return null;
        }
        
        return $date->toRelativeString();
    }
    
    /**
     * Dynamically retrieve tenant date attributes
     */
    public function __get($key)
    {
        $value = parent::__get($key);
        
        // Check if this is a tenant date attribute
        if (in_array($key, $this->tenantDates) && $value instanceof Carbon) {
            return $this->toTenantDateTime($value);
        }
        
        // Check for _formatted suffix
        if (str_ends_with($key, '_formatted')) {
            $attribute = substr($key, 0, -10);
            if ($this->hasDateAttribute($attribute)) {
                return $this->formatDateTime($attribute);
            }
        }
        
        // Check for _date suffix
        if (str_ends_with($key, '_date')) {
            $attribute = substr($key, 0, -5);
            if ($this->hasDateAttribute($attribute)) {
                return $this->formatDate($attribute);
            }
        }
        
        // Check for _time suffix
        if (str_ends_with($key, '_time')) {
            $attribute = substr($key, 0, -5);
            if ($this->hasDateAttribute($attribute)) {
                return $this->formatTime($attribute);
            }
        }
        
        // Check for _relative suffix
        if (str_ends_with($key, '_relative')) {
            $attribute = substr($key, 0, -9);
            if ($this->hasDateAttribute($attribute)) {
                return $this->formatRelative($attribute);
            }
        }
        
        return $value;
    }
    
    /**
     * Check if date attribute exists
     */
    protected function hasDateAttribute(string $attribute): bool
    {
        // Check if attribute exists in model
        if (!isset($this->attributes[$attribute])) {
            return false;
        }
        
        // Check if it's in casts array as date/datetime
        if (isset($this->casts[$attribute])) {
            $cast = $this->casts[$attribute];
            return in_array($cast, ['date', 'datetime', 'custom_datetime', 'immutable_date', 'immutable_datetime']);
        }
        
        // Check if it's in default date attributes
        return in_array($attribute, ['created_at', 'updated_at', 'deleted_at']);
    }
    
    /**
     * Scope to filter by date range
     */
    public function scopeDateBetween($query, string $column, $start, $end)
    {
        $manager = $this->getTenantDateTimeManager();
        
        if ($start) {
            $startDate = $manager->parse($start)->toUTC();
            $query->where($column, '>=', $startDate);
        }
        
        if ($end) {
            $endDate = $manager->parse($end)->toUTC();
            $query->where($column, '<=', $endDate);
        }
        
        return $query;
    }
    
    /**
     * Scope to filter by today
     */
    public function scopeToday($query, string $column = 'created_at')
    {
        $manager = $this->getTenantDateTimeManager();
        $today = $manager->today();
        
        return $query->whereBetween($column, [
            $today->startOfDay()->toUTC(),
            $today->endOfDay()->toUTC(),
        ]);
    }
    
    /**
     * Scope to filter by yesterday
     */
    public function scopeYesterday($query, string $column = 'created_at')
    {
        $manager = $this->getTenantDateTimeManager();
        $yesterday = $manager->yesterday();
        
        return $query->whereBetween($column, [
            $yesterday->startOfDay()->toUTC(),
            $yesterday->endOfDay()->toUTC(),
        ]);
    }
    
    /**
     * Scope to filter by this week
     */
    public function scopeThisWeek($query, string $column = 'created_at')
    {
        $manager = $this->getTenantDateTimeManager();
        $now = $manager->now();
        
        return $query->whereBetween($column, [
            $now->startOfWeek()->toUTC(),
            $now->endOfWeek()->toUTC(),
        ]);
    }
    
    /**
     * Scope to filter by this month
     */
    public function scopeThisMonth($query, string $column = 'created_at')
    {
        $manager = $this->getTenantDateTimeManager();
        $now = $manager->now();
        
        return $query->whereBetween($column, [
            $now->startOfMonth()->toUTC(),
            $now->endOfMonth()->toUTC(),
        ]);
    }
} 