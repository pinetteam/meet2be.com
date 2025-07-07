<?php

namespace App\Services\DateTime;

use Carbon\Carbon;

class DateTimeFormatter
{
    protected DateTimeManager $manager;
    protected array $relativeThresholds = [
        'minute' => 60,           // Up to 60 seconds show "X seconds ago"
        'hour' => 3600,           // Up to 60 minutes show "X minutes ago"
        'day' => 86400,           // Up to 24 hours show "X hours ago"
        'week' => 604800,         // Up to 7 days show "X days ago"
        'month' => 2592000,       // Up to 30 days show "X weeks ago"
        'year' => 31536000,       // Up to 365 days show "X months ago"
    ];
    
    public function __construct(DateTimeManager $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * Format a date using tenant settings
     */
    public function format(Carbon $date, string $format = null): string
    {
        $date = $date->copy()
            ->setTimezone($this->manager->getTimezone())
            ->locale($this->manager->getLocale());
            
        return $date->format($format ?? $this->manager->getDateTimeFormat());
    }
    
    /**
     * Format date only
     */
    public function date(Carbon $date): string
    {
        return $this->format($date, $this->manager->getDateFormat());
    }
    
    /**
     * Format time only
     */
    public function time(Carbon $date): string
    {
        return $this->format($date, $this->manager->getTimeFormat());
    }
    
    /**
     * Format datetime
     */
    public function datetime(Carbon $date): string
    {
        return $this->format($date, $this->manager->getDateTimeFormat());
    }
    
    /**
     * Format relative time with smart display
     */
    public function relative(Carbon $date): string
    {
        $now = Carbon::now();
        $date = $date->copy()->setTimezone($this->manager->getTimezone());
        $diffInSeconds = abs($now->diffInSeconds($date));
        
        // Today/Yesterday/Tomorrow handling
        if ($date->isToday()) {
            return __('common.dates.today') . ', ' . $this->time($date);
        }
        
        if ($date->isYesterday()) {
            return __('common.dates.yesterday') . ', ' . $this->time($date);
        }
        
        if ($date->isTomorrow()) {
            return __('common.dates.tomorrow') . ', ' . $this->time($date);
        }
        
        // Recent dates (within 7 days)
        if ($diffInSeconds < $this->relativeThresholds['week']) {
            return $date->locale($this->manager->getLocale())->diffForHumans();
        }
        
        // This year
        if ($date->year === $now->year) {
            return $this->format($date, $this->getShortDateFormat());
        }
        
        // Default to full date
        return $this->date($date);
    }
    
    /**
     * Get short date format (without year for current year)
     */
    protected function getShortDateFormat(): string
    {
        $format = $this->manager->getDateFormat();
        
        // Remove year from common formats
        $shortFormats = [
            'Y-m-d' => 'M d',
            'd/m/Y' => 'd/m',
            'm/d/Y' => 'm/d',
            'd.m.Y' => 'd.m',
            'd-m-Y' => 'd-m',
            'M j, Y' => 'M j',
            'F j, Y' => 'F j',
            'j F Y' => 'j F',
        ];
        
        return $shortFormats[$format] ?? $format;
    }
    
    /**
     * Format for API responses
     */
    public function forApi(Carbon $date): array
    {
        return [
            'iso' => $date->toIso8601String(),
            'timestamp' => $date->getTimestamp(),
            'formatted' => $this->datetime($date),
            'date' => $this->date($date),
            'time' => $this->time($date),
            'relative' => $this->relative($date),
            'timezone' => $this->manager->getTimezone(),
        ];
    }
    
    /**
     * Format duration between two dates
     */
    public function duration(Carbon $start, Carbon $end): string
    {
        $diff = $start->diff($end);
        $parts = [];
        
        if ($diff->y > 0) {
            $parts[] = __('common.time.years', ['count' => $diff->y]);
        }
        if ($diff->m > 0) {
            $parts[] = __('common.time.months', ['count' => $diff->m]);
        }
        if ($diff->d > 0) {
            $parts[] = __('common.time.days', ['count' => $diff->d]);
        }
        if ($diff->h > 0) {
            $parts[] = __('common.time.hours', ['count' => $diff->h]);
        }
        if ($diff->i > 0) {
            $parts[] = __('common.time.minutes', ['count' => $diff->i]);
        }
        
        return implode(', ', array_slice($parts, 0, 2));
    }
    
    /**
     * Format business hours
     */
    public function businessHours(string $start, string $end): string
    {
        $startTime = Carbon::createFromFormat('H:i', $start);
        $endTime = Carbon::createFromFormat('H:i', $end);
        
        return $this->time($startTime) . ' - ' . $this->time($endTime);
    }
    
    /**
     * Format date range
     */
    public function dateRange(Carbon $start, Carbon $end): string
    {
        if ($start->isSameDay($end)) {
            return $this->date($start);
        }
        
        if ($start->isSameMonth($end) && $start->isSameYear($end)) {
            return $start->day . '-' . $end->day . ' ' . $start->locale($this->manager->getLocale())->monthName . ' ' . $start->year;
        }
        
        if ($start->isSameYear($end)) {
            return $this->format($start, 'd M') . ' - ' . $this->format($end, 'd M Y');
        }
        
        return $this->date($start) . ' - ' . $this->date($end);
    }
    
    /**
     * Get all available formats for UI
     */
    public static function getAvailableFormats(): array
    {
        return [
            'date' => [
                'Y-m-d' => '2024-07-07',
                'd/m/Y' => '07/07/2024',
                'm/d/Y' => '07/07/2024',
                'd.m.Y' => '07.07.2024',
                'd-m-Y' => '07-07-2024',
                'M j, Y' => 'Jul 7, 2024',
                'F j, Y' => 'July 7, 2024',
                'j F Y' => '7 July 2024',
            ],
            'time' => [
                'H:i' => '14:30',
                'H:i:s' => '14:30:45',
                'g:i A' => '2:30 PM',
                'g:i:s A' => '2:30:45 PM',
                'h:i A' => '02:30 PM',
                'h:i:s A' => '02:30:45 PM',
            ],
        ];
    }
} 