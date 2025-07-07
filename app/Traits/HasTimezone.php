<?php

namespace App\Traits;

use App\Services\TimezoneService;
use Carbon\Carbon;

trait HasTimezone
{
    protected function getTimezoneService(): TimezoneService
    {
        return app(TimezoneService::class);
    }
    
    // created_at accessor
    public function getCreatedAtLocalAttribute(): ?Carbon
    {
        return $this->getTimezoneService()->convertToUserTimezone($this->created_at);
    }
    
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->getTimezoneService()->formatDateTime($this->created_at);
    }
    
    public function getCreatedAtDateAttribute(): string
    {
        return $this->getTimezoneService()->formatDate($this->created_at);
    }
    
    public function getCreatedAtTimeAttribute(): string
    {
        return $this->getTimezoneService()->formatTime($this->created_at);
    }
    
    public function getCreatedAtRelativeAttribute(): string
    {
        return $this->getTimezoneService()->formatRelative($this->created_at);
    }
    
    // updated_at accessor
    public function getUpdatedAtLocalAttribute(): ?Carbon
    {
        return $this->getTimezoneService()->convertToUserTimezone($this->updated_at);
    }
    
    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->getTimezoneService()->formatDateTime($this->updated_at);
    }
    
    public function getUpdatedAtDateAttribute(): string
    {
        return $this->getTimezoneService()->formatDate($this->updated_at);
    }
    
    public function getUpdatedAtTimeAttribute(): string
    {
        return $this->getTimezoneService()->formatTime($this->updated_at);
    }
    
    public function getUpdatedAtRelativeAttribute(): string
    {
        return $this->getTimezoneService()->formatRelative($this->updated_at);
    }
    
    // Genel metod - herhangi bir Carbon alanı için
    public function formatInTimezone(?Carbon $date, string $format = 'd.m.Y H:i'): string
    {
        return $this->getTimezoneService()->format($date, $format);
    }
    
    public function toUserTimezone(?Carbon $date): ?Carbon
    {
        return $this->getTimezoneService()->convertToUserTimezone($date);
    }
} 