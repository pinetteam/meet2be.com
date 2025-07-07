# Middleware

## Overview

Middleware provides a convenient mechanism for filtering HTTP requests entering your application. Meet2Be uses middleware for authentication, authorization, request modification, and response handling.

## Core Middleware

### Authentication Middleware

```php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
```

### Tenant Context Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantService;
use Illuminate\Support\Facades\Auth;

class EnsureTenantContext
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }
        
        $user = Auth::user();
        
        // Ensure user has tenant
        if (!$user->tenant_id) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['tenant' => 'No tenant associated with this account.']);
        }
        
        // Validate tenant is active
        if (!TenantService::validateTenant($user->tenant_id)) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['tenant' => 'Your organization access has been suspended.']);
        }
        
        // Set tenant context
        TenantService::setCurrentTenant($user->tenant);
        
        // Add tenant to request
        $request->merge(['current_tenant_id' => $user->tenant_id]);
        
        return $next($request);
    }
}
```

### Update Last Activity Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpdateLastActivity
{
    protected int $updateInterval = 5; // minutes
    
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $lastActivity = $user->last_activity_at;
            
            // Update if null or older than interval
            if (!$lastActivity || $lastActivity->lt(now()->subMinutes($this->updateInterval))) {
                $user->update(['last_activity_at' => now()]);
            }
        }
        
        return $next($request);
    }
}
```

## Security Middleware

### Verify CSRF Token

```php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'webhook/*',
        'api/stripe/webhook',
    ];
    
    protected function tokensMatch($request)
    {
        // Custom token validation logic if needed
        $token = $this->getTokenFromRequest($request);
        
        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }
}
```

### Security Headers Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    protected array $headers = [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    ];
    
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        foreach ($this->headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        
        // HSTS for production
        if (app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }
        
        // CSP
        $response->headers->set('Content-Security-Policy', $this->getCsp());
        
        return $response;
    }
    
    protected function getCsp(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https:",
            "connect-src 'self'",
        ]);
    }
}
```

### CORS Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleCors
{
    protected array $allowedOrigins = [
        'https://app.meet2be.com',
        'https://meet2be.com',
    ];
    
    public function handle(Request $request, Closure $next)
    {
        $origin = $request->headers->get('Origin');
        
        if (in_array($origin, $this->allowedOrigins) || app()->environment('local')) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin ?: '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }
        
        return $next($request);
    }
}
```

## Rate Limiting Middleware

### Throttle Requests

```php
namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottle;
use Illuminate\Http\Request;

class ThrottleRequests extends BaseThrottle
{
    protected function resolveRequestSignature($request)
    {
        if ($user = $request->user()) {
            return sha1($user->tenant_id . '|' . $user->id);
        }
        
        return sha1(
            $request->method() . '|' .
            $request->server('SERVER_NAME') . '|' .
            $request->path() . '|' .
            $request->ip()
        );
    }
    
    protected function getHeaders($maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        $headers = parent::getHeaders($maxAttempts, $remainingAttempts, $retryAfter);
        
        // Add custom headers
        $headers['X-RateLimit-Tenant'] = request()->user()?->tenant_id;
        
        return $headers;
    }
}
```

### API Rate Limiting

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimit
{
    public function handle(Request $request, Closure $next, string $tier = 'default')
    {
        $key = $this->resolveRateLimitKey($request, $tier);
        $limit = $this->getRateLimit($tier, $request->user());
        
        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return $this->buildResponse($key, $limit);
        }
        
        RateLimiter::hit($key);
        
        $response = $next($request);
        
        return $this->addHeaders($response, $key, $limit);
    }
    
    protected function resolveRateLimitKey(Request $request, string $tier): string
    {
        $user = $request->user();
        
        if ($user) {
            return sprintf('api:%s:tenant:%s:user:%s', $tier, $user->tenant_id, $user->id);
        }
        
        return sprintf('api:%s:ip:%s', $tier, $request->ip());
    }
    
    protected function getRateLimit(string $tier, ?User $user): int
    {
        $limits = [
            'default' => 60,
            'search' => 30,
            'export' => 10,
            'upload' => 20,
        ];
        
        $limit = $limits[$tier] ?? 60;
        
        // Premium users get higher limits
        if ($user?->isPremium()) {
            $limit *= 2;
        }
        
        return $limit;
    }
}
```

## Request Modification Middleware

### Locale Detection

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $this->detectLocale($request);
        
        app()->setLocale($locale);
        
        // Set Carbon locale
        \Carbon\Carbon::setLocale($locale);
        
        // Add to request
        $request->merge(['locale' => $locale]);
        
        $response = $next($request);
        
        // Add to response headers
        $response->headers->set('Content-Language', $locale);
        
        return $response;
    }
    
    protected function detectLocale(Request $request): string
    {
        // Priority order:
        // 1. URL parameter
        if ($request->has('locale')) {
            $locale = $request->get('locale');
            if ($this->isValidLocale($locale)) {
                session(['locale' => $locale]);
                return $locale;
            }
        }
        
        // 2. Session
        if (session()->has('locale')) {
            return session('locale');
        }
        
        // 3. User preference
        if ($user = $request->user()) {
            return $user->locale ?? config('app.locale');
        }
        
        // 4. Browser preference
        $locale = $request->getPreferredLanguage(config('app.supported_locales'));
        
        return $locale ?: config('app.locale');
    }
    
    protected function isValidLocale(string $locale): bool
    {
        return in_array($locale, config('app.supported_locales', ['en']));
    }
}
```

### Request ID Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssignRequestId
{
    public function handle(Request $request, Closure $next)
    {
        $requestId = $request->headers->get('X-Request-ID') ?: (string) Str::uuid();
        
        // Add to request
        $request->headers->set('X-Request-ID', $requestId);
        
        // Set in context for logging
        \Log::shareContext(['request_id' => $requestId]);
        
        $response = $next($request);
        
        // Add to response
        $response->headers->set('X-Request-ID', $requestId);
        
        return $response;
    }
}
```

## Response Middleware

### Response Compression

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompressResponse
{
    protected array $compressibleTypes = [
        'text/html',
        'text/css',
        'text/javascript',
        'application/javascript',
        'application/json',
        'application/xml',
    ];
    
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if ($this->shouldCompress($request, $response)) {
            $buffer = $response->getContent();
            $compressed = gzencode($buffer, 9);
            
            $response->setContent($compressed);
            $response->headers->set('Content-Encoding', 'gzip');
            $response->headers->set('Vary', 'Accept-Encoding');
            $response->headers->set('Content-Length', strlen($compressed));
        }
        
        return $response;
    }
    
    protected function shouldCompress(Request $request, $response): bool
    {
        if (!$request->header('Accept-Encoding') || 
            !str_contains($request->header('Accept-Encoding'), 'gzip')) {
            return false;
        }
        
        $contentType = $response->headers->get('Content-Type');
        
        foreach ($this->compressibleTypes as $type) {
            if (str_contains($contentType, $type)) {
                return true;
            }
        }
        
        return false;
    }
}
```

### Cache Control

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCacheHeaders
{
    protected array $cacheableRoutes = [
        'api.events.index' => 300,
        'api.events.show' => 600,
        'api.venues.index' => 3600,
    ];
    
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if ($request->method() === 'GET' && $response->isSuccessful()) {
            $routeName = $request->route()->getName();
            
            if (isset($this->cacheableRoutes[$routeName])) {
                $maxAge = $this->cacheableRoutes[$routeName];
                
                $response->headers->set(
                    'Cache-Control',
                    sprintf('public, max-age=%d', $maxAge)
                );
                
                // Add ETag
                $content = $response->getContent();
                $etag = md5($content);
                $response->headers->set('ETag', $etag);
            }
        }
        
        return $response;
    }
}
```

## Custom Middleware

### Feature Flag Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFeatureFlag
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        if (!$this->isFeatureEnabled($feature, $request->user())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This feature is not available.',
                ], 403);
            }
            
            abort(403, 'This feature is not available.');
        }
        
        return $next($request);
    }
    
    protected function isFeatureEnabled(string $feature, ?User $user): bool
    {
        // Global feature flags
        if (!config("features.{$feature}", false)) {
            return false;
        }
        
        // User-specific flags
        if ($user && $user->hasFeature($feature)) {
            return true;
        }
        
        // Tenant-specific flags
        if ($user && $user->tenant->hasFeature($feature)) {
            return true;
        }
        
        return false;
    }
}
```

### Maintenance Mode

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForMaintenanceMode
{
    protected array $except = [
        'api/health',
        'login',
        'admin/*',
    ];
    
    public function handle(Request $request, Closure $next)
    {
        if (app()->isDownForMaintenance() && !$this->shouldPassThrough($request)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Service is under maintenance. Please try again later.',
                ], 503);
            }
            
            return response()->view('maintenance', [], 503);
        }
        
        return $next($request);
    }
    
    protected function shouldPassThrough(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            
            if ($request->is($except)) {
                return true;
            }
        }
        
        // Allow admin users
        if ($request->user()?->isAdmin()) {
            return true;
        }
        
        return false;
    }
}
```

## Middleware Registration

### Kernel Configuration

```php
// app/Http/Kernel.php
protected $middleware = [
    \App\Http\Middleware\TrustProxies::class,
    \App\Http\Middleware\CheckForMaintenanceMode::class,
    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
    \App\Http\Middleware\TrimStrings::class,
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    \App\Http\Middleware\SecurityHeaders::class,
];

protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\SetLocale::class,
        \App\Http\Middleware\UpdateLastActivity::class,
    ],
    
    'api' => [
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\AssignRequestId::class,
    ],
];

protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'tenant' => \App\Http\Middleware\EnsureTenantContext::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \App\Http\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'feature' => \App\Http\Middleware\CheckFeatureFlag::class,
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

### Route Usage

```php
// In routes files
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::resource('events', EventController::class);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', AdminDashboardController::class);
});

Route::middleware(['throttle:uploads'])->group(function () {
    Route::post('upload', FileUploadController::class);
});

Route::middleware(['feature:api_v2'])->prefix('api/v2')->group(function () {
    // V2 API routes
});
```

## Testing Middleware

```php
test('tenant context middleware redirects users without tenant', function () {
    $user = User::factory()->create(['tenant_id' => null]);
    
    $response = $this->actingAs($user)
        ->get(route('portal.dashboard'));
    
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('tenant');
});

test('security headers are set correctly', function () {
    $response = $this->get('/');
    
    $response->assertHeader('X-Frame-Options', 'DENY');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
});

test('rate limiting works correctly', function () {
    $user = User::factory()->create();
    
    for ($i = 0; $i < 60; $i++) {
        $this->actingAs($user)->get('/api/events');
    }
    
    $response = $this->actingAs($user)->get('/api/events');
    
    $response->assertStatus(429);
    $response->assertHeader('X-RateLimit-Limit', '60');
    $response->assertHeader('X-RateLimit-Remaining', '0');
});
```

## Best Practices

1. **Keep middleware focused** - Each middleware should have a single responsibility
2. **Order matters** - Consider middleware execution order
3. **Use terminate method** - For post-response processing
4. **Cache middleware results** - When appropriate
5. **Test thoroughly** - Include edge cases
6. **Document parameters** - Explain what parameters do
7. **Handle errors gracefully** - Don't break the request flow
8. **Use middleware groups** - For common combinations
9. **Consider performance** - Middleware runs on every request
10. **Security first** - Always validate and sanitize 