# Environment Configuration

## Overview

Environment configuration in Meet2Be follows the twelve-factor app methodology, using environment variables for configuration that changes between deployments.

## Environment Files

### .env Structure

```env
# Application
APP_NAME="Meet2Be"
APP_ENV=local
APP_KEY=base64:generated_key_here
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meet2be
DB_USERNAME=root
DB_PASSWORD=

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=phpredis
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2
REDIS_QUEUE_DB=3

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@meet2be.com"
MAIL_FROM_NAME="${APP_NAME}"

# AWS Services
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Pusher (Broadcasting)
BROADCAST_DRIVER=log
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

# Security
SECURE_COOKIES=false
SESSION_SECURE_COOKIE=false
SANCTUM_STATEFUL_DOMAINS=localhost

# Features
FEATURE_API_V2=false
FEATURE_WEBSOCKETS=false
FEATURE_EXPORT=true

# Monitoring
SENTRY_LARAVEL_DSN=
SENTRY_ENVIRONMENT="${APP_ENV}"
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Third-party Services
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

GOOGLE_MAPS_API_KEY=
GOOGLE_ANALYTICS_ID=

# Development
DEBUGBAR_ENABLED=true
TELESCOPE_ENABLED=true
```

### Environment-Specific Files

```
.env                # Main environment file (git-ignored)
.env.example        # Example template (committed)
.env.local          # Local overrides (git-ignored)
.env.testing        # Testing environment (git-ignored)
.env.production     # Production reference (git-ignored)
```

## Configuration Files

### App Configuration

```php
// config/app.php
return [
    'name' => env('APP_NAME', 'Meet2Be'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    
    // Custom configuration
    'supported_locales' => ['en', 'tr', 'de', 'fr', 'es'],
    'admin_email' => env('ADMIN_EMAIL', 'admin@meet2be.com'),
    'support_email' => env('SUPPORT_EMAIL', 'support@meet2be.com'),
];
```

### Database Configuration

```php
// config/database.php
return [
    'default' => env('DB_CONNECTION', 'mysql'),
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_PERSISTENT => env('DB_PERSISTENT', false),
            ]) : [],
        ],
        
        'mysql_read' => [
            'driver' => 'mysql',
            'host' => env('DB_READ_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_READ_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_READ_USERNAME', env('DB_USERNAME', 'forge')),
            'password' => env('DB_READ_PASSWORD', env('DB_PASSWORD', '')),
            // ... same as mysql
        ],
    ],
    
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
        
        'session' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_SESSION_DB', '2'),
        ],
        
        'queue' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_QUEUE_DB', '3'),
        ],
    ],
];
```

## Environment-Specific Settings

### Local Development

```php
// config/local.php
return [
    'debug_bar' => env('DEBUGBAR_ENABLED', true),
    'telescope' => env('TELESCOPE_ENABLED', true),
    'model_strict' => true,
    'query_log' => true,
    'mail_pretend' => true,
];
```

### Staging Environment

```env
# .env.staging
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging.meet2be.com

DB_HOST=staging-db.meet2be.com
DB_DATABASE=meet2be_staging

MAIL_MAILER=ses
MAIL_HOST=email-smtp.us-east-1.amazonaws.com

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

SENTRY_LARAVEL_DSN=https://staging-key@sentry.io/project
```

### Production Environment

```env
# .env.production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://meet2be.com

DB_HOST=prod-db-master.meet2be.com
DB_READ_HOST=prod-db-replica.meet2be.com
DB_DATABASE=meet2be_production

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=ses
BROADCAST_DRIVER=pusher

SECURE_COOKIES=true
SESSION_SECURE_COOKIE=true

SENTRY_LARAVEL_DSN=https://prod-key@sentry.io/project
```

## Custom Configuration

### Feature Flags

```php
// config/features.php
return [
    'api_v2' => env('FEATURE_API_V2', false),
    'websockets' => env('FEATURE_WEBSOCKETS', false),
    'export' => env('FEATURE_EXPORT', true),
    'two_factor' => env('FEATURE_TWO_FACTOR', true),
    'social_login' => env('FEATURE_SOCIAL_LOGIN', false),
    
    // Per-environment features
    'debug_toolbar' => env('APP_ENV') === 'local',
    'query_log' => env('APP_DEBUG', false),
];
```

### Service Configuration

```php
// config/services.php
return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    
    'google' => [
        'maps' => [
            'key' => env('GOOGLE_MAPS_API_KEY'),
        ],
        'analytics' => [
            'id' => env('GOOGLE_ANALYTICS_ID'),
        ],
    ],
    
    'sentry' => [
        'dsn' => env('SENTRY_LARAVEL_DSN'),
        'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV')),
        'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.1),
    ],
];
```

## Environment Validation

### Configuration Validator

```php
namespace App\Services;

class EnvironmentValidator
{
    protected array $required = [
        'APP_KEY',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
    ];
    
    protected array $production = [
        'APP_DEBUG' => 'false',
        'APP_ENV' => 'production',
        'SECURE_COOKIES' => 'true',
        'SESSION_SECURE_COOKIE' => 'true',
    ];
    
    public function validate(): array
    {
        $errors = [];
        
        // Check required variables
        foreach ($this->required as $key) {
            if (empty(env($key))) {
                $errors[] = "Missing required environment variable: {$key}";
            }
        }
        
        // Production-specific checks
        if (app()->environment('production')) {
            foreach ($this->production as $key => $expectedValue) {
                if (env($key) !== $expectedValue) {
                    $errors[] = "Invalid production value for {$key}. Expected: {$expectedValue}";
                }
            }
        }
        
        return $errors;
    }
}
```

### Health Check

```php
Route::get('/health', function () {
    $validator = new EnvironmentValidator();
    $errors = $validator->validate();
    
    if (!empty($errors)) {
        return response()->json([
            'status' => 'unhealthy',
            'errors' => $errors,
        ], 500);
    }
    
    return response()->json([
        'status' => 'healthy',
        'environment' => app()->environment(),
        'debug' => config('app.debug'),
        'cache' => Cache::connection()->ping(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'queue' => Queue::size() < 1000 ? 'healthy' : 'backed_up',
    ]);
});
```

## Dynamic Configuration

### Runtime Configuration

```php
// app/Providers/AppServiceProvider.php
public function boot()
{
    // Load tenant-specific configuration
    if ($tenant = TenantService::getCurrentTenant()) {
        config([
            'app.name' => $tenant->name,
            'app.timezone' => $tenant->timezone,
            'app.locale' => $tenant->locale,
            'mail.from.name' => $tenant->name,
        ]);
    }
    
    // Environment-specific settings
    if (app()->environment('local')) {
        config(['database.connections.mysql.strict' => false]);
    }
}
```

### Configuration Cache

```bash
# Cache configuration for production
php artisan config:cache

# Clear configuration cache
php artisan config:clear

# Refresh configuration
php artisan config:clear && php artisan config:cache
```

## Security Best Practices

### Sensitive Data Protection

```php
// Never log sensitive data
protected $hidden = [
    'APP_KEY',
    'DB_PASSWORD',
    'REDIS_PASSWORD',
    'MAIL_PASSWORD',
    'AWS_SECRET_ACCESS_KEY',
    'STRIPE_SECRET',
];

// Mask sensitive values in logs
Log::info('Connecting to database', [
    'host' => env('DB_HOST'),
    'database' => env('DB_DATABASE'),
    'password' => '***',
]);
```

### Environment File Security

```bash
# Set proper permissions
chmod 600 .env
chmod 644 .env.example

# Never commit .env files
echo ".env*" >> .gitignore
echo "!.env.example" >> .gitignore
```

## Deployment Configuration

### Docker Environment

```dockerfile
# Dockerfile
FROM php:8.2-fpm

# Copy environment file
COPY .env.production /var/www/.env

# Set environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false
```

### CI/CD Environment

```yaml
# .github/workflows/deploy.yml
env:
  APP_ENV: production
  APP_DEBUG: false
  
steps:
  - name: Setup Environment
    run: |
      cp .env.production .env
      echo "APP_KEY=${{ secrets.APP_KEY }}" >> .env
      echo "DB_PASSWORD=${{ secrets.DB_PASSWORD }}" >> .env
```

## Testing Environment

### PHPUnit Configuration

```xml
<!-- phpunit.xml -->
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="BCRYPT_ROUNDS" value="4"/>
    <env name="CACHE_DRIVER" value="array"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
    <env name="MAIL_MAILER" value="array"/>
    <env name="QUEUE_CONNECTION" value="sync"/>
    <env name="SESSION_DRIVER" value="array"/>
    <env name="TELESCOPE_ENABLED" value="false"/>
</php>
```

### Test Environment Setup

```php
// tests/TestCase.php
protected function setUp(): void
{
    parent::setUp();
    
    // Set test environment
    config(['app.env' => 'testing']);
    config(['cache.default' => 'array']);
    config(['session.driver' => 'array']);
}
```

## Monitoring Configuration

### Environment Monitoring

```php
// app/Console/Commands/CheckEnvironment.php
class CheckEnvironment extends Command
{
    protected $signature = 'env:check';
    
    public function handle()
    {
        $this->info('Checking environment configuration...');
        
        // Check required services
        $services = [
            'Database' => fn() => DB::connection()->getPdo(),
            'Redis' => fn() => Redis::connection()->ping(),
            'Mail' => fn() => Mail::getSwiftMailer()->getTransport()->ping(),
        ];
        
        foreach ($services as $name => $check) {
            try {
                $check();
                $this->info("✓ {$name} is configured correctly");
            } catch (\Exception $e) {
                $this->error("✗ {$name} configuration error: " . $e->getMessage());
            }
        }
    }
}
```

## Best Practices

1. **Never commit .env files** - Only .env.example
2. **Use strong APP_KEY** - Generate with `php artisan key:generate`
3. **Set APP_DEBUG=false** in production
4. **Use environment-specific configs** - Don't hardcode values
5. **Validate configuration** - Check required values on deploy
6. **Use secrets management** - For sensitive data
7. **Cache configuration** - In production for performance
8. **Document all variables** - In .env.example
9. **Use type casting** - When reading env values
10. **Monitor configuration** - Ensure services are accessible 