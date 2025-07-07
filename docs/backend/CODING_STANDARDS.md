# Coding Standards

## Overview

This document outlines the coding standards and conventions for the Meet2Be backend. Following these standards ensures consistency, maintainability, and quality across the codebase.

## PHP Standards

### PSR Compliance
We follow PSR-12 coding standards with additional project-specific conventions.

### File Structure

```php
<?php

declare(strict_types=1);

namespace App\Services\DateTime;

use Carbon\Carbon;
use App\Models\Tenant\Tenant;
use App\Services\TenantService;

/**
 * Manages datetime operations with timezone support
 */
class DateTimeManager
{
    // Class implementation
}
```

### Naming Conventions

#### Classes
- Use PascalCase: `UserController`, `DateTimeManager`
- Be descriptive and specific
- Suffix with type: `Controller`, `Service`, `Request`

```php
// Good
class EventController extends Controller
class UserService
class StoreEventRequest extends FormRequest

// Bad
class Events  // Should be EventController
class UserMgr  // Avoid abbreviations
```

#### Methods
- Use camelCase: `getUserById`, `calculateTotal`
- Start with verb: `get`, `set`, `create`, `update`, `delete`
- Be descriptive about what the method does

```php
// Good
public function getUserByEmail(string $email): ?User
public function calculateOrderTotal(Order $order): Money
public function sendWelcomeEmail(User $user): void

// Bad
public function user($email)  // Not descriptive
public function calc($order)  // Avoid abbreviations
```

#### Variables
- Use camelCase: `$userName`, `$totalAmount`
- Be descriptive, avoid single letters except in loops
- Use meaningful names that explain the purpose

```php
// Good
$userEmail = $request->get('email');
$totalAmount = $order->calculateTotal();
$isActive = $user->isActive();

// Bad
$e = $request->get('email');  // Too short
$tmp = $order->calculateTotal();  // Not descriptive
$flag = $user->isActive();  // Vague
```

#### Constants
- Use UPPER_SNAKE_CASE: `MAX_ATTEMPTS`, `DEFAULT_TIMEOUT`
- Define at class level or in config files

```php
class PaymentService
{
    private const MAX_RETRY_ATTEMPTS = 3;
    private const DEFAULT_TIMEOUT = 30;
    private const SUPPORTED_CURRENCIES = ['USD', 'EUR', 'GBP'];
}
```

### Type Declarations

Always use strict types and declare parameter/return types:

```php
declare(strict_types=1);

class UserService
{
    public function createUser(array $data): User
    {
        return User::create($data);
    }
    
    public function findById(string $id): ?User
    {
        return User::find($id);
    }
    
    public function updateProfile(User $user, array $data): bool
    {
        return $user->update($data);
    }
}
```

### Method Structure

```php
class EventController extends Controller
{
    /**
     * Constructor with dependency injection
     */
    public function __construct(
        private EventService $eventService,
        private VenueService $venueService
    ) {}
    
    /**
     * Display a listing of events
     */
    public function index(Request $request): View
    {
        // 1. Validate/prepare input
        $filters = $request->validated();
        
        // 2. Business logic
        $events = $this->eventService->getFilteredEvents($filters);
        
        // 3. Return response
        return view('portal.event.index', compact('events'));
    }
}
```

## Laravel Conventions

### Controllers

#### Resourceful Controllers
Always use resource controllers with standard methods:

```php
class UserController extends Controller
{
    public function index() {}      // GET /users
    public function create() {}     // GET /users/create
    public function store() {}      // POST /users
    public function show() {}       // GET /users/{user}
    public function edit() {}       // GET /users/{user}/edit
    public function update() {}     // PUT/PATCH /users/{user}
    public function destroy() {}    // DELETE /users/{user}
}
```

#### Single Action Controllers
For non-CRUD operations, use single action controllers:

```php
class SendInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice): RedirectResponse
    {
        // Single responsibility
    }
}
```

### Models

#### Model Structure

```php
namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\TenantAware;
use App\Traits\HasDateTime;

class Event extends Model
{
    use HasUuids, TenantAware, HasDateTime;
    
    // Table name (if not following convention)
    protected $table = 'events';
    
    // Mass assignable attributes
    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'starts_at',
        'ends_at',
    ];
    
    // Hidden attributes
    protected $hidden = [
        'internal_notes',
    ];
    
    // Attribute casting
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'settings' => 'array',
        'is_published' => 'boolean',
    ];
    
    // Default values
    protected $attributes = [
        'status' => 'draft',
    ];
    
    // Relationships
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
    
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
    
    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }
    
    // Accessors & Mutators
    public function getIsUpcomingAttribute(): bool
    {
        return $this->starts_at->isFuture();
    }
    
    // Business Logic Methods
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
```

### Form Requests

```php
namespace App\Http\Requests\Portal\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Event::class);
    }
    
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'venue_id' => 'required|uuid|exists:event_venues,id',
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'required|date|after:starts_at',
        ];
    }
    
    public function messages(): array
    {
        return [
            'starts_at.after' => 'The event must start in the future.',
            'ends_at.after' => 'The event must end after it starts.',
        ];
    }
    
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->title),
        ]);
    }
}
```

### Services

```php
namespace App\Services;

use App\Models\Event\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function __construct(
        private NotificationService $notifications,
        private EmailService $emailService
    ) {}
    
    public function createEvent(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            $event = Event::create($data);
            
            $this->notifications->notifyEventCreated($event);
            
            return $event;
        });
    }
}
```

## Database Conventions

### Migrations

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'starts_at']);
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
```

### Eloquent Queries

```php
// Good - Efficient queries
$events = Event::with(['venue', 'organizer'])
    ->published()
    ->upcoming()
    ->orderBy('starts_at')
    ->paginate(20);

// Bad - N+1 problem
$events = Event::all();
foreach ($events as $event) {
    echo $event->venue->name; // N+1 query
}

// Good - Chunking for large datasets
Event::chunk(100, function ($events) {
    foreach ($events as $event) {
        // Process event
    }
});
```

## Error Handling

### Exceptions

```php
namespace App\Exceptions;

use Exception;

class EventCapacityExceededException extends Exception
{
    protected $message = 'Event capacity has been exceeded';
    
    public function __construct(
        public Event $event,
        public int $requestedSeats
    ) {
        parent::__construct($this->message);
    }
    
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->message,
                'event_id' => $this->event->id,
                'available_seats' => $this->event->available_seats,
            ], 422);
        }
        
        return back()->withErrors([
            'seats' => $this->message
        ]);
    }
}
```

### Try-Catch Blocks

```php
public function processPayment(Order $order): PaymentResult
{
    try {
        $result = $this->paymentGateway->charge($order);
        
        $this->logPaymentSuccess($order, $result);
        
        return PaymentResult::success($result);
        
    } catch (PaymentException $e) {
        $this->logPaymentFailure($order, $e);
        
        return PaymentResult::failure($e->getMessage());
        
    } catch (\Exception $e) {
        Log::error('Unexpected payment error', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
        ]);
        
        throw $e;
    }
}
```

## Testing Standards

### Test Structure

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Event\Event;

class EventManagementTest extends TestCase
{
    private User $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
    }
    
    /** @test */
    public function user_can_create_event(): void
    {
        // Arrange
        $eventData = [
            'title' => 'Test Event',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDays(2),
        ];
        
        // Act
        $response = $this->actingAs($this->user)
            ->post(route('portal.event.store'), $eventData);
        
        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'tenant_id' => $this->user->tenant_id,
        ]);
    }
}
```

## Documentation

### PHPDoc Standards

```php
/**
 * Calculate the total price including tax and discounts
 *
 * @param Order $order The order to calculate
 * @param Coupon|null $coupon Optional coupon to apply
 * @return Money The calculated total
 * @throws InvalidCouponException If coupon is invalid
 */
public function calculateTotal(Order $order, ?Coupon $coupon = null): Money
{
    // Implementation
}
```

### Inline Comments

```php
// Good - Explains why, not what
// Check cache before expensive calculation
$cached = Cache::get($key);

// Bad - Obvious comment
// Set user name
$user->name = $request->name;

// Good - Complex logic explanation
// We need to check both conditions because the user might have
// partial access through a shared team membership
if ($user->ownsTeam($team) || $user->hasTeamPermission($team, 'view')) {
    // Allow access
}
```

## Code Organization

### Directory Structure
- One class per file
- Namespace matches directory structure
- Group related classes in subdirectories

### Import Statements
```php
// Order: PHP core, Framework, Third-party, Application
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Stripe\Stripe;
use App\Models\User\User;
use App\Services\PaymentService;
```

### Method Ordering
1. Constants
2. Properties
3. Constructor
4. Public methods
5. Protected methods
6. Private methods
7. Magic methods

## Best Practices

### DRY (Don't Repeat Yourself)
```php
// Bad - Repeated logic
public function calculatePrice($quantity, $price) {
    return $quantity * $price * 1.2; // With tax
}

public function calculateDiscountedPrice($quantity, $price, $discount) {
    return $quantity * $price * 1.2 * (1 - $discount);
}

// Good - Reusable method
public function calculatePrice($quantity, $price, $discount = 0) {
    $subtotal = $quantity * $price;
    $withTax = $subtotal * 1.2;
    return $withTax * (1 - $discount);
}
```

### SOLID Principles
- **S**ingle Responsibility
- **O**pen/Closed
- **L**iskov Substitution
- **I**nterface Segregation
- **D**ependency Inversion

### Security
- Always validate input
- Use prepared statements (Eloquent does this)
- Sanitize output
- Check permissions
- Log security events

### Performance
- Use eager loading
- Cache expensive operations
- Optimize database queries
- Use queues for heavy tasks

## Tools & Automation

### Code Style Fixing
```bash
# PHP CS Fixer
./vendor/bin/php-cs-fixer fix

# Laravel Pint
./vendor/bin/pint
```

### Static Analysis
```bash
# PHPStan
./vendor/bin/phpstan analyse

# Larastan (Laravel-specific)
./vendor/bin/phpstan analyse --level=5
```

### Pre-commit Hooks
```json
{
    "scripts": {
        "pre-commit": [
            "vendor/bin/pint",
            "vendor/bin/phpstan analyse"
        ]
    }
}
``` 