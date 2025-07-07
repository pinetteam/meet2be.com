# Performance Optimization

## Overview

Performance is critical for user experience and scalability. This document outlines strategies and best practices for optimizing Meet2Be's backend performance.

## Database Optimization

### Query Optimization

#### N+1 Query Prevention

```php
// ❌ Bad - N+1 queries
$events = Event::all();
foreach ($events as $event) {
    echo $event->venue->name; // Additional query for each event
}

// ✅ Good - Eager loading
$events = Event::with(['venue', 'organizer', 'attendees'])->get();

// ✅ Better - Select only needed columns
$events = Event::with([
    'venue:id,name,address',
    'organizer:id,name,email'
])->select('id', 'title', 'venue_id', 'organizer_id', 'starts_at')->get();
```

#### Efficient Aggregations

```php
// ❌ Bad - Loading all records
$totalRevenue = Payment::all()->sum('amount');

// ✅ Good - Database aggregation
$totalRevenue = Payment::sum('amount');

// ✅ Better - With conditions
$monthlyRevenue = Payment::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->sum('amount');
```

#### Query Chunking

```php
// Process large datasets efficiently
Event::chunk(100, function ($events) {
    foreach ($events as $event) {
        // Process event
    }
});

// For better memory efficiency
Event::cursor()->each(function ($event) {
    // Process one event at a time
});
```

### Indexing Strategy

```php
// Migration with proper indexes
Schema::create('events', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('tenant_id');
    $table->uuid('venue_id');
    $table->string('status');
    $table->timestamp('starts_at');
    
    // Single column indexes
    $table->index('tenant_id');
    $table->index('venue_id');
    $table->index('status');
    $table->index('starts_at');
    
    // Composite indexes for common queries
    $table->index(['tenant_id', 'status']);
    $table->index(['tenant_id', 'starts_at']);
    $table->index(['tenant_id', 'venue_id', 'starts_at']);
});
```

### Database Connection Pooling

```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST'),
    'port' => env('DB_PORT'),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        PDO::ATTR_PERSISTENT => true, // Persistent connections
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
],
```

## Caching Strategies

### Query Result Caching

```php
class EventService
{
    public function getUpcomingEvents(string $tenantId): Collection
    {
        return Cache::remember("tenant_{$tenantId}_upcoming_events", 3600, function () use ($tenantId) {
            return Event::with(['venue', 'organizer'])
                ->where('tenant_id', $tenantId)
                ->where('starts_at', '>', now())
                ->where('status', 'published')
                ->orderBy('starts_at')
                ->limit(10)
                ->get();
        });
    }
    
    public function clearEventCache(string $tenantId): void
    {
        Cache::forget("tenant_{$tenantId}_upcoming_events");
        Cache::tags(["tenant_{$tenantId}", 'events'])->flush();
    }
}
```

### Model Caching

```php
trait CacheableModel
{
    public function getCacheKey(): string
    {
        return sprintf('%s_%s', $this->getTable(), $this->getKey());
    }
    
    public function getCached(): ?self
    {
        return Cache::remember($this->getCacheKey(), 3600, function () {
            return $this->fresh();
        });
    }
    
    public function clearCache(): void
    {
        Cache::forget($this->getCacheKey());
    }
    
    protected static function bootCacheableModel(): void
    {
        static::saved(function ($model) {
            $model->clearCache();
        });
        
        static::deleted(function ($model) {
            $model->clearCache();
        });
    }
}
```

### View Caching

```php
// In Blade views
@cache('event_' . $event->id, 3600)
    <div class="event-card">
        <h3>{{ $event->title }}</h3>
        <p>{{ $event->description }}</p>
        <span>{{ $event->starts_at->format('M d, Y') }}</span>
    </div>
@endcache

// Clear view cache
Cache::tags(['views', 'events'])->flush();
```

## Request Optimization

### Response Compression

```php
// app/Http/Middleware/CompressResponse.php
class CompressResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        if ($response instanceof Response && 
            $request->header('Accept-Encoding') && 
            str_contains($request->header('Accept-Encoding'), 'gzip')) {
            
            $buffer = $response->getContent();
            $response->setContent(gzencode($buffer, 9));
            $response->headers->add([
                'Content-Encoding' => 'gzip',
                'Vary' => 'Accept-Encoding',
                'Content-Length' => strlen($response->getContent()),
            ]);
        }
        
        return $response;
    }
}
```

### API Response Optimization

```php
class EventResource extends JsonResource
{
    public function toArray($request): array
    {
        // Only include requested fields
        $fields = explode(',', $request->get('fields', ''));
        
        $data = [
            'id' => $this->id,
            'title' => $this->title,
        ];
        
        if (empty($fields) || in_array('description', $fields)) {
            $data['description'] = $this->description;
        }
        
        if (empty($fields) || in_array('venue', $fields)) {
            $data['venue'] = new VenueResource($this->whenLoaded('venue'));
        }
        
        return $data;
    }
}
```

## Queue Optimization

### Job Batching

```php
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class EmailService
{
    public function sendBulkEmails(Collection $users, string $template): void
    {
        $jobs = $users->map(function ($user) use ($template) {
            return new SendEmailJob($user, $template);
        });
        
        Bus::batch($jobs)
            ->then(function (Batch $batch) {
                // All jobs completed successfully
                Log::info("Batch {$batch->id} completed");
            })
            ->catch(function (Batch $batch, Throwable $e) {
                // First batch job failure detected
                Log::error("Batch {$batch->id} failed: " . $e->getMessage());
            })
            ->finally(function (Batch $batch) {
                // The batch has finished executing
                Cache::forget("batch_{$batch->id}_status");
            })
            ->dispatch();
    }
}
```

### Queue Configuration

```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],
    
    'redis-bulk' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'bulk',
        'retry_after' => 300,
        'block_for' => null,
    ],
],

// Different queues for different priorities
'priorities' => [
    'high' => 'emails,notifications',
    'default' => 'default',
    'low' => 'bulk,reports',
],
```

## Memory Optimization

### Lazy Collections

```php
// Memory efficient for large datasets
Event::cursor()
    ->filter(function ($event) {
        return $event->isUpcoming();
    })
    ->map(function ($event) {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'date' => $event->starts_at->toDateString(),
        ];
    })
    ->chunk(100)
    ->each(function ($chunk) {
        // Process chunk
    });
```

### Resource Cleanup

```php
class LargeDataProcessor
{
    public function process(): void
    {
        $startMemory = memory_get_usage();
        
        DB::connection()->disableQueryLog();
        
        try {
            $this->processLargeDataset();
        } finally {
            // Clear any circular references
            gc_collect_cycles();
            
            // Log memory usage
            $endMemory = memory_get_usage();
            Log::info('Memory used: ' . (($endMemory - $startMemory) / 1024 / 1024) . ' MB');
        }
    }
}
```

## Application Optimization

### Service Provider Optimization

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    // Lazy load service providers
    $this->app->singleton(ExpensiveService::class, function ($app) {
        return new ExpensiveService();
    });
    
    // Only load in specific environments
    if ($this->app->environment('local')) {
        $this->app->register(TelescopeServiceProvider::class);
    }
}
```

### Configuration Caching

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## Frontend Asset Optimization

### Vite Configuration

```js
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'axios'],
                },
            },
        },
        cssCodeSplit: true,
        sourcemap: false,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
            },
        },
    },
});
```

## Monitoring & Profiling

### Query Monitoring

```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    if (config('app.debug')) {
        DB::listen(function ($query) {
            if ($query->time > 100) { // Queries taking more than 100ms
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                ]);
            }
        });
    }
}
```

### Performance Metrics

```php
class PerformanceMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = (microtime(true) - $start) * 1000;
        
        // Add performance headers
        $response->headers->set('X-Response-Time', $duration . 'ms');
        
        // Log slow requests
        if ($duration > 1000) { // More than 1 second
            Log::warning('Slow request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration' => $duration . 'ms',
                'user_id' => $request->user()?->id,
            ]);
        }
        
        return $response;
    }
}
```

## Performance Best Practices

1. **Database**
   - Use eager loading to prevent N+1 queries
   - Add indexes for frequently queried columns
   - Use database-level aggregations
   - Implement query result caching

2. **Caching**
   - Cache expensive computations
   - Use Redis for session and cache storage
   - Implement cache warming strategies
   - Clear cache selectively, not globally

3. **Queues**
   - Offload heavy tasks to queues
   - Use job batching for bulk operations
   - Configure queue priorities
   - Monitor queue performance

4. **Code**
   - Avoid loading unnecessary data
   - Use lazy collections for large datasets
   - Implement pagination
   - Profile and optimize bottlenecks

5. **Infrastructure**
   - Use CDN for static assets
   - Enable OpCache for PHP
   - Configure Redis properly
   - Use horizontal scaling 