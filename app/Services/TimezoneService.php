<?php

namespace App\Services;

use App\Models\System\Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TimezoneService
{
    protected ?Timezone $timezone = null;
    protected string $defaultTimezone = 'UTC';
    
    public function __construct()
    {
        $this->loadUserTimezone();
    }
    
    protected function loadUserTimezone(): void
    {
        if (Auth::check() && Auth::user()->tenant) {
            $tenant = Auth::user()->tenant;
            
            if ($tenant->timezone_id) {
                // Cache ile performans optimizasyonu
                $this->timezone = Cache::remember(
                    "tenant_timezone_{$tenant->id}",
                    3600, // 1 saat cache
                    fn() => Timezone::find($tenant->timezone_id)
                );
            }
        }
    }
    
    public function getTimezone(): ?Timezone
    {
        return $this->timezone;
    }
    
    public function getTimezoneName(): string
    {
        return $this->timezone?->name ?? $this->defaultTimezone;
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
    
    public function now(): Carbon
    {
        return Carbon::now($this->getTimezoneName());
    }
    
    public function today(): Carbon
    {
        return Carbon::today($this->getTimezoneName());
    }
    
    public function format(?Carbon $date, string $format = 'd.m.Y H:i'): string
    {
        if (!$date) {
            return '';
        }
        
        return $this->convertToUserTimezone($date)->format($format);
    }
    
    public function formatDate(?Carbon $date): string
    {
        return $this->format($date, 'd.m.Y');
    }
    
    public function formatTime(?Carbon $date): string
    {
        return $this->format($date, 'H:i');
    }
    
    public function formatDateTime(?Carbon $date): string
    {
        return $this->format($date, 'd.m.Y H:i');
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
            return 'Bugün ' . $converted->format('H:i');
        } elseif ($converted->isYesterday()) {
            return 'Dün ' . $converted->format('H:i');
        } elseif ($converted->diffInDays($now) < 7) {
            return $converted->diffForHumans();
        } else {
            return $converted->format('d.m.Y H:i');
        }
    }
    
    public function clearCache(): void
    {
        if (Auth::check() && Auth::user()->tenant) {
            Cache::forget("tenant_timezone_" . Auth::user()->tenant->id);
        }
    }
} 