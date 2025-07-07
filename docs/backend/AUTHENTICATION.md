# Authentication & Authorization

## Overview

Meet2Be implements a robust authentication and authorization system with multi-tenancy support, role-based access control (RBAC), and comprehensive security features.

## Authentication Architecture

### Session-Based Authentication

We use Laravel's built-in session authentication for web requests:

```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User\User::class,
    ],
],
```

### User Model

```php
namespace App\Models\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\TenantAware;
use App\Traits\HasDateTime;

class User extends Authenticatable
{
    use HasUuids, TenantAware, HasDateTime;
    
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'timezone',
        'locale',
        'is_active',
        'last_login_at',
        'last_activity_at',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function isActive(): bool
    {
        return $this->is_active && $this->tenant->is_active;
    }
}
```

## Login Process

### LoginController

```php
namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}
    
    public function show()
    {
        return view('site.auth.login');
    }
    
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        
        // Attempt authentication
        if (!$this->authService->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('auth.failed')]);
        }
        
        // Regenerate session
        $request->session()->regenerate();
        
        // Update user login info
        $this->authService->recordLogin(Auth::user());
        
        return redirect()->intended(route('portal.dashboard'));
    }
}
```

### LoginRequest Validation

```php
namespace App\Http\Requests\Site\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'remember' => 'boolean',
        ];
    }
    
    public function throttleKey(): string
    {
        return strtolower($this->input('email')) . '|' . $this->ip();
    }
}
```

### AuthService

```php
namespace App\Services;

use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function attempt(array $credentials, bool $remember = false): bool
    {
        // Find user by email
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return false;
        }
        
        // Check if user and tenant are active
        if (!$user->isActive()) {
            return false;
        }
        
        // Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            return false;
        }
        
        // Login user
        Auth::login($user, $remember);
        
        return true;
    }
    
    public function recordLogin(User $user): void
    {
        $user->update([
            'last_login_at' => now(),
            'last_activity_at' => now(),
        ]);
        
        // Log authentication event
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip()])
            ->log('User logged in');
    }
}
```

## Middleware

### Authenticate Middleware

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

### EnsureTenantContext Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantService;

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
                ->withErrors(['tenant' => 'No tenant associated with user.']);
        }
        
        // Validate tenant is active
        if (!TenantService::validateTenant($user->tenant_id)) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['tenant' => 'Tenant access has been suspended.']);
        }
        
        // Set tenant context
        TenantService::setCurrentTenant($user->tenant);
        
        return $next($request);
    }
}
```

### UpdateLastActivity Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update activity every 5 minutes
            if (!$user->last_activity_at || $user->last_activity_at->lt(now()->subMinutes(5))) {
                $user->update(['last_activity_at' => now()]);
            }
        }
        
        return $next($request);
    }
}
```

## Authorization

### Role-Based Access Control (RBAC)

Future implementation for roles and permissions:

```php
// Role Model
namespace App\Models\User;

class Role extends Model
{
    use HasUuids;
    
    protected $fillable = ['name', 'display_name', 'description'];
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

// Permission Model
class Permission extends Model
{
    use HasUuids;
    
    protected $fillable = ['name', 'display_name', 'description', 'group'];
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
```

### User Permissions

```php
// In User Model
public function roles()
{
    return $this->belongsToMany(Role::class);
}

public function hasRole(string $role): bool
{
    return $this->roles()->where('name', $role)->exists();
}

public function hasPermission(string $permission): bool
{
    return $this->roles()
        ->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })
        ->exists();
}

public function can($ability, $arguments = [])
{
    // Check Laravel policies first
    if (parent::can($ability, $arguments)) {
        return true;
    }
    
    // Check RBAC permissions
    return $this->hasPermission($ability);
}
```

### Policies

Resource-based authorization:

```php
namespace App\Policies;

use App\Models\User\User;
use App\Models\Event\Event;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view events list
    }
    
    public function view(User $user, Event $event): bool
    {
        // User can only view events from their tenant
        return $user->tenant_id === $event->tenant_id;
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('events.create');
    }
    
    public function update(User $user, Event $event): bool
    {
        return $user->tenant_id === $event->tenant_id 
            && $user->hasPermission('events.update');
    }
    
    public function delete(User $user, Event $event): bool
    {
        return $user->tenant_id === $event->tenant_id 
            && $user->hasPermission('events.delete');
    }
}
```

### Registering Policies

```php
// app/Providers/AuthServiceProvider.php
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class,
        User::class => UserPolicy::class,
    ];
    
    public function boot(): void
    {
        $this->registerPolicies();
        
        // Register gates
        Gate::define('access-admin', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}
```

## Password Security

### Password Validation Rules

```php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SecurePassword implements Rule
{
    protected array $errors = [];
    
    public function passes($attribute, $value): bool
    {
        $this->errors = [];
        
        if (strlen($value) < 12) {
            $this->errors[] = 'Password must be at least 12 characters long.';
        }
        
        if (!preg_match('/[A-Z]/', $value)) {
            $this->errors[] = 'Password must contain at least one uppercase letter.';
        }
        
        if (!preg_match('/[a-z]/', $value)) {
            $this->errors[] = 'Password must contain at least one lowercase letter.';
        }
        
        if (!preg_match('/[0-9]/', $value)) {
            $this->errors[] = 'Password must contain at least one number.';
        }
        
        if (!preg_match('/[@$!%*?&]/', $value)) {
            $this->errors[] = 'Password must contain at least one special character.';
        }
        
        // Check common passwords
        if ($this->isCommonPassword($value)) {
            $this->errors[] = 'This password is too common. Please choose a more unique password.';
        }
        
        return empty($this->errors);
    }
    
    public function message(): string
    {
        return implode(' ', $this->errors);
    }
    
    protected function isCommonPassword(string $password): bool
    {
        $common = ['password123', 'admin123', 'welcome123', '12345678'];
        return in_array(strtolower($password), $common);
    }
}
```

### Password Reset

```php
namespace App\Http\Controllers\Site\Auth;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('site.auth.forgot-password');
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
    
    public function showResetForm(Request $request, $token)
    {
        return view('site.auth.reset-password', ['token' => $token]);
    }
    
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', new SecurePassword],
        ]);
        
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
                
                event(new PasswordReset($user));
            }
        );
        
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
```

## Session Management

### Session Configuration

```php
// config/session.php
return [
    'driver' => env('SESSION_DRIVER', 'redis'),
    'lifetime' => env('SESSION_LIFETIME', 120), // 2 hours
    'expire_on_close' => false,
    'encrypt' => true,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', 'meet2be_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
    'same_site' => 'lax',
];
```

### Concurrent Session Management

```php
trait ManagesSessions
{
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
    
    public function invalidateOtherSessions(): void
    {
        $currentSessionId = session()->getId();
        
        $this->sessions()
            ->where('id', '!=', $currentSessionId)
            ->delete();
    }
    
    public function logoutOtherDevices(): void
    {
        Auth::logoutOtherDevices(request('password'));
    }
}
```

## Security Features

### Account Lockout

```php
trait LockableAccount
{
    public function incrementLoginAttempts(): void
    {
        $this->increment('failed_login_attempts');
        $this->update(['last_failed_login_at' => now()]);
        
        if ($this->failed_login_attempts >= 5) {
            $this->lockAccount();
        }
    }
    
    public function lockAccount(): void
    {
        $this->update([
            'locked_at' => now(),
            'locked_until' => now()->addMinutes(30)
        ]);
    }
    
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }
    
    public function resetLoginAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'last_failed_login_at' => null
        ]);
    }
}
```

### Two-Factor Authentication (Future)

```php
trait TwoFactorAuthenticatable
{
    public function enableTwoFactor(): void
    {
        $this->update([
            'two_factor_secret' => encrypt(Google2FA::generateSecretKey()),
            'two_factor_enabled' => true
        ]);
    }
    
    public function disableTwoFactor(): void
    {
        $this->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_recovery_codes' => null
        ]);
    }
    
    public function verifyTwoFactorCode(string $code): bool
    {
        return Google2FA::verifyKey(
            decrypt($this->two_factor_secret),
            $code
        );
    }
}
```

## API Authentication (Future)

### Sanctum Setup

```php
// For future API development
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasUuids, TenantAware;
    
    public function createApiToken(string $name, array $abilities = ['*']): string
    {
        return $this->createToken($name, $abilities)->plainTextToken;
    }
}
```

## Testing Authentication

### Unit Tests

```php
class AuthenticationTest extends TestCase
{
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123!')
        ]);
        
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123!'
        ]);
        
        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
    
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create();
        
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);
        
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
    
    public function test_inactive_user_cannot_login()
    {
        $user = User::factory()->create([
            'is_active' => false,
            'password' => Hash::make('password123!')
        ]);
        
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123!'
        ]);
        
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
```

## Security Best Practices

1. **Always hash passwords** using bcrypt or argon2
2. **Use HTTPS** for all authentication pages
3. **Implement rate limiting** on login attempts
4. **Validate session** on each request
5. **Log authentication events** for audit trails
6. **Use secure session cookies** with httpOnly and secure flags
7. **Implement CSRF protection** on all forms
8. **Regular security audits** of authentication flow 