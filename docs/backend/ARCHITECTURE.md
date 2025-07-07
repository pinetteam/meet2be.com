# Backend Architecture Overview

## Introduction

Meet2Be backend is built on Laravel 12 following enterprise-grade architectural patterns and best practices. The architecture emphasizes scalability, maintainability, and security while providing a robust multi-tenant event management platform.

## Core Architectural Principles

### 1. Domain-Driven Design (DDD)
We organize our codebase around business domains rather than technical layers:

```
app/
├── Models/
│   ├── Event/          # Event domain
│   ├── User/           # User domain
│   ├── Tenant/         # Tenant domain
│   └── System/         # System configurations
├── Services/
│   ├── DateTime/       # DateTime handling service
│   └── TenantService   # Tenant management service
```

### 2. Repository Pattern with Eloquent
While we leverage Eloquent ORM's power, we maintain clean separation of concerns:

```php
// Model handles data structure and relationships
class Event extends Model
{
    use HasUuids, SoftDeletes, TenantAware;
}

// Controller handles HTTP layer
class EventController extends Controller
{
    public function store(StoreEventRequest $request)
    {
        $event = Event::create($request->validated());
        return new EventResource($event);
    }
}
```

### 3. Service Layer Architecture
Complex business logic is encapsulated in service classes:

```php
// Services handle business logic
class EventService
{
    public function createEventWithVenue(array $eventData, array $venueData): Event
    {
        return DB::transaction(function () use ($eventData, $venueData) {
            $venue = Venue::create($venueData);
            $event = Event::create([...$eventData, 'venue_id' => $venue->id]);
            return $event->load('venue');
        });
    }
}
```

## Multi-Tenancy Architecture

### Tenant Isolation Strategy
We implement **shared database with row-level isolation**:

```php
trait TenantAware
{
    protected static function bootTenantAware()
    {
        static::creating(function ($model) {
            if (!$model->tenant_id && TenantService::getCurrentTenantId()) {
                $model->tenant_id = TenantService::getCurrentTenantId();
            }
        });
    }
}
```

### Tenant Context Management
Tenant context is established through middleware:

```php
class EnsureTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = TenantService::getCurrentTenantId();
        
        if (!$tenantId || !TenantService::validateTenant($tenantId)) {
            Auth::logout();
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
```

## Request Lifecycle

### 1. Route Definition
```php
// bootstrap/app.php
Route::middleware(['web', 'auth', 'tenant'])
    ->name('portal.')
    ->prefix('portal')
    ->group(base_path('routes/portal.php'));
```

### 2. Middleware Pipeline
1. **Web Middleware Group**: Session, CSRF, Cookie encryption
2. **Authentication**: Verify user is logged in
3. **Tenant Context**: Establish tenant scope
4. **Route Model Binding**: Auto-resolve UUIDs to models

### 3. Request Validation
```php
class StoreEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'starts_at' => 'required|date|after:now',
            'venue_id' => 'required|uuid|exists:venues,id',
        ];
    }
}
```

### 4. Controller Action
Controllers remain thin, delegating to services:

```php
public function store(StoreEventRequest $request, EventService $service)
{
    $event = $service->create($request->validated());
    return redirect()
        ->route('portal.event.show', $event)
        ->with('success', __('event.created'));
}
```

## Database Architecture

### UUID Primary Keys
All models use UUIDs for primary keys:

```php
Schema::create('events', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('tenant_id');
    $table->uuid('venue_id');
    // ...
});
```

### Soft Deletes
Critical data is never hard deleted:

```php
class Event extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
}
```

### Indexing Strategy
- Primary keys are automatically indexed
- Foreign keys get indexes for JOIN performance
- Composite indexes for common query patterns

```php
$table->index(['tenant_id', 'starts_at']);
$table->index(['tenant_id', 'status']);
```

## Caching Architecture

### Cache Layers

1. **Configuration Cache**
   ```bash
   php artisan config:cache
   ```

2. **Route Cache**
   ```bash
   php artisan route:cache
   ```

3. **Query Cache**
   ```php
   $countries = Cache::remember('countries', 3600, function () {
       return Country::where('is_active', true)->get();
   });
   ```

4. **Tenant Settings Cache**
   ```php
   Cache::remember("tenant_settings_{$tenant->id}", 86400, function () {
       return $tenant->settings;
   });
   ```

## Error Handling

### Global Exception Handler
```php
// app/Exceptions/Handler.php
public function render($request, Throwable $exception)
{
    if ($request->expectsJson()) {
        return $this->renderJsonException($exception);
    }
    
    return parent::render($request, $exception);
}
```

### Business Logic Exceptions
```php
namespace App\Exceptions\Business;

class EventCapacityExceededException extends BusinessException
{
    protected $message = 'Event capacity has been exceeded';
    protected $code = 'EVENT_CAPACITY_EXCEEDED';
}
```

## Security Architecture

### Authentication
- Session-based authentication for web
- Sanctum for API authentication (future)
- Two-factor authentication support (future)

### Authorization
- Role-based access control (RBAC)
- Policy-based authorization for resources
- Middleware-based route protection

### Data Protection
- All inputs sanitized through FormRequests
- SQL injection prevention via Eloquent
- XSS protection through Blade escaping
- CSRF protection on all POST requests

## Performance Optimization

### Query Optimization
1. **Eager Loading**
   ```php
   Event::with(['venue', 'organizer', 'attendees'])->get();
   ```

2. **Query Scopes**
   ```php
   Event::upcoming()->withVenue()->forTenant($tenantId)->get();
   ```

3. **Database Indexing**
   - Indexes on foreign keys
   - Composite indexes for filtering
   - Full-text indexes for search

### Response Optimization
1. **API Resources**
   ```php
   return EventResource::collection($events);
   ```

2. **Pagination**
   ```php
   $events = Event::paginate(20);
   ```

3. **Cache Headers**
   ```php
   return response()
       ->json($data)
       ->header('Cache-Control', 'max-age=3600');
   ```

## Scalability Considerations

### Horizontal Scaling
- Stateless application design
- Redis for session storage
- Shared nothing architecture

### Database Scaling
- Read replicas for reporting
- Connection pooling
- Query optimization

### Queue Processing
- Redis queue for background jobs
- Horizon for queue monitoring
- Job batching for bulk operations

## Monitoring & Observability

### Application Monitoring
- Laravel Telescope for local debugging
- Exception tracking with error reporting service
- Performance monitoring with APM tools

### Health Checks
```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::has('health_check') ? 'working' : 'not working',
    ]);
});
```

## Development Workflow

### Local Development
```bash
php artisan serve
npm run dev
```

### Testing
```bash
php artisan test
php artisan test --parallel
```

### Code Quality
```bash
./vendor/bin/phpstan analyse
./vendor/bin/phpcs
```

## Deployment Architecture

### Environment Management
- `.env` for local development
- Environment variables for production
- Secrets management for sensitive data

### Zero-Downtime Deployment
1. Deploy new code
2. Run migrations
3. Clear caches
4. Reload PHP-FPM

### Rollback Strategy
- Database migrations with down() methods
- Git tags for release versions
- Blue-green deployment support 