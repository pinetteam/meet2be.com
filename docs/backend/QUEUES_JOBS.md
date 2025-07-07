# Queue & Jobs

## Overview

Meet2Be uses Laravel's queue system with Redis as the queue driver for handling time-consuming tasks asynchronously. This improves application responsiveness and allows for better scaling.

## Queue Configuration

### Redis Configuration

```php
// config/queue.php
return [
    'default' => env('QUEUE_CONNECTION', 'redis'),
    
    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'queue',
            'queue' => env('REDIS_QUEUE', '{default}'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],
        
        'redis-long' => [
            'driver' => 'redis',
            'connection' => 'queue',
            'queue' => 'long-running',
            'retry_after' => 300,
            'block_for' => null,
        ],
        
        'redis-priority' => [
            'driver' => 'redis',
            'connection' => 'queue',
            'queue' => 'priority',
            'retry_after' => 60,
            'block_for' => null,
        ],
    ],
    
    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],
];
```

### Queue Priorities

```php
// Different queues for different priorities
return [
    'queues' => [
        'priority' => 'critical,high,default,low',
        'emails' => 'transactional,marketing',
        'exports' => 'exports',
        'imports' => 'imports',
    ],
];
```

## Job Structure

### Basic Job

```php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        protected User $user
    ) {
        $this->onQueue('emails');
    }
    
    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new WelcomeEmail($this->user));
    }
    
    public function failed(\Throwable $exception): void
    {
        \Log::error('Failed to send welcome email', [
            'user_id' => $this->user->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
```

### Advanced Job Features

```php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\Middleware\RateLimited;

class ProcessEventData implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    public $timeout = 120;
    public $maxExceptions = 3;
    public $backoff = [10, 30, 60];
    public $deleteWhenMissingModels = true;
    
    public function __construct(
        protected Event $event
    ) {
        $this->onQueue('processing');
        $this->onConnection('redis-long');
    }
    
    public function uniqueId(): string
    {
        return $this->event->id;
    }
    
    public function middleware(): array
    {
        return [
            new WithoutOverlapping($this->event->id),
            new RateLimited('event-processing'),
        ];
    }
    
    public function handle(): void
    {
        // Process event data
        $this->event->processAttendees();
        $this->event->calculateStatistics();
        $this->event->generateReports();
    }
    
    public function retryUntil(): DateTime
    {
        return now()->addHours(2);
    }
    
    public function failed(\Throwable $exception): void
    {
        // Notify administrators
        Notification::route('mail', config('app.admin_email'))
            ->notify(new JobFailedNotification($this->event, $exception));
    }
}
```

## Job Batching

### Creating Batches

```php
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class ImportService
{
    public function importUsers(string $filePath): void
    {
        $chunks = $this->parseFile($filePath);
        $jobs = [];
        
        foreach ($chunks as $index => $chunk) {
            $jobs[] = new ImportUserChunk($chunk, $index);
        }
        
        Bus::batch($jobs)
            ->name('User Import - ' . now()->format('Y-m-d H:i:s'))
            ->allowFailures()
            ->then(function (Batch $batch) {
                // All jobs completed successfully
                Notification::send(
                    $batch->options['user'],
                    new ImportCompletedNotification($batch)
                );
            })
            ->catch(function (Batch $batch, Throwable $e) {
                // First batch job failure detected
                Log::error('Import batch failed', [
                    'batch_id' => $batch->id,
                    'error' => $e->getMessage(),
                ]);
            })
            ->finally(function (Batch $batch) {
                // The batch has finished executing
                Cache::forget("import_batch_{$batch->id}");
            })
            ->onQueue('imports')
            ->dispatch();
    }
}
```

### Monitoring Batches

```php
// Get batch information
$batch = Bus::findBatch($batchId);

// Check progress
$progress = $batch->progress(); // 0-100
$totalJobs = $batch->totalJobs;
$pendingJobs = $batch->pendingJobs;
$failedJobs = $batch->failedJobs;

// Cancel batch
if ($shouldCancel) {
    $batch->cancel();
}

// Add more jobs to batch
$batch->add([
    new ProcessRecord($record),
]);
```

## Job Chains

### Sequential Processing

```php
use Illuminate\Support\Facades\Bus;

Bus::chain([
    new ProcessPayment($order),
    new UpdateInventory($order),
    new SendOrderConfirmation($order),
    new NotifyWarehouse($order),
])->catch(function (Throwable $e) {
    // Handle chain failure
    Log::error('Order processing chain failed', [
        'order_id' => $order->id,
        'error' => $e->getMessage(),
    ]);
})->dispatch();
```

### Conditional Chains

```php
$chain = [
    new ValidateOrder($order),
    new ProcessPayment($order),
];

if ($order->requiresShipping()) {
    $chain[] = new CalculateShipping($order);
    $chain[] = new CreateShippingLabel($order);
}

$chain[] = new SendConfirmation($order);

Bus::chain($chain)->dispatch();
```

## Queue Workers

### Running Workers

```bash
# Basic worker
php artisan queue:work

# Specific connection and queue
php artisan queue:work redis --queue=high,default

# With options
php artisan queue:work redis \
    --queue=critical,high,default \
    --tries=3 \
    --timeout=90 \
    --sleep=3 \
    --max-jobs=1000 \
    --max-time=3600
```

### Supervisor Configuration

```ini
[program:meet2be-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/logs/worker.log
stopwaitsecs=3600
```

### Horizon Configuration

```php
// config/horizon.php
return [
    'environments' => [
        'production' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['critical', 'high', 'default'],
                'balance' => 'auto',
                'maxProcesses' => 10,
                'minProcesses' => 1,
                'balanceMaxShift' => 1,
                'balanceCooldown' => 3,
                'memory' => 128,
                'tries' => 3,
                'timeout' => 90,
                'nice' => 0,
            ],
            
            'long-running' => [
                'connection' => 'redis-long',
                'queue' => ['exports', 'imports'],
                'balance' => 'simple',
                'processes' => 3,
                'memory' => 256,
                'tries' => 1,
                'timeout' => 600,
            ],
        ],
        
        'local' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['critical', 'high', 'default', 'low'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 1,
            ],
        ],
    ],
];
```

## Common Job Patterns

### Email Jobs

```php
namespace App\Jobs\Email;

class SendTransactionalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        protected User $user,
        protected string $template,
        protected array $data = []
    ) {
        $this->onQueue('transactional-emails');
    }
    
    public function handle(): void
    {
        // Rate limiting
        if (!$this->checkRateLimit()) {
            $this->release(60);
            return;
        }
        
        Mail::to($this->user)
            ->send(new DynamicEmail($this->template, $this->data));
        
        // Log email sent
        EmailLog::create([
            'user_id' => $this->user->id,
            'template' => $this->template,
            'sent_at' => now(),
        ]);
    }
    
    protected function checkRateLimit(): bool
    {
        $key = "email_rate_limit:{$this->user->id}";
        $sent = Cache::get($key, 0);
        
        if ($sent >= 10) {
            return false;
        }
        
        Cache::increment($key);
        Cache::expire($key, 3600);
        
        return true;
    }
}
```

### Export Jobs

```php
namespace App\Jobs\Export;

class ExportEventAttendees implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        protected Event $event,
        protected User $requestedBy,
        protected string $format = 'csv'
    ) {
        $this->onQueue('exports');
        $this->onConnection('redis-long');
    }
    
    public function handle(): void
    {
        $fileName = "event_{$this->event->id}_attendees." . $this->format;
        $filePath = storage_path("exports/{$fileName}");
        
        // Generate export
        $exporter = match($this->format) {
            'csv' => new CsvExporter(),
            'xlsx' => new ExcelExporter(),
            'pdf' => new PdfExporter(),
            default => throw new \InvalidArgumentException("Unsupported format: {$this->format}"),
        };
        
        $exporter->export($this->event->attendees, $filePath);
        
        // Store in S3
        $s3Path = Storage::disk('s3')->putFile(
            "exports/{$this->event->tenant_id}",
            new File($filePath)
        );
        
        // Create download link
        $downloadUrl = Storage::disk('s3')->temporaryUrl($s3Path, now()->addDays(7));
        
        // Notify user
        $this->requestedBy->notify(new ExportReadyNotification($downloadUrl));
        
        // Clean up local file
        unlink($filePath);
    }
    
    public function failed(\Throwable $exception): void
    {
        $this->requestedBy->notify(new ExportFailedNotification(
            $this->event,
            $exception->getMessage()
        ));
    }
}
```

### Webhook Processing

```php
namespace App\Jobs\Webhooks;

class ProcessWebhook implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 5;
    public $backoff = [30, 60, 120, 300, 600];
    
    public function __construct(
        protected string $url,
        protected array $payload,
        protected array $headers = []
    ) {
        $this->onQueue('webhooks');
    }
    
    public function uniqueId(): string
    {
        return md5($this->url . json_encode($this->payload));
    }
    
    public function handle(): void
    {
        $response = Http::timeout(30)
            ->withHeaders($this->headers)
            ->retry(3, 100)
            ->post($this->url, $this->payload);
        
        if (!$response->successful()) {
            Log::warning('Webhook failed', [
                'url' => $this->url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            if ($response->serverError()) {
                // Retry later for server errors
                $this->release($this->backoff[$this->attempts() - 1] ?? 600);
                return;
            }
            
            // Don't retry for client errors
            $this->fail(new WebhookException(
                "Webhook returned {$response->status()}: {$response->body()}"
            ));
        }
        
        // Log successful webhook
        WebhookLog::create([
            'url' => $this->url,
            'payload' => $this->payload,
            'response' => $response->body(),
            'status' => $response->status(),
            'sent_at' => now(),
        ]);
    }
}
```

## Job Middleware

### Rate Limiting

```php
namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Redis;

class RateLimited
{
    public function __construct(
        protected string $key,
        protected int $allows = 60,
        protected int $every = 60
    ) {}
    
    public function handle($job, $next)
    {
        Redis::throttle($this->key)
            ->allow($this->allows)
            ->every($this->every)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release($this->every);
            });
    }
}

// Usage in job
public function middleware()
{
    return [
        new RateLimited('api-calls', 30, 60),
    ];
}
```

### Database Transactions

```php
namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\DB;

class WithDatabaseTransaction
{
    public function handle($job, $next)
    {
        DB::transaction(function () use ($job, $next) {
            $next($job);
        });
    }
}
```

## Monitoring & Debugging

### Failed Jobs

```php
// Retry failed jobs
php artisan queue:retry all
php artisan queue:retry 5

// View failed jobs
php artisan queue:failed

// Clear failed jobs
php artisan queue:flush

// Handle failed jobs programmatically
$failed = DB::table('failed_jobs')->get();

foreach ($failed as $job) {
    $payload = json_decode($job->payload, true);
    
    // Analyze and potentially retry
    if ($this->shouldRetry($payload)) {
        Artisan::call('queue:retry', ['id' => $job->id]);
    }
}
```

### Job Events

```php
// app/Providers/EventServiceProvider.php
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

protected $listen = [
    JobProcessing::class => [
        [JobEventListener::class, 'handleJobProcessing'],
    ],
    JobProcessed::class => [
        [JobEventListener::class, 'handleJobProcessed'],
    ],
    JobFailed::class => [
        [JobEventListener::class, 'handleJobFailed'],
    ],
];

// Listener
class JobEventListener
{
    public function handleJobFailed(JobFailed $event)
    {
        Log::error('Job failed', [
            'connection' => $event->connectionName,
            'job' => $event->job->getName(),
            'exception' => $event->exception->getMessage(),
        ]);
        
        // Send alert for critical jobs
        if ($this->isCriticalJob($event->job)) {
            Notification::route('slack', config('services.slack.alerts'))
                ->notify(new CriticalJobFailedNotification($event));
        }
    }
}
```

## Testing Jobs

### Unit Tests

```php
use Illuminate\Support\Facades\Queue;

test('welcome email job is dispatched when user is created', function () {
    Queue::fake();
    
    $user = User::factory()->create();
    
    Queue::assertPushed(SendWelcomeEmail::class, function ($job) use ($user) {
        return $job->user->id === $user->id;
    });
});

test('job processes data correctly', function () {
    $event = Event::factory()->create();
    
    $job = new ProcessEventData($event);
    $job->handle();
    
    expect($event->fresh()->processed)->toBeTrue();
    expect($event->statistics)->not->toBeNull();
});
```

### Feature Tests

```php
test('export job completes successfully', function () {
    Storage::fake('s3');
    Notification::fake();
    
    $event = Event::factory()->create();
    $user = User::factory()->create();
    
    ExportEventAttendees::dispatch($event, $user);
    
    // Process the job
    Artisan::call('queue:work', [
        '--once' => true,
    ]);
    
    Storage::disk('s3')->assertExists("exports/{$event->tenant_id}");
    Notification::assertSentTo($user, ExportReadyNotification::class);
});
```

## Best Practices

1. **Use appropriate queues** - Separate by priority and type
2. **Set reasonable timeouts** - Prevent jobs from running forever
3. **Implement retry logic** - Handle transient failures
4. **Use job batching** - For processing large datasets
5. **Monitor queue health** - Track failed jobs and queue size
6. **Clean up resources** - Free memory after processing
7. **Log job activity** - For debugging and auditing
8. **Test job logic** - Include both unit and integration tests
9. **Handle failures gracefully** - Notify users of failures
10. **Use unique jobs** - Prevent duplicate processing 