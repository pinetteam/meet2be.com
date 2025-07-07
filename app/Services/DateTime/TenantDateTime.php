<?php

namespace App\Services\DateTime;

use Carbon\Carbon;
use JsonSerializable;
use Stringable;

/**
 * Immutable DateTime wrapper that respects tenant settings
 */
class TenantDateTime implements JsonSerializable, Stringable
{
    protected Carbon $carbon;
    protected DateTimeManager $manager;
    protected ?string $cachedTimezone = null;
    
    public function __construct(Carbon $carbon, DateTimeManager $manager)
    {
        $this->carbon = $carbon->copy();
        $this->manager = $manager;
    }
    
    /**
     * Convert to tenant's timezone
     */
    public function inTenantTimezone(): self
    {
        if ($this->cachedTimezone === $this->manager->getTimezone()) {
            return $this;
        }
        
        $carbon = $this->carbon->copy()->setTimezone($this->manager->getTimezone());
        $new = new self($carbon, $this->manager);
        $new->cachedTimezone = $this->manager->getTimezone();
        
        return $new;
    }
    
    /**
     * Format using tenant's date format
     */
    public function toDateString(): string
    {
        return $this->inTenantTimezone()
            ->carbon
            ->format($this->manager->getDateFormat());
    }
    
    /**
     * Format using tenant's time format
     */
    public function toTimeString(): string
    {
        return $this->inTenantTimezone()
            ->carbon
            ->format($this->manager->getTimeFormat());
    }
    
    /**
     * Format using tenant's datetime format
     */
    public function toDateTimeString(): string
    {
        return $this->inTenantTimezone()
            ->carbon
            ->format($this->manager->getDateTimeFormat());
    }
    
    /**
     * Format with custom format
     */
    public function format(string $format): string
    {
        return $this->inTenantTimezone()
            ->carbon
            ->locale($this->manager->getLocale())
            ->format($format);
    }
    
    /**
     * Get relative time (e.g., "2 hours ago")
     */
    public function toRelativeString(): string
    {
        $formatter = new DateTimeFormatter($this->manager);
        return $formatter->relative($this->carbon);
    }
    
    /**
     * Get human-readable difference
     */
    public function diffForHumans(): string
    {
        return $this->carbon
            ->locale($this->manager->getLocale())
            ->diffForHumans();
    }
    
    /**
     * Check if date is today
     */
    public function isToday(): bool
    {
        return $this->inTenantTimezone()
            ->carbon
            ->isToday();
    }
    
    /**
     * Check if date is yesterday
     */
    public function isYesterday(): bool
    {
        return $this->inTenantTimezone()
            ->carbon
            ->isYesterday();
    }
    
    /**
     * Check if date is tomorrow
     */
    public function isTomorrow(): bool
    {
        return $this->inTenantTimezone()
            ->carbon
            ->isTomorrow();
    }
    
    /**
     * Check if date is in the past
     */
    public function isPast(): bool
    {
        return $this->carbon->isPast();
    }
    
    /**
     * Check if date is in the future
     */
    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }
    
    /**
     * Add time to the date
     */
    public function add($unit, $value = 1): self
    {
        $carbon = $this->carbon->copy()->add($unit, $value);
        return new self($carbon, $this->manager);
    }
    
    /**
     * Subtract time from the date
     */
    public function sub($unit, $value = 1): self
    {
        $carbon = $this->carbon->copy()->sub($unit, $value);
        return new self($carbon, $this->manager);
    }
    
    /**
     * Get the start of day
     */
    public function startOfDay(): self
    {
        $carbon = $this->inTenantTimezone()->carbon->copy()->startOfDay();
        return new self($carbon, $this->manager);
    }
    
    /**
     * Get the end of day
     */
    public function endOfDay(): self
    {
        $carbon = $this->inTenantTimezone()->carbon->copy()->endOfDay();
        return new self($carbon, $this->manager);
    }
    
    /**
     * Get the underlying Carbon instance
     */
    public function toCarbon(): Carbon
    {
        return $this->carbon->copy();
    }
    
    /**
     * Convert to UTC Carbon instance
     */
    public function toUTC(): Carbon
    {
        return $this->carbon->copy()->setTimezone('UTC');
    }
    
    /**
     * Get ISO 8601 string
     */
    public function toISO8601String(): string
    {
        return $this->carbon->toIso8601String();
    }
    
    /**
     * Get timestamp
     */
    public function getTimestamp(): int
    {
        return $this->carbon->getTimestamp();
    }
    
    /**
     * Magic method to proxy Carbon methods
     */
    public function __call($method, $parameters)
    {
        $result = $this->carbon->$method(...$parameters);
        
        if ($result instanceof Carbon) {
            return new self($result, $this->manager);
        }
        
        return $result;
    }
    
    /**
     * Convert to string (uses tenant's datetime format)
     */
    public function __toString(): string
    {
        return $this->toDateTimeString();
    }
    
    /**
     * JSON serialization
     */
    public function jsonSerialize(): mixed
    {
        return [
            'datetime' => $this->toDateTimeString(),
            'date' => $this->toDateString(),
            'time' => $this->toTimeString(),
            'relative' => $this->toRelativeString(),
            'iso' => $this->toISO8601String(),
            'timestamp' => $this->getTimestamp(),
        ];
    }
} 