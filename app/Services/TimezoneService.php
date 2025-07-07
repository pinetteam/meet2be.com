<?php

namespace App\Services;

use App\Models\System\Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimezoneService
{
    protected ?Timezone $timezone = null;
    protected string $defaultTimezone = 'UTC';
    protected string $defaultDateFormat = 'Y-m-d';
    protected string $defaultTimeFormat = 'H:i';
    
    public function __construct()
    {
        // Constructor'da yükleme yapmıyoruz
    }
    
    protected function loadUserTimezone(): void
    {
        // Her çağrıda güncel timezone'u al
        $this->timezone = null;
        
        if (Auth::check() && Auth::user()->tenant) {
            $tenant = Auth::user()->tenant;
            
            if ($tenant->timezone_id) {
                // Cache kullanmadan direkt timezone'u al
                $this->timezone = $tenant->timezone;
            }
        }
    }
    
    protected function getTenant()
    {
        return Auth::check() ? Auth::user()->tenant : null;
    }
    
    protected function getDateFormat(): string
    {
        $tenant = $this->getTenant();
        return $tenant ? $tenant->date_format : $this->defaultDateFormat;
    }
    
    protected function getTimeFormat(): string
    {
        $tenant = $this->getTenant();
        return $tenant ? $tenant->time_format : $this->defaultTimeFormat;
    }
    
    public function getTimezone(): ?Timezone
    {
        // Her seferinde güncel timezone'u yükle
        $this->loadUserTimezone();
        return $this->timezone;
    }
    
    public function getTimezoneName(): string
    {
        return $this->getTimezone()?->name ?? $this->defaultTimezone;
    }
    
    public function convertToUserTimezone(?Carbon $date): ?Carbon
    {
        if (!$date) {
            return null;
        }
        
        return $date->copy()->setTimezone($this->getTimezoneName());
    }
    
    public function convertFromUserTimezone(?Carbon $date): ?Carbon
    {
        if (!$date) {
            return null;
        }
        
        return $date->copy()->setTimezone($this->defaultTimezone);
    }
    
    /**
     * Parse a date string in user's timezone and convert to UTC
     * 
     * @param string|null $dateString
     * @param string $format
     * @return Carbon|null
     */
    public function parseFromUserTimezone(?string $dateString, string $format = 'Y-m-d H:i:s'): ?Carbon
    {
        if (!$dateString) {
            return null;
        }
        
        // Parse the date in user's timezone
        $date = Carbon::createFromFormat($format, $dateString, $this->getTimezoneName());
        
        // Convert to UTC
        return $date ? $date->setTimezone('UTC') : null;
    }
    
    /**
     * Parse a date input (Y-m-d) in user's timezone and convert to UTC
     * 
     * @param string|null $dateString
     * @return Carbon|null
     */
    public function parseDateFromUserTimezone(?string $dateString): ?Carbon
    {
        return $this->parseFromUserTimezone($dateString, 'Y-m-d');
    }
    
    /**
     * Parse a datetime input (Y-m-d H:i) in user's timezone and convert to UTC
     * 
     * @param string|null $dateTimeString
     * @return Carbon|null
     */
    public function parseDateTimeFromUserTimezone(?string $dateTimeString): ?Carbon
    {
        return $this->parseFromUserTimezone($dateTimeString, 'Y-m-d H:i');
    }
    
    public function now(): Carbon
    {
        return Carbon::now($this->getTimezoneName());
    }
    
    public function today(): Carbon
    {
        return Carbon::today($this->getTimezoneName());
    }
    
    public function format(?Carbon $date, string $format = null): string
    {
        if (!$date) {
            return '';
        }
        
        // Eğer format verilmemişse, tenant'ın formatını kullan
        if ($format === null) {
            $format = $this->getDateFormat() . ' ' . $this->getTimeFormat();
        }
        
        return $this->convertToUserTimezone($date)->format($format);
    }
    
    public function formatDate(?Carbon $date): string
    {
        if (!$date) {
            return '';
        }
        
        return $this->convertToUserTimezone($date)->format($this->getDateFormat());
    }
    
    public function formatTime(?Carbon $date): string
    {
        if (!$date) {
            return '';
        }
        
        return $this->convertToUserTimezone($date)->format($this->getTimeFormat());
    }
    
    public function formatDateTime(?Carbon $date): string
    {
        if (!$date) {
            return '';
        }
        
        $dateFormat = $this->getDateFormat();
        $timeFormat = $this->getTimeFormat();
        
        return $this->convertToUserTimezone($date)->format($dateFormat . ' ' . $timeFormat);
    }
    
    public function formatRelative(?Carbon $date): string
    {
        if (!$date) {
            return '';
        }
        
        $converted = $this->convertToUserTimezone($date);
        $now = $this->now();
        
        // Yakın tarihler için göreceli format
        if ($converted->isToday()) {
            return __('common.dates.today') . ' ' . $converted->format($this->getTimeFormat());
        } elseif ($converted->isYesterday()) {
            return __('common.dates.yesterday') . ' ' . $converted->format($this->getTimeFormat());
        } elseif ($converted->diffInDays($now) < 7) {
            return $converted->diffForHumans();
        } else {
            return $this->formatDateTime($date);
        }
    }
    
    public function clearCache(): void
    {
        // Cache kullanmıyoruz artık
        $this->timezone = null;
    }
    
    /**
     * Force reload timezone from database
     */
    public function refresh(): void
    {
        $this->timezone = null;
        $this->loadUserTimezone();
    }
} 