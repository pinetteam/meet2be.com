<?php

namespace App\Traits;

use App\Services\DateTime\DateTimeManager;
use Carbon\Carbon;

trait HasTimezone
{
    protected function getDateTimeManager(): DateTimeManager
    {
        return app(DateTimeManager::class);
    }
    
    /**
     * Get current time in UTC (for database storage)
     */
    public function getCurrentTime(): Carbon
    {
        return Carbon::now();
    }
    
    /**
     * Get current time in tenant's timezone (for display)
     */
    public function getCurrentTimeInTenantTimezone(): Carbon
    {
        $timezone = $this->tenant?->timezone?->name ?? config('app.timezone');
        return Carbon::now($timezone);
    }
    
    /**
     * Convert a date from tenant timezone to UTC (for database storage)
     */
    public function toUTC(?Carbon $date): ?Carbon
    {
        if (!$date) {
            return null;
        }
        
        return $date->copy()->setTimezone('UTC');
    }
    
    // created_at accessor
    public function getCreatedAtLocalAttribute(): ?Carbon
    {
        return $this->created_at ? $this->getDateTimeManager()->parse($this->created_at)->inTenantTimezone()->toCarbon() : null;
    }
    
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at ? $this->getDateTimeManager()->parse($this->created_at)->toDateTimeString() : '';
    }
    
    public function getCreatedAtDateAttribute(): string
    {
        return $this->created_at ? $this->getDateTimeManager()->parse($this->created_at)->toDateString() : '';
    }
    
    public function getCreatedAtTimeAttribute(): string
    {
        return $this->created_at ? $this->getDateTimeManager()->parse($this->created_at)->toTimeString() : '';
    }
    
    public function getCreatedAtRelativeAttribute(): string
    {
        return $this->created_at ? $this->getDateTimeManager()->parse($this->created_at)->toRelativeString() : '';
    }
    
    // updated_at accessor
    public function getUpdatedAtLocalAttribute(): ?Carbon
    {
        return $this->updated_at ? $this->getDateTimeManager()->parse($this->updated_at)->inTenantTimezone()->toCarbon() : null;
    }
    
    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at ? $this->getDateTimeManager()->parse($this->updated_at)->toDateTimeString() : '';
    }
    
    public function getUpdatedAtDateAttribute(): string
    {
        return $this->updated_at ? $this->getDateTimeManager()->parse($this->updated_at)->toDateString() : '';
    }
    
    public function getUpdatedAtTimeAttribute(): string
    {
        return $this->updated_at ? $this->getDateTimeManager()->parse($this->updated_at)->toTimeString() : '';
    }
    
    public function getUpdatedAtRelativeAttribute(): string
    {
        return $this->updated_at ? $this->getDateTimeManager()->parse($this->updated_at)->toRelativeString() : '';
    }
    
    // Genel metod - herhangi bir Carbon alanı için
    public function formatInTimezone($date, $format = null): string
    {
        return $this->getDateTimeManager()->format($date, $format);
    }
    
    public function toUserTimezone($date): ?Carbon
    {
        return $date ? $this->getDateTimeManager()->parse($date)->inTenantTimezone()->toCarbon() : null;
    }

    public function convertToTimezone($date, $timezone = null)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $timezone = $timezone ?? $this->getDateTimeManager()->getTimezone();
        
        return $date->setTimezone($timezone);
    }

    public function convertFromTimezone($date, $timezone = null)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $timezone = $timezone ?? $this->getDateTimeManager()->getTimezone();
        
        return $date->setTimezone('UTC');
    }
} 