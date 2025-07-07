# DateTime & Timezone Architecture Documentation

## Overview

Our DateTime system provides a unified, tenant-aware date and time handling across the entire application. It automatically respects each tenant's timezone, date format, and time format preferences.

## Architecture Components

1. **DateTimeManager** - Main service for datetime operations
2. **TenantDateTime** - Immutable value object for datetime representation
3. **DateTimeFormatter** - Formatting engine with localization support
4. **HasDateTime Trait** - Model integration for automatic datetime handling
5. **JavaScript SDK** - Frontend datetime handling synchronized with backend

## Backend Usage

### 1. Using Helper Functions

```php
// Get current time in tenant's timezone
$now = tenant_now();

// Parse and format any date
$date = dt('2024-07-07 14:30:00');
echo $date->toDateString();     // "07/07/2024" (based on tenant format)
echo $date->toTimeString();     // "2:30 PM" (based on tenant format)
echo $date->toRelativeString(); // "2 hours ago"

// Quick formatting
echo format_date($user->created_at);     // "07/07/2024"
echo format_time($user->created_at);     // "2:30 PM"
echo format_datetime($user->created_at); // "07/07/2024 2:30 PM"
echo format_relative($user->created_at); // "2 hours ago"
```

### 2. Using in Models

Add the `HasDateTime` trait to any model:

```php
use App\Traits\HasDateTime;

class Event extends Model
{
    use HasDateTime;
    
    // Specify which dates should be auto-converted
    protected array $tenantDates = ['created_at', 'updated_at', 'starts_at', 'ends_at'];
}

// Usage
$event = Event::find($id);

// Automatic conversion via magic properties
echo $event->starts_at;              // TenantDateTime object
echo $event->starts_at_formatted;    // "07/07/2024 2:30 PM"
echo $event->starts_at_date;         // "07/07/2024"
echo $event->starts_at_time;         // "2:30 PM"
echo $event->starts_at_relative;     // "in 2 hours"

// Query scopes
$todayEvents = Event::today('starts_at')->get();
$thisWeekEvents = Event::thisWeek('starts_at')->get();
$dateRangeEvents = Event::dateBetween('starts_at', $start, $end)->get();
```

### 3. Using in Blade Views

```blade
{{-- New directives --}}
@dt($user->created_at)       {{-- Full datetime --}}
@date($user->created_at)     {{-- Date only --}}
@time($user->created_at)     {{-- Time only --}}
@relative($user->created_at) {{-- Relative time --}}

{{-- Legacy directives (still work) --}}
@timezone($user->created_at)
@timezoneDate($user->created_at)
@timezoneTime($user->created_at)
@timezoneRelative($user->created_at)

{{-- Using helper functions --}}
{{ format_date($user->created_at) }}
{{ format_relative($user->created_at) }}
```

### 4. Direct Service Usage

```php
// Get the service instance
$dateTime = app(DateTimeManager::class);

// Or via helper
$dateTime = dt();

// Parse dates
$date = $dateTime->parse('2024-07-07 14:30:00');
$date = $dateTime->parse(Carbon::now());
$date = $dateTime->parse($user->created_at);

// Get specific dates
$today = $dateTime->today();
$yesterday = $dateTime->yesterday();
$tomorrow = $dateTime->tomorrow();

// Format operations
$formatted = $dateTime->format($carbonDate, 'Y-m-d H:i');
$relative = $dateTime->formatRelative($carbonDate);
```

## Frontend Usage

### 1. Basic JavaScript Usage

```javascript
// Format dates
const formatted = formatDateTime('2024-07-07T14:30:00Z');
const dateOnly = formatDate('2024-07-07T14:30:00Z');
const timeOnly = formatTime('2024-07-07T14:30:00Z');
const relative = formatRelative('2024-07-07T14:30:00Z');

// Using DateTime object directly
const dt = window.DateTime;
const isToday = dt.isToday('2024-07-07T14:30:00Z');
const isYesterday = dt.isYesterday('2024-07-07T14:30:00Z');
```

### 2. Alpine.js Integration

```html
<div x-data="{ createdAt: '2024-07-07T14:30:00Z' }">
    <!-- Using magic helpers -->
    <span x-text="$date(createdAt)"></span>
    <span x-text="$time(createdAt)"></span>
    <span x-text="$datetime(createdAt)"></span>
    <span x-text="$relative(createdAt)"></span>
</div>

<!-- In Alpine component -->
<script>
Alpine.data('myComponent', () => ({
    eventDate: '2024-07-07T14:30:00Z',
    
    get formattedDate() {
        return formatDate(this.eventDate);
    },
    
    get relativeTime() {
        return formatRelative(this.eventDate);
    }
}));
</script>
```

## API Responses

When returning dates in API responses, use the formatter's `forApi` method:

```php
// In API Resource
public function toArray($request): array
{
    $formatter = new DateTimeFormatter(app(DateTimeManager::class));
    
    return [
        'id' => $this->id,
        'name' => $this->name,
        'created_at' => $formatter->forApi($this->created_at),
    ];
}

// Response will include:
{
    "created_at": {
        "iso": "2024-07-07T14:30:00+00:00",
        "timestamp": 1720365000,
        "formatted": "07/07/2024 2:30 PM",
        "date": "07/07/2024",
        "time": "2:30 PM",
        "relative": "2 hours ago",
        "timezone": "America/New_York"
    }
}
```

## Date Format Options

The system supports these date formats:
- `Y-m-d` → "2024-07-07"
- `d/m/Y` → "07/07/2024"
- `m/d/Y` → "07/07/2024"
- `d.m.Y` → "07.07.2024"
- `d-m-Y` → "07-07-2024"
- `M j, Y` → "Jul 7, 2024"
- `F j, Y` → "July 7, 2024"
- `j F Y` → "7 July 2024"

## Time Format Options

The system supports these time formats:
- `H:i` → "14:30" (24-hour)
- `H:i:s` → "14:30:45" (24-hour with seconds)
- `g:i A` → "2:30 PM" (12-hour)
- `g:i:s A` → "2:30:45 PM" (12-hour with seconds)
- `h:i A` → "02:30 PM" (12-hour with leading zero)
- `h:i:s A` → "02:30:45 PM" (12-hour with seconds and leading zero)

## Special Formatting

### Date Ranges
```php
$formatter = new DateTimeFormatter(dt());
$range = $formatter->dateRange($start, $end);
// Same day: "July 7, 2024"
// Same month: "7-15 July 2024"
// Different months: "Jul 7 - Aug 15 2024"
// Different years: "07/07/2024 - 07/07/2025"
```

### Duration
```php
$duration = $formatter->duration($start, $end);
// "2 hours, 30 minutes"
// "3 days, 4 hours"
// "1 month, 2 weeks"
```

### Business Hours
```php
$hours = $formatter->businessHours('09:00', '17:00');
// "9:00 AM - 5:00 PM" (based on tenant's time format)
```

## Caching

The system automatically caches tenant datetime settings for 24 hours. To clear the cache:

```php
// Clear for current tenant
dt()->clearCache();

// Clear for specific tenant
$manager = app(DateTimeManager::class);
$manager->setTenant($tenant)->clearCache();
```

### Important: Automatic Cache Clearing

The cache is automatically cleared when timezone, date format, or time format settings are changed in the Settings page. This ensures that all datetime displays across the application immediately reflect the new settings.

If you're updating these settings programmatically, make sure to clear the cache:

```php
// Update tenant settings
$tenant->update([
    'timezone_id' => $newTimezoneId,
    'date_format' => $newDateFormat,
    'time_format' => $newTimeFormat,
]);

// Clear the cache to reflect changes immediately
app(DateTimeManager::class)->clearCache();
```

The frontend will automatically reload after datetime settings are changed to ensure all displayed dates and times use the new format.

## Best Practices

1. **Always use the system for user-facing dates** - Never display raw database timestamps
2. **Store in UTC** - Always store dates in UTC in the database
3. **Use appropriate format** - Use relative times for recent activity, full dates for older items
4. **Be consistent** - Use the same formatting approach throughout your feature
5. **Test with different timezones** - Always test your feature with different tenant timezone settings

## Migration from Old System

If you're updating existing code:

1. Replace `TimezoneService` with `DateTimeManager`
2. Update Blade directives (old ones still work for compatibility)
3. Add `HasDateTime` trait to models that need it
4. Update API responses to use the new formatter

Example migration:
```php
// Old
$timezoneService = app(TimezoneService::class);
$formatted = $timezoneService->formatDateTime($date);

// New
$formatted = dt($date)->toDateTimeString();
// or
$formatted = format_datetime($date);
``` 