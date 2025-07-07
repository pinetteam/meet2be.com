# Monitoring & Logging

## Overview

Meet2Be implements comprehensive monitoring and logging to ensure system reliability, performance tracking, and quick issue resolution. The system uses structured logging, real-time monitoring, and alerting mechanisms.

## Logging Architecture

### Log Channels

```php
// config/logging.php
return [
    'default' => env('LOG_CHANNEL', 'stack'),
    
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'slack'],
            'ignore_exceptions' => false,
        ],
        
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'permission' => 0664,
        ],
        
        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Meet2Be Logger',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],
        
        'security' => [
            'driver' => 'daily',
            'path' => storage_path('logs/security.log'),
            'level' => 'info',
            'days' => 90,
        ],
        
        'performance' => [
            'driver' => 'daily',
            'path' => storage_path('logs/performance.log'),
            'level' => 'info',
            'days' => 7,
        ],
        
        'api' => [
            'driver' => 'daily',
            'path' => storage_path('logs/api.log'),
            'level' => 'info',
            'days' => 30,
        ],
        
        'tenant' => [
            'driver' => 'custom',
            'via' => App\Logging\TenantLogger::class,
            'level' => 'debug',
        ],
    ],
];
```

### Custom Logger

```php
namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use App\Services\TenantService;

class TenantLogger
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('tenant');
        
        // Dynamic path based on tenant
        $tenant = TenantService::getCurrentTenant();
        $path = storage_path("logs/tenants/{$tenant?->id}/laravel.log");
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $handler = new StreamHandler($path, $config['level'] ?? 'debug');
        $handler->pushProcessor(new IntrospectionProcessor());
        
        // Add custom processors
        $handler->pushProcessor(function ($record) use ($tenant) {
            $record['extra']['tenant_id'] = $tenant?->id;
            $record['extra']['user_id'] = auth()->id();
            $record['extra']['request_id'] = request()->header('X-Request-ID');
            $record['extra']['ip'] = request()->ip();
            
            return $record;
        });
        
        $logger->pushHandler($handler);
        
        return $logger;
    }
}
```

## Structured Logging

### Log Context

```php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoggingService
{
    protected array $context = [];
    
    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }
    
    public function info(string $message, array $context = []): void
    {
        Log::channel('stack')->info($message, $this->mergeContext($context));
    }
    
    public function error(string $message, array $context = []): void
    {
        Log::channel('stack')->error($message, $this->mergeContext($context));
    }
    
    public function security(string $event, array $context = []): void
    {
        Log::channel('security')->info($event, $this->mergeContext([
            'event_type' => 'security',
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]));
    }
    
    public function performance(string $operation, float $duration, array $context = []): void
    {
        Log::channel('performance')->info($operation, $this->mergeContext([
            'duration_ms' => round($duration * 1000, 2),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            ...$context,
        ]));
    }
    
    protected function mergeContext(array $context): array
    {
        return array_merge($this->context, [
            'environment' => app()->environment(),
            'tenant_id' => TenantService::getCurrentTenant()?->id,
            'user_id' => auth()->id(),
            'request_id' => request()->header('X-Request-ID'),
            'session_id' => session()->getId(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $context);
    }
}
```

### Usage Examples

```php
// Security logging
app(LoggingService::class)->security('login_attempt', [
    'email' => $request->email,
    'success' => false,
    'reason' => 'invalid_credentials',
]);

// Performance logging
$start = microtime(true);
$result = $this->processLargeDataset();
$duration = microtime(true) - $start;

app(LoggingService::class)->performance('dataset_processing', $duration, [
    'records_processed' => count($result),
    'memory_used' => memory_get_usage(true) - $memoryBefore,
]);

// API logging
app(LoggingService::class)
    ->withContext(['api_version' => 'v1'])
    ->info('API request', [
        'endpoint' => $request->path(),
        'parameters' => $request->all(),
        'response_code' => $response->status(),
    ]);
```

## Application Monitoring

### Health Checks

```php
namespace App\Http\Controllers\Monitoring;

class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];
        
        $healthy = collect($checks)->every(fn($check) => $check['status'] === 'healthy');
        
        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }
    
    protected function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $duration = (microtime(true) - $start) * 1000;
            
            return [
                'status' => 'healthy',
                'response_time_ms' => round($duration, 2),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }
    
    protected function checkRedis(): array
    {
        try {
            $start = microtime(true);
            Redis::ping();
            $duration = (microtime(true) - $start) * 1000;
            
            return [
                'status' => 'healthy',
                'response_time_ms' => round($duration, 2),
                'memory_usage' => Redis::info()['used_memory_human'] ?? 'unknown',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }
    
    protected function checkStorage(): array
    {
        try {
            $disk = Storage::disk('local');
            $testFile = 'health-check-' . Str::random(10);
            
            $disk->put($testFile, 'test');
            $disk->delete($testFile);
            
            return [
                'status' => 'healthy',
                'free_space' => disk_free_space(storage_path()),
                'total_space' => disk_total_space(storage_path()),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }
    
    protected function checkQueue(): array
    {
        try {
            $size = Queue::size();
            $failed = DB::table('failed_jobs')->count();
            
            return [
                'status' => $size < 1000 && $failed < 100 ? 'healthy' : 'degraded',
                'queue_size' => $size,
                'failed_jobs' => $failed,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }
}
```

### Metrics Collection

```php
namespace App\Services\Monitoring;

use Illuminate\Support\Facades\Cache;

class MetricsService
{
    protected string $prefix = 'metrics:';
    
    public function increment(string $metric, int $value = 1, array $tags = []): void
    {
        $key = $this->getKey($metric, $tags);
        Cache::increment($key, $value);
    }
    
    public function gauge(string $metric, float $value, array $tags = []): void
    {
        $key = $this->getKey($metric, $tags);
        Cache::put($key, $value, now()->addHour());
    }
    
    public function timing(string $metric, float $duration, array $tags = []): void
    {
        $this->histogram($metric, $duration, array_merge($tags, ['unit' => 'ms']));
    }
    
    public function histogram(string $metric, float $value, array $tags = []): void
    {
        $key = $this->getKey($metric . ':histogram', $tags);
        $histogram = Cache::get($key, [
            'count' => 0,
            'sum' => 0,
            'min' => PHP_FLOAT_MAX,
            'max' => PHP_FLOAT_MIN,
        ]);
        
        $histogram['count']++;
        $histogram['sum'] += $value;
        $histogram['min'] = min($histogram['min'], $value);
        $histogram['max'] = max($histogram['max'], $value);
        
        Cache::put($key, $histogram, now()->addHour());
    }
    
    protected function getKey(string $metric, array $tags): string
    {
        $tagString = collect($tags)
            ->map(fn($value, $key) => "{$key}:{$value}")
            ->sort()
            ->implode(',');
        
        return $this->prefix . $metric . ($tagString ? ":{$tagString}" : '');
    }
    
    public function getMetrics(): array
    {
        $keys = Cache::getMultiple(
            Cache::getPrefix() . $this->prefix . '*'
        );
        
        $metrics = [];
        foreach ($keys as $key => $value) {
            $metricName = str_replace($this->prefix, '', $key);
            $metrics[$metricName] = $value;
        }
        
        return $metrics;
    }
}
```

## Error Tracking

### Exception Handler

```php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];
    
    public function report(Throwable $e)
    {
        // Log to appropriate channel based on exception type
        if ($e instanceof SecurityException) {
            Log::channel('security')->error($e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
        
        // Send to external monitoring service
        if ($this->shouldReport($e) && app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }
        
        // Critical alerts
        if ($e instanceof CriticalException) {
            Notification::route('slack', config('services.slack.critical'))
                ->notify(new CriticalErrorNotification($e));
        }
        
        parent::report($e);
    }
    
    public function render($request, Throwable $e)
    {
        // Add request ID to error responses
        $response = parent::render($request, $e);
        
        if ($request->hasHeader('X-Request-ID')) {
            $response->headers->set('X-Request-ID', $request->header('X-Request-ID'));
        }
        
        return $response;
    }
}
```

### Sentry Integration

```php
// config/sentry.php
return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),
    
    'release' => env('SENTRY_RELEASE', git_commit_short()),
    
    'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV')),
    
    'breadcrumbs' => [
        'logs' => true,
        'sql_queries' => true,
        'sql_bindings' => true,
        'queue_info' => true,
        'command_info' => true,
    ],
    
    'tracing' => [
        'transaction_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.1),
        'trace_sql_queries' => true,
        'trace_sql_query_bindings' => false,
        'trace_queue_jobs' => true,
        'trace_requests' => true,
        'trace_redis_commands' => true,
    ],
    
    'send_default_pii' => false,
    
    'before_send' => function (\Sentry\Event $event, ?\Sentry\EventHint $hint): ?\Sentry\Event {
        // Filter sensitive data
        if ($event->getRequest()) {
            $event->getRequest()->setData(
                $this->filterSensitiveData($event->getRequest()->getData())
            );
        }
        
        return $event;
    },
];
```

## Performance Monitoring

### Request Performance

```php
namespace App\Http\Middleware;

class PerformanceMonitoring
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        $startMemory = memory_get_usage(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        $memoryUsed = memory_get_usage(true) - $startMemory;
        
        // Log slow requests
        if ($duration > 1.0) {
            Log::channel('performance')->warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration' => round($duration, 3),
                'memory_mb' => round($memoryUsed / 1024 / 1024, 2),
                'user_id' => auth()->id(),
            ]);
        }
        
        // Add performance headers
        $response->headers->set('X-Response-Time', round($duration * 1000) . 'ms');
        $response->headers->set('X-Memory-Usage', round($memoryUsed / 1024) . 'KB');
        
        // Send metrics
        app(MetricsService::class)->timing('request.duration', $duration * 1000, [
            'method' => $request->method(),
            'route' => $request->route()?->getName() ?? 'unknown',
            'status' => $response->status(),
        ]);
        
        return $response;
    }
}
```

### Database Query Monitoring

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseMonitoringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('app.debug') || config('monitoring.log_queries')) {
            DB::listen(function ($query) {
                if ($query->time > 100) {
                    Log::channel('performance')->warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time_ms' => $query->time,
                        'connection' => $query->connectionName,
                    ]);
                }
                
                app(MetricsService::class)->timing('database.query', $query->time, [
                    'connection' => $query->connectionName,
                ]);
            });
        }
    }
}
```

## Real-time Monitoring

### Horizon Dashboard

```php
// config/horizon.php
'metrics' => [
    'trim_snapshots' => [
        'job' => 24,
        'queue' => 24,
    ],
],

'fast_termination' => false,

'memory_limit' => 64,

'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'maxProcesses' => 10,
            'memory' => 128,
            'tries' => 3,
            'nice' => 0,
        ],
    ],
],
```

### Custom Dashboard

```php
namespace App\Http\Controllers\Monitoring;

class DashboardController extends Controller
{
    public function index()
    {
        return view('monitoring.dashboard', [
            'systemHealth' => $this->getSystemHealth(),
            'realtimeMetrics' => $this->getRealtimeMetrics(),
            'errorRate' => $this->getErrorRate(),
            'responseTime' => $this->getAverageResponseTime(),
            'activeUsers' => $this->getActiveUsers(),
            'queueStatus' => $this->getQueueStatus(),
        ]);
    }
    
    protected function getRealtimeMetrics(): array
    {
        return Cache::remember('monitoring:realtime', 5, function () {
            return [
                'requests_per_minute' => $this->calculateRequestRate(),
                'average_response_time' => $this->calculateAverageResponseTime(),
                'error_rate' => $this->calculateErrorRate(),
                'active_connections' => $this->getActiveConnections(),
            ];
        });
    }
    
    protected function calculateRequestRate(): float
    {
        $key = 'metrics:requests:' . now()->format('Y-m-d-H-i');
        return Cache::get($key, 0);
    }
}
```

## Alerting

### Alert Rules

```php
namespace App\Services\Monitoring;

class AlertManager
{
    protected array $rules = [
        'high_error_rate' => [
            'threshold' => 5, // percent
            'window' => 300, // seconds
            'severity' => 'critical',
        ],
        'slow_response_time' => [
            'threshold' => 2000, // milliseconds
            'window' => 300,
            'severity' => 'warning',
        ],
        'queue_backup' => [
            'threshold' => 1000, // jobs
            'severity' => 'warning',
        ],
        'disk_space' => [
            'threshold' => 90, // percent
            'severity' => 'critical',
        ],
    ];
    
    public function check(): void
    {
        foreach ($this->rules as $rule => $config) {
            $value = $this->getValue($rule);
            
            if ($this->shouldAlert($rule, $value, $config)) {
                $this->sendAlert($rule, $value, $config);
            }
        }
    }
    
    protected function shouldAlert(string $rule, $value, array $config): bool
    {
        // Check if we've already alerted recently
        $lastAlert = Cache::get("alert:sent:{$rule}");
        if ($lastAlert && $lastAlert->gt(now()->subMinutes(15))) {
            return false;
        }
        
        return $value > $config['threshold'];
    }
    
    protected function sendAlert(string $rule, $value, array $config): void
    {
        $message = $this->formatAlertMessage($rule, $value, $config);
        
        // Send to appropriate channels based on severity
        switch ($config['severity']) {
            case 'critical':
                Notification::route('slack', config('services.slack.critical'))
                    ->route('mail', config('monitoring.alert_email'))
                    ->notify(new SystemAlert($message, 'critical'));
                
                // Also send SMS for critical alerts
                Notification::route('nexmo', config('monitoring.alert_phone'))
                    ->notify(new SmsAlert($message));
                break;
                
            case 'warning':
                Notification::route('slack', config('services.slack.alerts'))
                    ->notify(new SystemAlert($message, 'warning'));
                break;
        }
        
        // Mark as sent
        Cache::put("alert:sent:{$rule}", now(), now()->addMinutes(15));
    }
}
```

## Log Analysis

### Log Parser

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class AnalyzeLogs extends Command
{
    protected $signature = 'logs:analyze {--channel=} {--date=}';
    
    public function handle()
    {
        $channel = $this->option('channel') ?? 'laravel';
        $date = $this->option('date') ?? now()->format('Y-m-d');
        
        $logFile = storage_path("logs/{$channel}-{$date}.log");
        
        if (!file_exists($logFile)) {
            $this->error("Log file not found: {$logFile}");
            return;
        }
        
        $stats = [
            'total_lines' => 0,
            'levels' => [],
            'top_errors' => [],
            'response_times' => [],
        ];
        
        $handle = fopen($logFile, 'r');
        while (($line = fgets($handle)) !== false) {
            $stats['total_lines']++;
            
            // Parse log level
            if (preg_match('/^\[([\d-]+\s[\d:]+)\]\s+(\w+)\.(\w+):/', $line, $matches)) {
                $level = $matches[3];
                $stats['levels'][$level] = ($stats['levels'][$level] ?? 0) + 1;
            }
            
            // Parse response times
            if (preg_match('/duration:(\d+\.?\d*)/', $line, $matches)) {
                $stats['response_times'][] = floatval($matches[1]);
            }
        }
        fclose($handle);
        
        // Calculate statistics
        $this->displayResults($stats);
    }
    
    protected function displayResults(array $stats): void
    {
        $this->info("Log Analysis Results");
        $this->info("===================");
        $this->info("Total Lines: " . number_format($stats['total_lines']));
        
        $this->info("\nLog Levels:");
        foreach ($stats['levels'] as $level => $count) {
            $percentage = round(($count / $stats['total_lines']) * 100, 2);
            $this->info("  {$level}: {$count} ({$percentage}%)");
        }
        
        if (!empty($stats['response_times'])) {
            $avg = array_sum($stats['response_times']) / count($stats['response_times']);
            $this->info("\nResponse Times:");
            $this->info("  Average: " . round($avg, 2) . "ms");
            $this->info("  Min: " . min($stats['response_times']) . "ms");
            $this->info("  Max: " . max($stats['response_times']) . "ms");
        }
    }
}
```

## Audit Logging

### Audit Trail

```php
namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });
        
        static::updated(function ($model) {
            $model->logAudit('updated', $model->getDirty());
        });
        
        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });
    }
    
    public function logAudit(string $action, array $changes = []): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'tenant_id' => $this->tenant_id ?? TenantService::getCurrentTenant()?->id,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => $action,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ]);
    }
    
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'model');
    }
}
```

## Dashboard Integration

### Grafana Configuration

```json
{
  "dashboard": {
    "title": "Meet2Be Application Metrics",
    "panels": [
      {
        "title": "Request Rate",
        "targets": [
          {
            "expr": "rate(app_requests_total[5m])"
          }
        ]
      },
      {
        "title": "Response Time",
        "targets": [
          {
            "expr": "histogram_quantile(0.95, app_response_time_bucket)"
          }
        ]
      },
      {
        "title": "Error Rate",
        "targets": [
          {
            "expr": "rate(app_errors_total[5m]) / rate(app_requests_total[5m]) * 100"
          }
        ]
      }
    ]
  }
}
```

## Best Practices

1. **Use structured logging** - JSON format for easy parsing
2. **Include context** - User, tenant, request ID in all logs
3. **Set appropriate log levels** - Don't log sensitive data
4. **Monitor key metrics** - Response time, error rate, queue size
5. **Set up alerts** - For critical issues and thresholds
6. **Rotate logs** - Prevent disk space issues
7. **Use correlation IDs** - Track requests across services
8. **Audit sensitive operations** - Track who did what when
9. **Test monitoring** - Ensure alerts work correctly
10. **Document runbooks** - How to respond to alerts 