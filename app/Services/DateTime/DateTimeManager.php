<?php

namespace App\Services\DateTime;

use App\Models\Tenant\Tenant;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DateTimeManager
{
    protected ?Tenant $tenant = null;
    protected ?User $user = null;
    protected string $timezone = 'UTC';
    protected string $dateFormat = 'Y-m-d';
    protected string $timeFormat = 'H:i:s';
    protected string $locale = 'en';
    
    public function __construct()
    {
        $this->initializeFromContext();
    }
    
    protected function initializeFromContext(): void
    {
        if (auth()->check()) {
            $this->user = auth()->user();
            $this->tenant = $this->user->tenant;
            
            if ($this->tenant) {
                $this->setFromTenant($this->tenant);
            }
        }
    }
    
    public function setTenant(Tenant $tenant): self
    {
        $this->tenant = $tenant;
        $this->setFromTenant($tenant);
        return $this;
    }
    
    protected function setFromTenant(Tenant $tenant): void
    {
        $settings = $this->getCachedTenantSettings($tenant);
        
        $this->timezone = $settings['timezone'];
        $this->dateFormat = $settings['date_format'];
        $this->timeFormat = $settings['time_format'];
        $this->locale = $settings['locale'];
    }
    
    protected function getCachedTenantSettings(Tenant $tenant): array
    {
        return Cache::remember(
            "tenant_datetime_settings_{$tenant->id}",
            now()->addHours(24),
            fn() => [
                'timezone' => $tenant->timezone?->name ?? config('app.timezone'),
                'date_format' => $tenant->date_format ?? 'Y-m-d',
                'time_format' => $tenant->time_format ?? 'H:i:s',
                'locale' => $tenant->language?->iso_639_1 ?? app()->getLocale(),
            ]
        );
    }
    
    public function parse($datetime): TenantDateTime
    {
        if ($datetime instanceof TenantDateTime) {
            return $datetime;
        }
        
        if ($datetime instanceof Carbon) {
            $carbon = $datetime;
        } elseif (is_string($datetime)) {
            $carbon = Carbon::parse($datetime);
        } elseif ($datetime instanceof \DateTimeInterface) {
            $carbon = Carbon::instance($datetime);
        } else {
            $carbon = Carbon::now();
        }
        
        return new TenantDateTime($carbon, $this);
    }
    
    public function now(): TenantDateTime
    {
        return $this->parse(Carbon::now());
    }
    
    public function today(): TenantDateTime
    {
        return $this->parse(Carbon::today());
    }
    
    public function tomorrow(): TenantDateTime
    {
        return $this->parse(Carbon::tomorrow());
    }
    
    public function yesterday(): TenantDateTime
    {
        return $this->parse(Carbon::yesterday());
    }
    
    public function getTimezone(): string
    {
        return $this->timezone;
    }
    
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }
    
    public function getTimeFormat(): string
    {
        return $this->timeFormat;
    }
    
    public function getDateTimeFormat(): string
    {
        return $this->dateFormat . ' ' . $this->timeFormat;
    }
    
    public function getLocale(): string
    {
        return $this->locale;
    }
    
    public function clearCache(): void
    {
        if ($this->tenant) {
            Cache::forget("tenant_datetime_settings_{$this->tenant->id}");
        }
    }
    
    public function format(Carbon $date, string $format = null): string
    {
        $formatter = new DateTimeFormatter($this);
        return $formatter->format($date, $format);
    }
    
    public function formatRelative(Carbon $date): string
    {
        $formatter = new DateTimeFormatter($this);
        return $formatter->relative($date);
    }
} 