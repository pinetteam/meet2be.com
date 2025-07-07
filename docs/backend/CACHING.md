# Caching Strategy

## Overview

Meet2Be implements a comprehensive caching strategy using Redis to improve performance, reduce database load, and enhance user experience.

## Cache Architecture

### Cache Layers

1. **Application Cache**: General purpose caching
2. **Query Cache**: Database query results
3. **Object Cache**: Model instances
4. **View Cache**: Rendered view fragments
5. **Session Cache**: User sessions
6. **Route Cache**: Compiled routes

### Cache Configuration

```php
// config/cache.php
return [
    'default' => env('CACHE_DRIVER', 'redis'),
    
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],
        
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
    ],
    
    'prefix' => env('CACHE_PREFIX', 'meet2be_cache_'),
];

// config/database.php
'redis' => [
    'cache' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_CACHE_DB', '1'),
    ],
],
```

## Caching Patterns

### Basic Caching

```php
// Simple caching
$value = Cache::remember('key', 3600, function () {
    return expensive_operation();
});

// Forever caching
Cache::forever('settings', $settings);

// Conditional caching
$events = Cache::when($useCache, function () {
    return Cache::remember('events', 3600, fn() => Event::all());
}, function () {
    return Event::all();
});
```

### Tagged Caching

```php
// Store with tags
Cache::tags(['events', 'tenant_123'])->put('upcoming_events', $events, 3600);

// Flush by tag
Cache::tags(['tenant_123'])->flush(); // Clear all tenant cache
Cache::tags(['events'])->flush();     // Clear all event cache
```

### Atomic Operations

```php
// Atomic increment/decrement
$views = Cache::increment('event_123_views');
$remaining = Cache::decrement('event_123_seats');

// Lock for atomic operations
$lock = Cache::lock('process_payment_123', 10);

if ($lock->get()) {
    try {
        // Process payment
    } finally {
        $lock->release();
    }
}
```

## Model Caching

### Query Result Caching

```php
trait CachesQueries
{
    public function scopeCached($query, int $minutes = 60)
    {
        $key = $this->getCacheKey($query);
        
        return Cache::remember($key, $minutes * 60, function () use ($query) {
            return $query->get();
        });
    }
    
    protected function getCacheKey($query): string
    {
        return sprintf(
            '%s_%s_%s',
            $this->getTable(),
            md5($query->toSql()),
            md5(serialize($query->getBindings()))
        );
    }
}

// Usage
$events = Event::upcoming()->cached(120)->get();
```

### Model Instance Caching

```php
class Event extends Model
{
    public function getCached(): self
    {
        return Cache::remember($this->getCacheKey(), 3600, fn() => $this->fresh());
    }
    
    public function getCacheKey(): string
    {
        return "event_{$this->id}";
    }
    
    public function clearCache(): void
    {
        Cache::forget($this->getCacheKey());
        Cache::tags(['events', "tenant_{$this->tenant_id}"])->flush();
    }
    
    protected static function booted(): void
    {
        static::saved(fn($event) => $event->clearCache());
        static::deleted(fn($event) => $event->clearCache());
    }
}
```

## Service Layer Caching

### Tenant Settings Cache

```php
class TenantSettingsService
{
    protected array $cache = [];
    
    public function get(string $key, $default = null)
    {
        $tenantId = TenantService::getCurrentTenantId();
        $cacheKey = "tenant_{$tenantId}_setting_{$key}";
        
        // In-memory cache
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }
        
        // Redis cache
        return $this->cache[$cacheKey] = Cache::remember($cacheKey, 86400, function () use ($key, $default) {
            $setting = Setting::where('tenant_id', TenantService::getCurrentTenantId())
                ->where('key', $key)
                ->first();
                
            return $setting ? $setting->value : $default;
        });
    }
    
    public function set(string $key, $value): void
    {
        $tenantId = TenantService::getCurrentTenantId();
        
        Setting::updateOrCreate(
            ['tenant_id' => $tenantId, 'key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("tenant_{$tenantId}_setting_{$key}");
        unset($this->cache["tenant_{$tenantId}_setting_{$key}"]);
    }
}
```

### Complex Data Caching

```php
class EventStatsService
{
    public function getStats(string $eventId): array
    {
        return Cache::tags(['events', 'stats'])->remember(
            "event_{$eventId}_stats",
            3600,
            function () use ($eventId) {
                $event = Event::with(['attendees', 'payments'])->findOrFail($eventId);
                
                return [
                    'total_attendees' => $event->attendees->count(),
                    'checked_in' => $event->attendees->whereNotNull('checked_in_at')->count(),
                    'revenue' => $event->payments->sum('amount'),
                    'average_rating' => $event->reviews->avg('rating'),
                    'capacity_percentage' => ($event->current_attendees / $event->max_attendees) * 100,
                ];
            }
        );
    }
    
    public function clearStats(string $eventId): void
    {
        Cache::tags(['events', 'stats'])->forget("event_{$eventId}_stats");
    }
}
```

## View Caching

### Blade Cache Directive

```blade
{{-- Cache for 1 hour --}}
@cache('event_card_' . $event->id, 3600)
    <div class="event-card">
        <h3>{{ $event->title }}</h3>
        <p>{{ $event->description }}</p>
        <div class="venue">{{ $event->venue->name }}</div>
        <time>{{ $event->starts_at_formatted }}</time>
    </div>
@endcache

{{-- Cache with tags --}}
@cache(['key' => 'sidebar_' . $user->id, 'tags' => ['users', 'navigation']], 7200)
    @include('partials.sidebar', ['user' => $user])
@endcache
```

### Fragment Caching

```php
// In controller
$html = Cache::remember('homepage_events', 3600, function () {
    return view('partials.event-list', [
        'events' => Event::upcoming()->limit(10)->get()
    ])->render();
});

return view('home', compact('html'));
```

## API Response Caching

### Middleware Implementation

```php
class CacheResponse
{
    public function handle($request, Closure $next)
    {
        // Skip for non-GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }
        
        $key = $this->getCacheKey($request);
        
        // Check cache
        if ($cached = Cache::get($key)) {
            return response($cached['content'], 200)
                ->header('X-Cache', 'HIT')
                ->header('Content-Type', $cached['content_type']);
        }
        
        $response = $next($request);
        
        // Cache successful responses
        if ($response->status() === 200) {
            Cache::put($key, [
                'content' => $response->content(),
                'content_type' => $response->headers->get('Content-Type'),
            ], 300); // 5 minutes
        }
        
        return $response->header('X-Cache', 'MISS');
    }
    
    protected function getCacheKey($request): string
    {
        return 'response_' . md5($request->fullUrl() . $request->user()?->id);
    }
}
```

### Conditional Caching

```php
class EventController extends Controller
{
    public function show(Request $request, Event $event)
    {
        $etag = md5($event->updated_at . $event->attendees_count);
        
        // Check If-None-Match header
        if ($request->header('If-None-Match') === $etag) {
            return response(null, 304);
        }
        
        $response = Cache::remember("event_api_{$event->id}", 600, function () use ($event) {
            return new EventResource($event->load(['venue', 'organizer']));
        });
        
        return response($response)
            ->header('ETag', $etag)
            ->header('Cache-Control', 'private, max-age=600');
    }
}
```

## Cache Warming

### Scheduled Cache Warming

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('cache:warm-events')->hourly();
    $schedule->command('cache:warm-settings')->daily();
}

// app/Console/Commands/WarmEventCache.php
class WarmEventCache extends Command
{
    protected $signature = 'cache:warm-events';
    
    public function handle()
    {
        $this->info('Warming event cache...');
        
        Tenant::active()->each(function ($tenant) {
            TenantService::setCurrentTenant($tenant);
            
            // Warm upcoming events
            $events = Event::upcoming()->with(['venue', 'organizer'])->get();
            Cache::tags(['events', "tenant_{$tenant->id}"])
                ->put('upcoming_events', $events, 3600);
            
            // Warm individual events
            $events->each(function ($event) {
                Cache::put("event_{$event->id}", $event, 3600);
            });
        });
        
        $this->info('Event cache warmed successfully.');
    }
}
```

## Cache Invalidation

### Event-Based Invalidation

```php
class EventObserver
{
    public function saved(Event $event): void
    {
        $this->clearEventCache($event);
    }
    
    public function deleted(Event $event): void
    {
        $this->clearEventCache($event);
    }
    
    protected function clearEventCache(Event $event): void
    {
        // Clear specific caches
        Cache::forget("event_{$event->id}");
        Cache::forget("event_api_{$event->id}");
        
        // Clear tagged caches
        Cache::tags(['events', "tenant_{$event->tenant_id}"])->flush();
        Cache::tags(['stats', "event_{$event->id}"])->flush();
        
        // Clear related caches
        Cache::forget("venue_{$event->venue_id}_events");
    }
}
```

### Smart Cache Invalidation

```php
trait SmartCacheInvalidation
{
    protected function invalidateRelatedCaches(): void
    {
        $tags = $this->getCacheTags();
        
        foreach ($tags as $tag) {
            Cache::tags($tag)->flush();
        }
        
        $keys = $this->getCacheKeys();
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
    
    protected function getCacheTags(): array
    {
        return [
            $this->getTable(),
            "tenant_{$this->tenant_id}",
            get_class($this),
        ];
    }
    
    protected function getCacheKeys(): array
    {
        return [
            "{$this->getTable()}_{$this->id}",
            "{$this->getTable()}_list_{$this->tenant_id}",
        ];
    }
}
```

## Redis Data Structures

### Using Redis Lists

```php
class ActivityFeed
{
    public function add(string $userId, array $activity): void
    {
        $key = "user:{$userId}:activities";
        
        Redis::lpush($key, json_encode($activity));
        Redis::ltrim($key, 0, 99); // Keep last 100 activities
        Redis::expire($key, 86400); // Expire after 24 hours
    }
    
    public function get(string $userId, int $limit = 20): array
    {
        $key = "user:{$userId}:activities";
        
        $activities = Redis::lrange($key, 0, $limit - 1);
        
        return array_map(fn($item) => json_decode($item, true), $activities);
    }
}
```

### Using Redis Sets

```php
class OnlineUsers
{
    public function setOnline(string $userId): void
    {
        Redis::sadd('online_users', $userId);
        Redis::setex("user:{$userId}:online", 300, time()); // 5 minute timeout
    }
    
    public function setOffline(string $userId): void
    {
        Redis::srem('online_users', $userId);
        Redis::del("user:{$userId}:online");
    }
    
    public function getOnlineCount(): int
    {
        return Redis::scard('online_users');
    }
    
    public function isOnline(string $userId): bool
    {
        return Redis::exists("user:{$userId}:online");
    }
}
```

## Monitoring & Debugging

### Cache Hit Rate

```php
class CacheMonitor
{
    public function recordHit(string $key): void
    {
        Redis::hincrby('cache:stats:' . date('Y-m-d'), 'hits', 1);
        Redis::hincrby('cache:stats:' . date('Y-m-d'), "hits:{$key}", 1);
    }
    
    public function recordMiss(string $key): void
    {
        Redis::hincrby('cache:stats:' . date('Y-m-d'), 'misses', 1);
        Redis::hincrby('cache:stats:' . date('Y-m-d'), "misses:{$key}", 1);
    }
    
    public function getHitRate(string $date = null): float
    {
        $date = $date ?? date('Y-m-d');
        $stats = Redis::hgetall('cache:stats:' . $date);
        
        $hits = $stats['hits'] ?? 0;
        $misses = $stats['misses'] ?? 0;
        $total = $hits + $misses;
        
        return $total > 0 ? ($hits / $total) * 100 : 0;
    }
}
```

## Best Practices

1. **Use appropriate TTL** - Don't cache forever unless necessary
2. **Tag related caches** - Makes invalidation easier
3. **Cache at the right level** - Service layer for business logic
4. **Monitor cache performance** - Track hit rates and sizes
5. **Implement cache warming** - Preload frequently accessed data
6. **Handle cache failures gracefully** - Always have fallbacks
7. **Use cache locks** - Prevent cache stampede
8. **Clear caches selectively** - Don't flush everything
9. **Document cache dependencies** - Know what invalidates what
10. **Test cache behavior** - Include cache scenarios in tests 