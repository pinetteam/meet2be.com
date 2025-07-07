# DateTime & Timezone Management

## Overview

Meet2Be implements a comprehensive DateTime management system that handles timezone conversions, locale-aware formatting, and tenant-specific date/time preferences. The system ensures consistent datetime handling across the entire application.

## Architecture

### Core Components

1. **DateTimeManager** - Singleton service managing datetime operations
2. **TenantDateTime** - Immutable value object for datetime representation
3. **DateTimeFormatter** - Handles all formatting with locale support
4. **HasDateTime Trait** - Model integration for automatic formatting

## DateTimeManager Service

### Purpose
Central service for all datetime operations, cached for performance.

```php
namespace App\Services\DateTime;

class DateTimeManager
{
    protected array $tenantCache = [];
    
    public function parse($datetime, ?string $tenantId = null): TenantDateTime
    {
        $tenantId ??= TenantService::getCurrentTenantId();
        $timezone = $this->getTenantTimezone($tenantId);
        
        if ($datetime instanceof TenantDateTime) {
            return $datetime;
        }
        
        $carbon = match (true) {
            $datetime instanceof CarbonInterface => $datetime,
            is_string($datetime) => Carbon::parse($datetime),
            $datetime instanceof \DateTimeInterface => Carbon::instance($datetime),
            default => Carbon::now()
        };
        
        return new TenantDateTime($carbon, $timezone, $tenantId);
    }
}
```

### Key Features

1. **Timezone Conversion**: Automatic conversion between UTC and tenant timezone
2. **Caching**: 24-hour cache for tenant settings
3. **Type Safety**: Always returns TenantDateTime objects
4. **Flexibility**: Accepts various datetime input formats

## TenantDateTime Value Object

### Purpose
Immutable datetime representation with tenant context.

```php
class TenantDateTime
{
    public function __construct(
        protected CarbonInterface $utc,
        protected string $timezone,
        protected ?string $tenantId
    ) {
        $this->utc = $utc->copy()->setTimezone('UTC');
        $this->local = $this->utc->copy()->setTimezone($this->timezone);
    }
    
    public function format(string $format): string
    {
        return $this->local->format($format);
    }
    
    public function toDatabase(): string
    {
        return $this->utc->toDateTimeString();
    }
}
```

### Key Methods

```php
// Formatting
$datetime->format('Y-m-d H:i:s');     // Custom format
$datetime->toDateString();            // Date only
$datetime->toTimeString();            // Time only
$datetime->toDateTimeString();        // Full datetime
$datetime->toRelative();              // "2 hours ago"

// Timezone operations
$datetime->inTimezone('Europe/London');
$datetime->toUtc();
$datetime->toLocal();

// Comparisons
$datetime->isPast();
$datetime->isFuture();
$datetime->isToday();
$datetime->isTomorrow();

// Modifications (returns new instance)
$datetime->addDays(7);
$datetime->subHours(2);
$datetime->startOfDay();
$datetime->endOfMonth();
```

## DateTimeFormatter

### Purpose
Handles all formatting with full localization support.

```php
class DateTimeFormatter
{
    protected array $formats = [
        'date' => [
            'en' => 'M j, Y',
            'tr' => 'd.m.Y',
        ],
        'time' => [
            'en' => 'g:i A',
            'tr' => 'H:i',
        ],
        'datetime' => [
            'en' => 'M j, Y g:i A',
            'tr' => 'd.m.Y H:i',
        ],
    ];
    
    public function date(CarbonInterface $date, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $format = $this->getFormat('date', $locale);
        return $date->format($format);
    }
}
```

### Locale-Aware Formatting

```php
// English: Jan 15, 2025
// Turkish: 15.01.2025
$formatter->date($datetime);

// English: 3:30 PM
// Turkish: 15:30
$formatter->time($datetime);

// English: 2 hours ago
// Turkish: 2 saat önce
$formatter->relative($datetime);
```

## Model Integration

### HasDateTime Trait

```php
trait HasDateTime
{
    public function getDateAttribute($key): ?TenantDateTime
    {
        $value = $this->getAttribute($key);
        
        if (is_null($value)) {
            return null;
        }
        
        return $this->getTenantDateTimeManager()->parse($value);
    }
    
    // Magic properties
    public function __get($key)
    {
        // created_at_formatted => "Jan 15, 2025 3:30 PM"
        if (str_ends_with($key, '_formatted')) {
            $attribute = substr($key, 0, -10);
            return $this->getDateAttribute($attribute)?->formatted();
        }
        
        // created_at_date => "Jan 15, 2025"
        if (str_ends_with($key, '_date')) {
            $attribute = substr($key, 0, -5);
            return $this->getDateAttribute($attribute)?->date();
        }
        
        // created_at_time => "3:30 PM"
        if (str_ends_with($key, '_time')) {
            $attribute = substr($key, 0, -5);
            return $this->getDateAttribute($attribute)?->time();
        }
        
        // created_at_relative => "2 hours ago"
        if (str_ends_with($key, '_relative')) {
            $attribute = substr($key, 0, -9);
            return $this->getDateAttribute($attribute)?->relative();
        }
        
        return parent::__get($key);
    }
}
```

### Usage in Models

```php
class Event extends Model
{
    use HasDateTime;
    
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}

// Usage
$event = Event::find($id);

echo $event->starts_at_formatted;  // "Jan 15, 2025 3:30 PM"
echo $event->starts_at_date;       // "Jan 15, 2025"
echo $event->starts_at_time;       // "3:30 PM"
echo $event->starts_at_relative;   // "in 2 days"
```

## Blade Directives

### Available Directives

```php
// Full datetime with formatting
@dt($event->starts_at)              // "Jan 15, 2025 3:30 PM"

// Date only
@date($event->starts_at)            // "Jan 15, 2025"

// Time only
@time($event->starts_at)            // "3:30 PM"

// Relative time
@relative($event->starts_at)        // "in 2 days"

// Custom format
@dt($event->starts_at, 'Y-m-d')     // "2025-01-15"
```

### Implementation

```php
// In AppServiceProvider
Blade::directive('dt', function ($expression) {
    return "<?php echo dt($expression); ?>";
});

Blade::directive('date', function ($expression) {
    return "<?php echo dt($expression)?->date(); ?>";
});

Blade::directive('time', function ($expression) {
    return "<?php echo dt($expression)?->time(); ?>";
});

Blade::directive('relative', function ($expression) {
    return "<?php echo dt($expression)?->relative(); ?>";
});
```

## Helper Functions

### Global Helpers

```php
// Parse any datetime to TenantDateTime
function dt($datetime = null, ?string $tenantId = null): ?TenantDateTime
{
    if (is_null($datetime)) {
        return null;
    }
    
    return app(DateTimeManager::class)->parse($datetime, $tenantId);
}

// Get current time in tenant timezone
function tenant_now(?string $tenantId = null): TenantDateTime
{
    return app(DateTimeManager::class)->now($tenantId);
}

// Format date
function format_date($datetime, string $format = null): string
{
    return dt($datetime)->format($format ?? 'date');
}

// Format time
function format_time($datetime, string $format = null): string
{
    return dt($datetime)->format($format ?? 'time');
}

// Format datetime
function format_datetime($datetime, string $format = null): string
{
    return dt($datetime)->format($format ?? 'datetime');
}
```

## Query Scopes

### Date-based Scopes

```php
trait HasDateTime
{
    public function scopeToday($query, string $column = 'created_at')
    {
        $today = tenant_now();
        return $query->whereDate($column, '>=', $today->startOfDay()->toDatabase())
                     ->whereDate($column, '<=', $today->endOfDay()->toDatabase());
    }
    
    public function scopeYesterday($query, string $column = 'created_at')
    {
        $yesterday = tenant_now()->subDay();
        return $query->whereDate($column, '>=', $yesterday->startOfDay()->toDatabase())
                     ->whereDate($column, '<=', $yesterday->endOfDay()->toDatabase());
    }
    
    public function scopeThisWeek($query, string $column = 'created_at')
    {
        $now = tenant_now();
        return $query->whereDate($column, '>=', $now->startOfWeek()->toDatabase())
                     ->whereDate($column, '<=', $now->endOfWeek()->toDatabase());
    }
    
    public function scopeThisMonth($query, string $column = 'created_at')
    {
        $now = tenant_now();
        return $query->whereDate($column, '>=', $now->startOfMonth()->toDatabase())
                     ->whereDate($column, '<=', $now->endOfMonth()->toDatabase());
    }
}
```

### Usage

```php
// Get today's events
$todaysEvents = Event::today('starts_at')->get();

// Get this week's orders
$weeklyOrders = Order::thisWeek()->get();

// Get last month's revenue
$lastMonthRevenue = Payment::lastMonth()->sum('amount');
```

## JavaScript Integration

### DateTime Service

```javascript
// resources/js/services/datetime.js
class DateTimeService {
    constructor() {
        this.timezone = window.app?.timezone || 'UTC';
        this.locale = window.app?.locale || 'en';
        this.dateFormat = window.app?.dateFormat || 'M j, Y';
        this.timeFormat = window.app?.timeFormat || 'g:i A';
    }
    
    parse(datetime) {
        return dayjs(datetime).tz(this.timezone);
    }
    
    format(datetime, format = null) {
        const parsed = this.parse(datetime);
        return parsed.format(format || this.dateTimeFormat);
    }
    
    relative(datetime) {
        return this.parse(datetime).fromNow();
    }
    
    toLocal(utcDatetime) {
        return dayjs.utc(utcDatetime).tz(this.timezone);
    }
    
    toUtc(localDatetime) {
        return dayjs.tz(localDatetime, this.timezone).utc();
    }
}

window.DateTime = new DateTimeService();
```

### Alpine.js Integration

```javascript
Alpine.data('dateTimeInput', () => ({
    localValue: '',
    utcValue: '',
    
    init() {
        if (this.utcValue) {
            this.localValue = window.DateTime.toLocal(this.utcValue).format();
        }
    },
    
    updateUtc() {
        if (this.localValue) {
            this.utcValue = window.DateTime.toUtc(this.localValue).format();
        }
    }
}));
```

## Form Handling

### DateTime Input Component

```blade
{{-- resources/views/components/datetime-input.blade.php --}}
<div x-data="dateTimeInput" x-init="utcValue = '{{ $value }}'">
    <input 
        type="datetime-local"
        x-model="localValue"
        @change="updateUtc"
        {{ $attributes->merge(['class' => 'form-input']) }}
    >
    <input 
        type="hidden" 
        name="{{ $name }}" 
        x-model="utcValue"
    >
</div>
```

### Usage

```blade
<x-datetime-input 
    name="starts_at" 
    :value="$event->starts_at?->toDatabase()"
    class="w-full"
/>
```

## Database Storage

### Best Practices

1. **Always store in UTC**
   ```php
   $table->timestamp('starts_at');  // Stored in UTC
   ```

2. **Use timestamp columns**
   ```php
   // Good
   $table->timestamp('published_at')->nullable();
   
   // Avoid
   $table->dateTime('published_at');  // No timezone info
   ```

3. **Convert on save**
   ```php
   protected function asDateTime($value)
   {
       return parent::asDateTime($value)->setTimezone('UTC');
   }
   ```

## Testing DateTime

### Unit Tests

```php
class DateTimeTest extends TestCase
{
    public function test_datetime_converts_to_tenant_timezone()
    {
        $tenant = Tenant::factory()->create(['timezone' => 'America/New_York']);
        $user = User::factory()->for($tenant)->create();
        
        $this->actingAs($user);
        
        $utcTime = '2025-01-15 20:00:00';  // 8 PM UTC
        $datetime = dt($utcTime);
        
        $this->assertEquals('2025-01-15 15:00:00', $datetime->format('Y-m-d H:i:s'));  // 3 PM EST
    }
    
    public function test_datetime_formats_with_locale()
    {
        app()->setLocale('tr');
        
        $datetime = dt('2025-01-15 15:30:00');
        
        $this->assertEquals('15.01.2025', $datetime->date());
        $this->assertEquals('15:30', $datetime->time());
    }
}
```

### Feature Tests

```php
class EventDateTimeTest extends TestCase
{
    public function test_event_displays_in_user_timezone()
    {
        $tenant = Tenant::factory()->create(['timezone' => 'Europe/Istanbul']);
        $user = User::factory()->for($tenant)->create();
        $event = Event::factory()->create([
            'tenant_id' => $tenant->id,
            'starts_at' => '2025-01-15 10:00:00',  // UTC
        ]);
        
        $this->actingAs($user);
        
        $response = $this->get(route('portal.event.show', $event));
        
        $response->assertSee('15.01.2025 13:00');  // Istanbul time
    }
}
```

## Performance Optimization

### Caching Strategy

```php
// Cache tenant timezone for 24 hours
protected function getTenantTimezone(string $tenantId): string
{
    return Cache::remember(
        "tenant_timezone_{$tenantId}", 
        86400, 
        fn() => Tenant::find($tenantId)?->timezone ?? 'UTC'
    );
}
```

### Query Optimization

```php
// Efficient date filtering
Event::whereDate('starts_at', '>=', $startDate->toDatabase())
     ->whereDate('starts_at', '<=', $endDate->toDatabase())
     ->orderBy('starts_at')
     ->get();
```

## Common Patterns

### Business Hours Check

```php
public function isWithinBusinessHours(TenantDateTime $datetime): bool
{
    $hour = $datetime->hour;
    $dayOfWeek = $datetime->dayOfWeek;
    
    // Monday-Friday, 9 AM - 5 PM
    return $dayOfWeek >= 1 && $dayOfWeek <= 5 && $hour >= 9 && $hour < 17;
}
```

### Recurring Events

```php
public function getNextOccurrence(TenantDateTime $from): TenantDateTime
{
    return match($this->recurrence_type) {
        'daily' => $from->addDay(),
        'weekly' => $from->addWeek(),
        'monthly' => $from->addMonth(),
        'yearly' => $from->addYear(),
        default => $from
    };
}
```

### Date Ranges

```php
public function scopeBetweenDates($query, $start, $end, $column = 'created_at')
{
    $startDt = dt($start)->startOfDay();
    $endDt = dt($end)->endOfDay();
    
    return $query->whereBetween($column, [
        $startDt->toDatabase(),
        $endDt->toDatabase()
    ]);
}
```

## Troubleshooting

### Common Issues

1. **Wrong timezone displayed**
   - Check tenant timezone setting
   - Verify DateTimeManager is being used
   - Check if UTC is stored in database

2. **Daylight Saving Time issues**
   - Use timezone names, not offsets
   - Let Carbon handle DST automatically

3. **Formatting issues**
   - Check locale is set correctly
   - Verify format string is valid
   - Use predefined formats when possible

### Debug Helpers

```php
// Check current tenant timezone
dd(app(DateTimeManager::class)->getTenantTimezone());

// Verify datetime conversion
$dt = dt('2025-01-15 10:00:00');
dd([
    'input' => '2025-01-15 10:00:00',
    'utc' => $dt->toUtc()->toDateTimeString(),
    'local' => $dt->toLocal()->toDateTimeString(),
    'timezone' => $dt->getTimezone(),
]);
``` 