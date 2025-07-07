# Security Guidelines

## Overview

Security is paramount in Meet2Be. This document outlines security best practices, implementation patterns, and guidelines to protect against common vulnerabilities.

## Authentication Security

### Password Requirements

```php
namespace App\Rules;

class SecurePassword implements Rule
{
    public function passes($attribute, $value): bool
    {
        // Minimum 12 characters
        if (strlen($value) < 12) return false;
        
        // Contains uppercase, lowercase, number, and special character
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $value)) {
            return false;
        }
        
        // Not in common passwords list
        if ($this->isCommonPassword($value)) return false;
        
        // Not similar to user data
        if ($this->isSimilarToUserData($value)) return false;
        
        return true;
    }
}
```

### Session Security

```php
// config/session.php
return [
    'secure' => env('SESSION_SECURE_COOKIE', true), // HTTPS only
    'http_only' => true, // No JavaScript access
    'same_site' => 'lax', // CSRF protection
    'encrypt' => true, // Encrypted session data
    'lifetime' => 120, // 2-hour timeout
];
```

### Two-Factor Authentication

```php
class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $user = $request->user();
        
        // Generate secret
        $secret = Google2FA::generateSecretKey();
        
        // Encrypt and store
        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_enabled' => false, // Not enabled until verified
        ]);
        
        // Generate QR code
        $qrCode = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );
        
        return view('auth.two-factor.enable', compact('qrCode', 'secret'));
    }
    
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);
        
        $user = $request->user();
        $valid = Google2FA::verifyKey(
            decrypt($user->two_factor_secret),
            $request->code
        );
        
        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }
        
        $user->update(['two_factor_enabled' => true]);
        
        // Generate recovery codes
        $recoveryCodes = Collection::times(8, fn () => Str::random(10))->toArray();
        $user->update(['two_factor_recovery_codes' => encrypt($recoveryCodes)]);
        
        return redirect()->route('profile')->with('recovery_codes', $recoveryCodes);
    }
}
```

## Authorization Security

### Policy-Based Authorization

```php
class EventPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view event list
    }
    
    public function view(User $user, Event $event): bool
    {
        // Tenant isolation
        if ($user->tenant_id !== $event->tenant_id) {
            return false;
        }
        
        // Check visibility
        if ($event->visibility === 'private' && !$event->isAttendee($user)) {
            return false;
        }
        
        return true;
    }
    
    public function update(User $user, Event $event): bool
    {
        // Only organizer can update
        return $user->id === $event->created_by
            && $user->tenant_id === $event->tenant_id
            && $event->status !== 'cancelled';
    }
}
```

### Role-Based Access Control

```php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user() || !$request->user()->hasAnyRole($roles)) {
            abort(403, 'Unauthorized action.');
        }
        
        return $next($request);
    }
}

// Usage in routes
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::resource('users', UserController::class);
});
```

## Input Validation & Sanitization

### XSS Prevention

```php
// Always escape output in Blade
{{ $user->name }}  // Escaped
{!! $user->bio !!} // Not escaped - use only for trusted HTML

// Purify HTML input
use HTMLPurifier;

class UpdateProfileRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('bio')) {
            $purifier = new HTMLPurifier();
            $this->merge([
                'bio' => $purifier->purify($this->bio),
            ]);
        }
    }
}
```

### SQL Injection Prevention

```php
// ✅ Safe - Using Eloquent
$users = User::where('email', $email)->get();

// ✅ Safe - Using query builder with bindings
$users = DB::table('users')
    ->where('email', '=', $email)
    ->get();

// ✅ Safe - Raw queries with bindings
$users = DB::select('SELECT * FROM users WHERE email = ?', [$email]);

// ❌ NEVER do this
$users = DB::select("SELECT * FROM users WHERE email = '$email'");

// For complex queries, use bindings
$results = DB::select('
    SELECT u.*, COUNT(e.id) as event_count
    FROM users u
    LEFT JOIN events e ON u.id = e.created_by
    WHERE u.tenant_id = :tenant_id
    AND u.created_at > :date
    GROUP BY u.id
', [
    'tenant_id' => $tenantId,
    'date' => $date->toDateTimeString(),
]);
```

### Mass Assignment Protection

```php
class User extends Model
{
    // Whitelist approach (recommended)
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
    
    // Blacklist approach
    protected $guarded = [
        'id',
        'tenant_id',
        'is_admin',
        'email_verified_at',
    ];
}

// In controller
public function update(UpdateUserRequest $request, User $user)
{
    // Only validated data is used
    $user->update($request->validated());
}
```

## CSRF Protection

### Blade Forms

```blade
<form method="POST" action="{{ route('events.store') }}">
    @csrf  {{-- Always include CSRF token --}}
    
    <input type="text" name="title">
    <button type="submit">Create</button>
</form>
```

### AJAX Requests

```javascript
// Setup CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Or with fetch
fetch('/api/events', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
});
```

### Excluding Routes

```php
// app/Http/Middleware/VerifyCsrfToken.php
class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'webhook/*',  // External webhooks
        'api/stripe/webhook',  // Payment webhooks
    ];
}
```

## File Upload Security

### Validation

```php
class UploadAvatarRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048', // 2MB limit
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
                new VirusScanRule(), // Custom virus scanning
            ],
        ];
    }
}
```

### Secure Storage

```php
class AvatarController extends Controller
{
    public function store(UploadAvatarRequest $request)
    {
        $file = $request->file('avatar');
        
        // Generate secure filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store outside public directory
        $path = $file->storeAs('avatars', $filename, 'private');
        
        // Save path to database
        auth()->user()->update(['avatar_path' => $path]);
        
        return back()->with('success', 'Avatar uploaded successfully.');
    }
    
    public function show(User $user)
    {
        // Check authorization
        if (!Gate::allows('view-avatar', $user)) {
            abort(403);
        }
        
        // Serve file securely
        return Storage::disk('private')->download($user->avatar_path);
    }
}
```

### File Type Verification

```php
class FileTypeValidator
{
    public function validate(UploadedFile $file): bool
    {
        // Check MIME type
        $mimeType = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
        
        if (!in_array($mimeType, $allowedMimes)) {
            return false;
        }
        
        // Verify file content matches extension
        $extension = $file->getClientOriginalExtension();
        $detectedExtension = $this->detectExtension($file->getRealPath());
        
        return $extension === $detectedExtension;
    }
    
    private function detectExtension(string $path): ?string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);
        
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
        ];
        
        return $mimeToExt[$mimeType] ?? null;
    }
}
```

## API Security

### Rate Limiting

```php
// app/Providers/RouteServiceProvider.php
protected function configureRateLimiting()
{
    // General API limit
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
    
    // Strict limit for sensitive operations
    RateLimiter::for('auth', function (Request $request) {
        return Limit::perMinute(5)->by($request->ip());
    });
    
    // Custom response
    RateLimiter::for('api')->response(function (Request $request, array $headers) {
        return response()->json([
            'message' => 'Too many requests. Please slow down.',
        ], 429, $headers);
    });
}
```

### API Token Security

```php
class ApiTokenController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'array',
            'abilities.*' => 'string|in:read,write,delete',
        ]);
        
        $token = $request->user()->createToken(
            name: $request->name,
            abilities: $request->abilities ?? ['read'],
            expiresAt: now()->addYear()
        );
        
        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => now()->addYear(),
        ]);
    }
    
    public function revoke(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();
        
        return response()->json(['message' => 'Token revoked successfully.']);
    }
}
```

## Encryption & Hashing

### Sensitive Data Encryption

```php
class User extends Model
{
    // Automatic encryption/decryption
    protected $casts = [
        'ssn' => 'encrypted',
        'credit_card' => 'encrypted:array',
    ];
    
    // Manual encryption
    public function setApiKeyAttribute($value): void
    {
        $this->attributes['api_key'] = encrypt($value);
    }
    
    public function getApiKeyAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }
}
```

### Password Hashing

```php
// Creating user
$user = User::create([
    'email' => $request->email,
    'password' => Hash::make($request->password), // Bcrypt by default
]);

// Verifying password
if (Hash::check($request->password, $user->password)) {
    // Password is correct
}

// Rehashing if needed
if (Hash::needsRehash($user->password)) {
    $user->update(['password' => Hash::make($request->password)]);
}
```

## Security Headers

### Middleware Implementation

```php
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Content-Security-Policy', $this->csp());
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        return $response;
    }
    
    private function csp(): string
    {
        return "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self';";
    }
}
```

## Logging & Monitoring

### Security Event Logging

```php
class SecurityLogger
{
    public function logFailedLogin(string $email, string $ip): void
    {
        Log::channel('security')->warning('Failed login attempt', [
            'email' => $email,
            'ip' => $ip,
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }
    
    public function logSuspiciousActivity(User $user, string $activity): void
    {
        Log::channel('security')->alert('Suspicious activity detected', [
            'user_id' => $user->id,
            'activity' => $activity,
            'ip' => request()->ip(),
            'timestamp' => now(),
        ]);
        
        // Notify security team
        Notification::route('mail', config('security.alert_email'))
            ->notify(new SuspiciousActivityNotification($user, $activity));
    }
}
```

### Audit Trail

```php
trait Auditable
{
    protected static function bootAuditable(): void
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
    
    protected function logAudit(string $action, array $changes = []): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => static::class,
            'auditable_id' => $this->id,
            'action' => $action,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
```

## Security Checklist

### Development
- [ ] Use HTTPS in all environments
- [ ] Enable debug mode only in local
- [ ] Use strong session configuration
- [ ] Implement CSRF protection
- [ ] Validate all user input
- [ ] Escape all output
- [ ] Use parameterized queries
- [ ] Implement rate limiting
- [ ] Add security headers
- [ ] Log security events

### Authentication
- [ ] Enforce strong passwords
- [ ] Implement account lockout
- [ ] Use secure session management
- [ ] Add two-factor authentication
- [ ] Log authentication events
- [ ] Implement password reset securely

### Authorization
- [ ] Use policy-based authorization
- [ ] Implement role-based access
- [ ] Check tenant isolation
- [ ] Validate resource ownership
- [ ] Log authorization failures

### Data Protection
- [ ] Encrypt sensitive data
- [ ] Hash passwords properly
- [ ] Use HTTPS for data transmission
- [ ] Implement secure file storage
- [ ] Regular security audits
- [ ] Backup encryption keys

## Incident Response

### Security Breach Protocol

1. **Immediate Actions**
   - Isolate affected systems
   - Reset compromised credentials
   - Enable emergency mode
   - Notify security team

2. **Investigation**
   - Review security logs
   - Identify breach vector
   - Assess data exposure
   - Document timeline

3. **Remediation**
   - Patch vulnerabilities
   - Update security measures
   - Reset user sessions
   - Force password resets

4. **Communication**
   - Notify affected users
   - Report to authorities
   - Update stakeholders
   - Public disclosure (if required) 