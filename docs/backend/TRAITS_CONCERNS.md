# Traits & Concerns

## Overview

Traits in Meet2Be provide reusable functionality across models and classes. They follow the Single Responsibility Principle and promote DRY (Don't Repeat Yourself) code.

## Core Traits

### TenantAware

Automatically filters queries and assigns tenant context to models.

```php
namespace App\Traits;

use App\Services\TenantService;
use Illuminate\Database\Eloquent\Builder;

trait TenantAware
{
    protected static function bootTenantAware(): void
    {
        // Auto-assign tenant_id on creation
        static::creating(function ($model) {
            if (!$model->tenant_id && TenantService::getCurrentTenantId()) {
                $model->tenant_id = TenantService::getCurrentTenantId();
            }
        });
    }
    
    public function newQuery(): Builder
    {
        $builder = parent::newQuery();
        
        if ($this->shouldApplyTenantFilter()) {
            $tenantId = TenantService::getCurrentTenantId();
            if ($tenantId) {
                $builder->where($this->getTable() . '.tenant_id', $tenantId);
            }
        }
        
        return $builder;
    }
    
    protected function shouldApplyTenantFilter(): bool
    {
        // Skip filter for specific scenarios
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return false;
        }
        
        return true;
    }
    
    public function scopeWithoutTenantFilter(Builder $query): Builder
    {
        return $query->withoutGlobalScope('tenant');
    }
    
    public function scopeForTenant(Builder $query, string $tenantId): Builder
    {
        return $query->withoutTenantFilter()
            ->where($this->getTable() . '.tenant_id', $tenantId);
    }
}
```

**Usage:**
```php
class Event extends Model
{
    use TenantAware;
}

// Automatically filtered by current tenant
$events = Event::all();

// Access all tenants (admin only)
$allEvents = Event::withoutTenantFilter()->get();

// Specific tenant
$tenantEvents = Event::forTenant($tenantId)->get();
```

### HasDateTime

Provides timezone-aware datetime formatting and manipulation.

```php
namespace App\Traits;

use App\Services\DateTime\DateTimeManager;
use App\Services\DateTime\TenantDateTime;

trait HasDateTime
{
    protected ?DateTimeManager $dateTimeManager = null;
    
    protected function getTenantDateTimeManager(): DateTimeManager
    {
        if (!$this->dateTimeManager) {
            $this->dateTimeManager = app(DateTimeManager::class);
        }
        
        return $this->dateTimeManager;
    }
    
    public function hasDateAttribute(string $attribute): bool
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            return false;
        }
        
        return in_array($attribute, $this->getDates()) || 
               $this->hasCast($attribute, ['date', 'datetime', 'immutable_date', 'immutable_datetime']);
    }
    
    public function getDateAttribute($key): ?TenantDateTime
    {
        $value = $this->getAttribute($key);
        
        if (is_null($value)) {
            return null;
        }
        
        return $this->getTenantDateTimeManager()->parse($value);
    }
    
    public function __get($key)
    {
        // Magic datetime properties
        if (str_ends_with($key, '_formatted')) {
            $attribute = substr($key, 0, -10);
            if ($this->hasDateAttribute($attribute)) {
                return $this->getDateAttribute($attribute)?->formatted();
            }
        }
        
        if (str_ends_with($key, '_date')) {
            $attribute = substr($key, 0, -5);
            if ($this->hasDateAttribute($attribute)) {
                return $this->getDateAttribute($attribute)?->date();
            }
        }
        
        if (str_ends_with($key, '_time')) {
            $attribute = substr($key, 0, -5);
            if ($this->hasDateAttribute($attribute)) {
                return $this->getDateAttribute($attribute)?->time();
            }
        }
        
        if (str_ends_with($key, '_relative')) {
            $attribute = substr($key, 0, -9);
            if ($this->hasDateAttribute($attribute)) {
                return $this->getDateAttribute($attribute)?->relative();
            }
        }
        
        return parent::__get($key);
    }
    
    // Query scopes for date filtering
    public function scopeToday($query, string $column = 'created_at')
    {
        $today = $this->getTenantDateTimeManager()->now();
        
        return $query->whereDate($column, '>=', $today->startOfDay()->toDatabase())
                     ->whereDate($column, '<=', $today->endOfDay()->toDatabase());
    }
    
    public function scopeYesterday($query, string $column = 'created_at')
    {
        $yesterday = $this->getTenantDateTimeManager()->now()->subDay();
        
        return $query->whereDate($column, '>=', $yesterday->startOfDay()->toDatabase())
                     ->whereDate($column, '<=', $yesterday->endOfDay()->toDatabase());
    }
    
    public function scopeThisWeek($query, string $column = 'created_at')
    {
        $now = $this->getTenantDateTimeManager()->now();
        
        return $query->whereDate($column, '>=', $now->startOfWeek()->toDatabase())
                     ->whereDate($column, '<=', $now->endOfWeek()->toDatabase());
    }
    
    public function scopeThisMonth($query, string $column = 'created_at')
    {
        $now = $this->getTenantDateTimeManager()->now();
        
        return $query->whereDate($column, '>=', $now->startOfMonth()->toDatabase())
                     ->whereDate($column, '<=', $now->endOfMonth()->toDatabase());
    }
}
```

**Usage:**
```php
class Event extends Model
{
    use HasDateTime;
    
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}

$event = Event::first();
echo $event->starts_at_formatted;  // "Jan 15, 2025 3:30 PM"
echo $event->starts_at_date;       // "Jan 15, 2025"
echo $event->starts_at_time;       // "3:30 PM"
echo $event->starts_at_relative;   // "in 2 days"

// Query scopes
$todaysEvents = Event::today('starts_at')->get();
$thisWeeksOrders = Order::thisWeek()->get();
```

### HasTimezone

Manages timezone conversions for models.

```php
namespace App\Traits;

use Carbon\Carbon;

trait HasTimezone
{
    public function getTimezone(): string
    {
        // Check if model has timezone attribute
        if ($this->timezone) {
            return $this->timezone;
        }
        
        // Check tenant timezone
        if (method_exists($this, 'tenant') && $this->tenant) {
            return $this->tenant->timezone;
        }
        
        // Check current user's tenant
        if (auth()->check() && auth()->user()->tenant) {
            return auth()->user()->tenant->timezone;
        }
        
        // Default to UTC
        return config('app.timezone', 'UTC');
    }
    
    public function convertToTimezone($datetime, ?string $timezone = null): Carbon
    {
        $timezone = $timezone ?? $this->getTimezone();
        
        return Carbon::parse($datetime)->setTimezone($timezone);
    }
    
    public function convertToUTC($datetime): Carbon
    {
        return Carbon::parse($datetime, $this->getTimezone())->setTimezone('UTC');
    }
}
```

## Model Traits

### Searchable

Provides full-text search capabilities.

```php
namespace App\Traits;

trait Searchable
{
    public function scopeSearch($query, string $term)
    {
        if (empty($term)) {
            return $query;
        }
        
        $searchable = $this->searchable ?? [];
        
        return $query->where(function ($query) use ($term, $searchable) {
            foreach ($searchable as $column) {
                $query->orWhere($column, 'LIKE', "%{$term}%");
            }
        });
    }
    
    public function scopeSearchIn($query, array $columns, string $term)
    {
        if (empty($term)) {
            return $query;
        }
        
        return $query->where(function ($query) use ($columns, $term) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$term}%");
            }
        });
    }
}
```

**Usage:**
```php
class User extends Model
{
    use Searchable;
    
    protected $searchable = ['name', 'email', 'phone'];
}

// Search in all searchable columns
$users = User::search('john')->get();

// Search in specific columns
$users = User::searchIn(['name', 'email'], 'john')->get();
```

### Sluggable

Automatically generates URL-friendly slugs.

```php
namespace App\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    protected static function bootSluggable(): void
    {
        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = $model->generateSlug();
            }
        });
        
        static::updating(function ($model) {
            if ($model->isDirty($model->getSlugSourceColumn())) {
                $model->slug = $model->generateSlug();
            }
        });
    }
    
    protected function generateSlug(): string
    {
        $source = $this->getSlugSourceColumn();
        $slug = Str::slug($this->$source);
        
        // Ensure uniqueness
        $count = static::where('slug', 'LIKE', "{$slug}%")
            ->where('id', '!=', $this->id)
            ->count();
            
        return $count > 0 ? "{$slug}-" . ($count + 1) : $slug;
    }
    
    protected function getSlugSourceColumn(): string
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'title';
    }
}
```

### Auditable

Tracks changes to model attributes.

```php
namespace App\Traits;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            $model->audit('created');
        });
        
        static::updated(function ($model) {
            if ($model->isDirty()) {
                $model->audit('updated', $model->getDirty());
            }
        });
        
        static::deleted(function ($model) {
            $model->audit('deleted');
        });
    }
    
    protected function audit(string $action, array $changes = []): void
    {
        Audit::create([
            'auditable_type' => static::class,
            'auditable_id' => $this->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
    
    public function audits()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
```

## Controller Traits

### RespondsWithJson

Standardizes JSON responses.

```php
namespace App\Traits;

trait RespondsWithJson
{
    protected function success($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    
    protected function error(string $message = 'Error', int $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
    
    protected function notFound(string $message = 'Resource not found')
    {
        return $this->error($message, 404);
    }
    
    protected function unauthorized(string $message = 'Unauthorized')
    {
        return $this->error($message, 401);
    }
    
    protected function forbidden(string $message = 'Forbidden')
    {
        return $this->error($message, 403);
    }
    
    protected function validationError($errors)
    {
        return $this->error('Validation failed', 422, $errors);
    }
}
```

### HandlesFileUploads

Manages file upload operations.

```php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HandlesFileUploads
{
    protected function uploadFile(UploadedFile $file, string $directory, ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        
        $filename = $this->generateFilename($file);
        
        return $file->storeAs($directory, $filename, $disk);
    }
    
    protected function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }
    
    protected function deleteFile(string $path, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');
        
        return Storage::disk($disk)->delete($path);
    }
    
    protected function uploadImage(UploadedFile $file, string $directory, array $sizes = []): array
    {
        $paths = [
            'original' => $this->uploadFile($file, $directory)
        ];
        
        foreach ($sizes as $size => $dimensions) {
            $resized = Image::make($file)
                ->resize($dimensions['width'], $dimensions['height'])
                ->encode();
                
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = "{$directory}/{$size}/{$filename}";
            
            Storage::put($path, $resized);
            $paths[$size] = $path;
        }
        
        return $paths;
    }
}
```

## Request Traits

### HasTenantValidation

Adds tenant-aware validation rules.

```php
namespace App\Traits;

trait HasTenantValidation
{
    protected function tenantUnique(string $table, string $column = 'NULL'): string
    {
        $column = $column === 'NULL' ? $this->input('column') : $column;
        $tenantId = TenantService::getCurrentTenantId();
        
        return "unique:{$table},{$column},NULL,id,tenant_id,{$tenantId}";
    }
    
    protected function tenantExists(string $table): string
    {
        $tenantId = TenantService::getCurrentTenantId();
        
        return "exists:{$table},id,tenant_id,{$tenantId}";
    }
    
    protected function prependTenantRules(array $rules): array
    {
        $tenantRules = [];
        
        foreach ($rules as $field => $rule) {
            if (is_string($rule) && str_contains($rule, 'tenant_unique')) {
                $rule = str_replace('tenant_unique', $this->tenantUnique(...), $rule);
            }
            
            if (is_string($rule) && str_contains($rule, 'tenant_exists')) {
                $rule = str_replace('tenant_exists', $this->tenantExists(...), $rule);
            }
            
            $tenantRules[$field] = $rule;
        }
        
        return $tenantRules;
    }
}
```

## Testing Traits

### RefreshesDatabase

Enhanced database refresh with tenant support.

```php
namespace App\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;

trait RefreshesDatabase
{
    use BaseRefreshDatabase {
        refreshDatabase as baseRefreshDatabase;
    }
    
    public function refreshDatabase(): void
    {
        $this->baseRefreshDatabase();
        
        // Seed system data
        $this->artisan('db:seed', ['--class' => 'SystemSeeder']);
        
        // Create test tenant
        $this->tenant = Tenant::factory()->create();
        TenantService::setCurrentTenant($this->tenant);
    }
}
```

### InteractsWithTenant

Provides tenant helpers for tests.

```php
namespace App\Traits;

trait InteractsWithTenant
{
    protected function actingAsTenant(Tenant $tenant): self
    {
        TenantService::setCurrentTenant($tenant);
        
        return $this;
    }
    
    protected function actingAsUser(User $user = null): self
    {
        $user = $user ?? User::factory()->create(['tenant_id' => $this->tenant->id]);
        
        $this->actingAs($user);
        TenantService::setCurrentTenant($user->tenant);
        
        return $this;
    }
    
    protected function withoutTenant(): self
    {
        TenantService::setCurrentTenant(null);
        
        return $this;
    }
}
```

## Best Practices

### 1. Single Responsibility

Each trait should have one clear purpose:

```php
// Good - Focused traits
trait HasUuid { }
trait SoftDeletes { }
trait Searchable { }

// Bad - Kitchen sink trait
trait ModelHelpers {
    // Too many unrelated methods
}
```

### 2. Avoid Property Conflicts

Use unique property names:

```php
trait TimestampFormats
{
    // Prefix properties to avoid conflicts
    protected $timestampFormatsDateFormat = 'Y-m-d';
    protected $timestampFormatsTimeFormat = 'H:i:s';
}
```

### 3. Document Requirements

Clear documentation for trait usage:

```php
/**
 * Provides automatic slug generation for models.
 * 
 * Requirements:
 * - Model must have 'slug' column
 * - Model should have 'title' or 'name' column (or define $slugSource)
 * 
 * @property string $slugSource Column to generate slug from
 */
trait Sluggable
{
    // Implementation
}
```

### 4. Use Boot Methods

Initialize trait behavior properly:

```php
trait Auditable
{
    protected static function bootAuditable(): void
    {
        // Boot logic here
    }
    
    public function initializeAuditable(): void
    {
        // Instance initialization if needed
    }
}
```

### 5. Provide Escape Hatches

Allow disabling trait behavior when needed:

```php
trait SoftDeletes
{
    protected $forceDeleting = false;
    
    public function forceDelete()
    {
        $this->forceDeleting = true;
        return $this->delete();
    }
}
```

## Common Patterns

### Configuration Traits

```php
trait Configurable
{
    protected array $config = [];
    
    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }
    
    public function getConfig(string $key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }
}
```

### Event Traits

```php
trait DispatchesEvents
{
    protected function dispatchModelEvent(string $event, array $payload = []): void
    {
        $class = static::class;
        $eventClass = "App\\Events\\{$class}{$event}";
        
        if (class_exists($eventClass)) {
            event(new $eventClass($this, $payload));
        }
    }
}
```

### Validation Traits

```php
trait ValidatesData
{
    protected function validate(array $data, array $rules): array
    {
        return validator($data, $rules)->validate();
    }
    
    protected function validateWith(string $requestClass, array $data): array
    {
        $request = new $requestClass();
        return $this->validate($data, $request->rules());
    }
}
``` 