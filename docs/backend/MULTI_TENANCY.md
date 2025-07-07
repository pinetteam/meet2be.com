# Multi-Tenancy Architecture

## Overview

Meet2Be implements a **shared database with row-level isolation** multi-tenancy architecture. This approach provides excellent balance between isolation, performance, and maintenance overhead.

## Architecture Decision

### Why Shared Database?

1. **Cost Efficiency**: Single database instance for all tenants
2. **Maintenance**: Single schema to migrate and maintain
3. **Performance**: Efficient resource utilization
4. **Scalability**: Easy to scale horizontally

### Why Row-Level Isolation?

1. **Data Isolation**: Every query automatically filtered by tenant
2. **Security**: Tenant data completely isolated
3. **Flexibility**: Easy to implement cross-tenant features
4. **Backup**: Individual tenant data can be exported

## Implementation Details

### 1. Tenant Model

```php
namespace App\Models\Tenant;

class Tenant extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'owner_id',
        'settings',
        // ... other fields
    ];
    
    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

### 2. TenantAware Trait

The core of our multi-tenancy implementation:

```php
namespace App\Traits;

trait TenantAware
{
    protected static function bootTenantAware()
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
}
```

### 3. TenantService

Manages tenant context throughout the application:

```php
namespace App\Services;

class TenantService
{
    protected static ?string $currentTenantId = null;
    
    public static function setCurrentTenant(?Tenant $tenant): void
    {
        self::$currentTenantId = $tenant?->id;
    }
    
    public static function getCurrentTenantId(): ?string
    {
        if (self::$currentTenantId) {
            return self::$currentTenantId;
        }
        
        if (auth()->check() && auth()->user()->tenant_id) {
            self::$currentTenantId = auth()->user()->tenant_id;
            return self::$currentTenantId;
        }
        
        return null;
    }
    
    public static function validateTenant(string $tenantId): bool
    {
        return Cache::remember("tenant_valid_{$tenantId}", 3600, function () use ($tenantId) {
            $tenant = Tenant::find($tenantId);
            return $tenant && $tenant->is_active;
        });
    }
}
```

### 4. Middleware Implementation

```php
namespace App\Http\Middleware;

class EnsureTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }
        
        $tenantId = TenantService::getCurrentTenantId();
        
        if (!$tenantId) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['tenant' => 'Invalid tenant context.']);
        }
        
        if (!TenantService::validateTenant($tenantId)) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['tenant' => 'Tenant access suspended.']);
        }
        
        $request->merge(['current_tenant_id' => $tenantId]);
        
        return $next($request);
    }
}
```

## Database Schema

### Tenant Table

```php
Schema::create('tenants', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->string('code')->unique();
    $table->enum('type', ['personal', 'team', 'enterprise'])->default('personal');
    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
    $table->uuid('owner_id');
    $table->json('settings')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index('code');
    $table->index('status');
    $table->foreign('owner_id')->references('id')->on('users');
});
```

### Tenant-Aware Tables

All tenant-scoped tables include tenant_id:

```php
Schema::create('events', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('tenant_id');
    $table->string('title');
    // ... other fields
    
    $table->foreign('tenant_id')->references('id')->on('tenants');
    $table->index(['tenant_id', 'starts_at']);
});
```

## Model Configuration

### Using TenantAware Trait

```php
class Event extends Model
{
    use HasUuids, SoftDeletes, TenantAware;
    
    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        // ...
    ];
}
```

### Bypassing Tenant Filter

Sometimes you need to access data across tenants:

```php
// Get all events across all tenants (admin only)
$allEvents = Event::withoutTenantFilter()->get();

// Query specific tenant
$tenantEvents = Event::forTenant($tenantId)->get();
```

## Security Considerations

### 1. Query Isolation

Every query is automatically filtered by tenant:

```php
// This automatically adds WHERE tenant_id = current_tenant_id
$events = Event::where('status', 'published')->get();
```

### 2. Creation Protection

New records automatically get tenant_id:

```php
// tenant_id is automatically set
$event = Event::create([
    'title' => 'New Event',
    'starts_at' => now()->addDays(7),
]);
```

### 3. Relationship Protection

Relationships respect tenant boundaries:

```php
// Only returns venues belonging to current tenant
$venues = $event->venue()->get();
```

## Cross-Tenant Features

### System-Wide Models

Some models are not tenant-scoped:

```php
class Country extends Model
{
    // No TenantAware trait
    use HasUuids;
    
    protected $table = 'system_countries';
}
```

### Admin Access

Super admins can access all tenant data:

```php
class AdminEventController extends Controller
{
    public function index()
    {
        // Bypass tenant filter for admin
        $events = Event::withoutTenantFilter()
            ->with(['tenant', 'venue'])
            ->paginate(20);
            
        return view('admin.events.index', compact('events'));
    }
}
```

## Performance Optimization

### 1. Indexing Strategy

Always index tenant_id with commonly queried fields:

```php
$table->index(['tenant_id', 'status']);
$table->index(['tenant_id', 'created_at']);
$table->index(['tenant_id', 'user_id']);
```

### 2. Query Optimization

Tenant filter is applied at the query builder level:

```sql
-- Automatically generated
SELECT * FROM events 
WHERE tenant_id = '550e8400-e29b-41d4-a716-446655440000' 
AND status = 'published'
ORDER BY starts_at DESC
```

### 3. Caching Strategy

Cache tenant-specific data with tenant ID in key:

```php
$key = "tenant_{$tenantId}_settings";
$settings = Cache::remember($key, 3600, function () use ($tenantId) {
    return Tenant::find($tenantId)->settings;
});
```

## Testing Multi-Tenancy

### Unit Tests

```php
class TenantAwareTest extends TestCase
{
    public function test_model_filters_by_tenant()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();
        
        $user1 = User::factory()->for($tenant1)->create();
        $user2 = User::factory()->for($tenant2)->create();
        
        Event::factory()->count(3)->create(['tenant_id' => $tenant1->id]);
        Event::factory()->count(2)->create(['tenant_id' => $tenant2->id]);
        
        $this->actingAs($user1);
        
        $events = Event::all();
        
        $this->assertCount(3, $events);
        $this->assertTrue($events->every(fn($e) => $e->tenant_id === $tenant1->id));
    }
}
```

### Feature Tests

```php
class TenantIsolationTest extends TestCase
{
    public function test_user_cannot_access_other_tenant_resources()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();
        
        $user = User::factory()->for($tenant1)->create();
        $event = Event::factory()->for($tenant2)->create();
        
        $this->actingAs($user);
        
        $response = $this->get(route('portal.event.show', $event));
        
        $response->assertNotFound();
    }
}
```

## Migration Strategy

### Adding Tenant Support to Existing Table

```php
class AddTenantIdToEventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('tenant_id')->after('id')->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->index(['tenant_id', 'starts_at']);
        });
        
        // Migrate existing data to default tenant
        $defaultTenant = Tenant::where('code', 'default')->first();
        DB::table('events')->update(['tenant_id' => $defaultTenant->id]);
        
        // Make column required
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable(false)->change();
        });
    }
}
```

## Best Practices

### 1. Always Use Traits

```php
// Good
class Event extends Model
{
    use TenantAware;
}

// Bad - manual filtering
class Event extends Model
{
    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }
}
```

### 2. Validate Tenant Access

```php
// In FormRequest
public function authorize()
{
    return $this->route('event')->tenant_id === auth()->user()->tenant_id;
}
```

### 3. Use Policies

```php
class EventPolicy
{
    public function view(User $user, Event $event)
    {
        return $user->tenant_id === $event->tenant_id;
    }
}
```

### 4. Test Isolation

Always test that tenant isolation works:

```php
// Good - tests isolation
public function test_tenant_isolation()
{
    $otherTenantEvent = Event::factory()->create();
    
    $this->actingAs($this->user)
        ->get(route('portal.event.show', $otherTenantEvent))
        ->assertNotFound();
}
```

## Troubleshooting

### Common Issues

1. **Missing tenant_id**: Ensure model uses TenantAware trait
2. **Cross-tenant access**: Check if accidentally using withoutTenantFilter()
3. **Performance issues**: Add composite indexes on tenant_id
4. **Test failures**: Ensure proper tenant context in tests

### Debug Helpers

```php
// Check current tenant
dd(TenantService::getCurrentTenantId());

// Check if query has tenant filter
dd(Event::toSql());
// Should show: select * from `events` where `events`.`tenant_id` = ?

// Temporarily disable filter
Event::withoutTenantFilter()->where('id', $id)->first();
``` 