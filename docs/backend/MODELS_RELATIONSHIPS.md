# Models & Relationships

## Overview

This document outlines the model structure and relationships in Meet2Be. All models use UUID primary keys and follow Laravel's Eloquent ORM conventions.

## Base Model Structure

### Standard Model Template

```php
namespace App\Models\{Domain};

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TenantAware;
use App\Traits\HasDateTime;

class ModelName extends Model
{
    use HasUuids, SoftDeletes, TenantAware, HasDateTime;
    
    protected $fillable = [];
    protected $hidden = [];
    protected $casts = [];
    protected $attributes = [];
    
    // Relationships
    // Scopes
    // Accessors & Mutators
    // Business Logic Methods
}
```

## Core Models

### User Model

```php
namespace App\Models\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\TenantAware;
use App\Traits\HasDateTime;

class User extends Authenticatable
{
    use HasUuids, TenantAware, HasDateTime;
    
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'phone',
        'timezone',
        'locale',
        'is_active',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }
    
    public function attendingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_attendees')
            ->withTimestamps()
            ->withPivot(['status', 'checked_in_at']);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Business Logic
    public function isActive(): bool
    {
        return $this->is_active && $this->tenant->is_active;
    }
}
```

### Tenant Model

```php
namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasDateTime;

class Tenant extends Model
{
    use HasUuids, SoftDeletes, HasDateTime;
    
    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'owner_id',
        'timezone',
        'locale',
        'date_format',
        'time_format',
        'currency_code',
        'country_code',
        'settings',
        'metadata',
    ];
    
    protected $casts = [
        'settings' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];
    
    protected $attributes = [
        'type' => 'personal',
        'status' => 'active',
        'timezone' => 'UTC',
        'locale' => 'en',
    ];
    
    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    public function venues()
    {
        return $this->hasMany(Venue::class);
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('is_active', true);
    }
    
    // Business Logic
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }
}
```

### Event Model

```php
namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TenantAware;
use App\Traits\HasDateTime;
use App\Traits\Sluggable;

class Event extends Model
{
    use HasUuids, SoftDeletes, TenantAware, HasDateTime, Sluggable;
    
    protected $fillable = [
        'tenant_id',
        'venue_id',
        'title',
        'slug',
        'description',
        'starts_at',
        'ends_at',
        'timezone',
        'status',
        'visibility',
        'max_attendees',
        'settings',
        'metadata',
    ];
    
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'published_at' => 'datetime',
        'settings' => 'array',
        'metadata' => 'array',
        'max_attendees' => 'integer',
        'current_attendees' => 'integer',
    ];
    
    protected $attributes = [
        'status' => 'draft',
        'visibility' => 'public',
        'current_attendees' => 0,
    ];
    
    // Relationships
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
    
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_attendees')
            ->withTimestamps()
            ->withPivot(['status', 'checked_in_at']);
    }
    
    public function organizer()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }
    
    public function scopePast($query)
    {
        return $query->where('ends_at', '<', now());
    }
    
    public function scopeOngoing($query)
    {
        return $query->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }
    
    // Accessors
    public function getIsUpcomingAttribute(): bool
    {
        return $this->starts_at->isFuture();
    }
    
    public function getIsPastAttribute(): bool
    {
        return $this->ends_at->isPast();
    }
    
    public function getIsOngoingAttribute(): bool
    {
        return $this->starts_at->isPast() && $this->ends_at->isFuture();
    }
    
    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->max_attendees - $this->current_attendees);
    }
    
    // Business Logic
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
    
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        
        // Notify attendees
        $this->attendees->each(function ($attendee) {
            // Send cancellation notification
        });
    }
    
    public function hasCapacity(int $seats = 1): bool
    {
        if (!$this->max_attendees) {
            return true; // Unlimited capacity
        }
        
        return $this->available_seats >= $seats;
    }
}
```

### Venue Model

```php
namespace App\Models\Event\Venue;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TenantAware;
use App\Traits\HasDateTime;
use App\Traits\Sluggable;

class Venue extends Model
{
    use HasUuids, SoftDeletes, TenantAware, HasDateTime, Sluggable;
    
    protected $table = 'event_venues';
    
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'type',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country_code',
        'latitude',
        'longitude',
        'virtual_url',
        'virtual_platform',
        'capacity',
        'description',
        'amenities',
        'contact_name',
        'contact_email',
        'contact_phone',
        'is_active',
    ];
    
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'integer',
        'amenities' => 'array',
        'is_active' => 'boolean',
    ];
    
    protected $attributes = [
        'type' => 'physical',
        'is_active' => true,
    ];
    
    // Relationships
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopePhysical($query)
    {
        return $query->where('type', 'physical');
    }
    
    public function scopeVirtual($query)
    {
        return $query->where('type', 'virtual');
    }
    
    // Accessors
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country?->name,
        ]);
        
        return implode(', ', $parts);
    }
    
    public function getIsPhysicalAttribute(): bool
    {
        return $this->type === 'physical';
    }
    
    public function getIsVirtualAttribute(): bool
    {
        return $this->type === 'virtual';
    }
}
```

## System Models

### Country Model

```php
namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Country extends Model
{
    use HasUuids;
    
    protected $table = 'system_countries';
    
    protected $fillable = [
        'code',
        'code3',
        'name',
        'native_name',
        'capital',
        'region',
        'subregion',
        'phone_code',
        'currency_code',
        'flag_emoji',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    // Relationships
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'system_country_language')
            ->withPivot('is_primary');
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
    
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'country_code', 'code');
    }
    
    public function venues()
    {
        return $this->hasMany(Venue::class, 'country_code', 'code');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
```

## Relationship Types

### One-to-One

```php
// User has one profile
public function profile()
{
    return $this->hasOne(UserProfile::class);
}

// Profile belongs to user
public function user()
{
    return $this->belongsTo(User::class);
}
```

### One-to-Many

```php
// Tenant has many users
public function users()
{
    return $this->hasMany(User::class);
}

// User belongs to tenant
public function tenant()
{
    return $this->belongsTo(Tenant::class);
}
```

### Many-to-Many

```php
// Event has many attendees (users)
public function attendees()
{
    return $this->belongsToMany(User::class, 'event_attendees')
        ->withTimestamps()
        ->withPivot(['status', 'checked_in_at']);
}

// User attends many events
public function attendingEvents()
{
    return $this->belongsToMany(Event::class, 'event_attendees')
        ->withTimestamps()
        ->withPivot(['status', 'checked_in_at']);
}
```

### Polymorphic Relations

```php
// Comments can belong to multiple models
class Comment extends Model
{
    public function commentable()
    {
        return $this->morphTo();
    }
}

// Event has comments
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

## Eager Loading

### Preventing N+1 Queries

```php
// Bad - N+1 problem
$events = Event::all();
foreach ($events as $event) {
    echo $event->venue->name; // Additional query for each event
}

// Good - Eager loading
$events = Event::with(['venue', 'organizer', 'attendees'])->get();

// Nested eager loading
$events = Event::with([
    'venue.country',
    'attendees.tenant',
    'organizer' => function ($query) {
        $query->select('id', 'name', 'email');
    }
])->get();
```

### Lazy Eager Loading

```php
$events = Event::all();

// Load relationships later
$events->load(['venue', 'organizer']);

// Conditional loading
$events->loadMissing('attendees');
```

## Query Scopes

### Local Scopes

```php
// Define in model
public function scopePublished($query)
{
    return $query->where('status', 'published');
}

public function scopeOfType($query, $type)
{
    return $query->where('type', $type);
}

// Usage
$publishedEvents = Event::published()->get();
$virtualVenues = Venue::ofType('virtual')->get();
```

### Global Scopes

```php
// Define global scope
class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_active', true);
    }
}

// Apply to model
protected static function booted()
{
    static::addGlobalScope(new ActiveScope);
}

// Bypass global scope
$allVenues = Venue::withoutGlobalScope(ActiveScope::class)->get();
```

## Accessors & Mutators

### Accessors

```php
// Get full name attribute
public function getFullNameAttribute(): string
{
    return "{$this->first_name} {$this->last_name}";
}

// Usage
echo $user->full_name; // John Doe
```

### Mutators

```php
// Set password attribute
public function setPasswordAttribute($value): void
{
    $this->attributes['password'] = bcrypt($value);
}

// Usage
$user->password = 'plain-text-password'; // Automatically hashed
```

### Attribute Casting

```php
protected $casts = [
    'settings' => 'array',
    'is_active' => 'boolean',
    'starts_at' => 'datetime',
    'price' => 'decimal:2',
    'metadata' => AsCollection::class,
];
```

## Model Events

### Available Events

```php
class Event extends Model
{
    protected static function booted()
    {
        // Before creating
        static::creating(function ($event) {
            $event->slug = Str::slug($event->title);
        });
        
        // After created
        static::created(function ($event) {
            Cache::tags(['events'])->flush();
        });
        
        // Before updating
        static::updating(function ($event) {
            if ($event->isDirty('status')) {
                // Status is changing
            }
        });
        
        // After deleted
        static::deleted(function ($event) {
            // Clean up related data
        });
    }
}
```

### Using Observers

```php
// app/Observers/EventObserver.php
class EventObserver
{
    public function creating(Event $event): void
    {
        $event->code = $this->generateUniqueCode();
    }
    
    public function updated(Event $event): void
    {
        if ($event->wasChanged('status')) {
            // Notify attendees of status change
        }
    }
}

// Register in provider
Event::observe(EventObserver::class);
```

## Performance Tips

### Index Optimization

```php
// Add indexes in migration
$table->index('tenant_id');
$table->index(['tenant_id', 'status']);
$table->index(['tenant_id', 'starts_at']);
```

### Select Specific Columns

```php
// Only select needed columns
$users = User::select('id', 'name', 'email')->get();

// With relationships
$events = Event::with(['venue:id,name', 'organizer:id,name,email'])->get();
```

### Chunking Large Datasets

```php
// Process in chunks
Event::chunk(100, function ($events) {
    foreach ($events as $event) {
        // Process event
    }
});

// Using cursor for memory efficiency
foreach (Event::cursor() as $event) {
    // Process one at a time
}
``` 