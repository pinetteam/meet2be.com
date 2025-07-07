# Services Layer

## Overview

The Services Layer in Meet2Be encapsulates complex business logic, keeping controllers thin and models focused on data representation. Services promote code reusability, testability, and separation of concerns.

## Service Architecture Principles

### 1. Single Responsibility
Each service handles one specific area of business logic.

### 2. Dependency Injection
Services are injected into controllers, never instantiated directly.

### 3. Stateless Design
Services don't maintain state between method calls.

### 4. Type Safety
All methods have explicit return types and parameter types.

## Service Structure

### Basic Service Pattern

```php
namespace App\Services;

class UserService
{
    public function __construct(
        private EmailService $emailService,
        private EventDispatcher $events
    ) {}
    
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create($data);
            
            $this->emailService->sendWelcomeEmail($user);
            $this->events->dispatch(new UserCreated($user));
            
            return $user;
        });
    }
}
```

## Core Services

### DateTimeManager

Handles all datetime operations with timezone support.

```php
namespace App\Services\DateTime;

class DateTimeManager
{
    protected array $tenantCache = [];
    
    public function parse($datetime, ?string $tenantId = null): TenantDateTime
    {
        $tenantId ??= TenantService::getCurrentTenantId();
        $timezone = $this->getTenantTimezone($tenantId);
        
        return new TenantDateTime(
            Carbon::parse($datetime),
            $timezone,
            $tenantId
        );
    }
    
    public function now(?string $tenantId = null): TenantDateTime
    {
        return $this->parse('now', $tenantId);
    }
    
    protected function getTenantTimezone(string $tenantId): string
    {
        if (!isset($this->tenantCache[$tenantId])) {
            $this->loadTenantSettings($tenantId);
        }
        
        return $this->tenantCache[$tenantId]['timezone'];
    }
}
```

### TenantService

Manages tenant context and operations.

```php
namespace App\Services;

class TenantService
{
    protected static ?string $currentTenantId = null;
    
    public static function setCurrentTenant(?Tenant $tenant): void
    {
        self::$currentTenantId = $tenant?->id;
        
        // Clear tenant-specific caches
        Cache::tags(['tenant_' . self::$currentTenantId])->flush();
    }
    
    public static function getCurrentTenantId(): ?string
    {
        if (self::$currentTenantId) {
            return self::$currentTenantId;
        }
        
        if (auth()->check()) {
            self::$currentTenantId = auth()->user()->tenant_id;
            return self::$currentTenantId;
        }
        
        return null;
    }
    
    public function createTenant(array $data, User $owner): Tenant
    {
        return DB::transaction(function () use ($data, $owner) {
            $tenant = Tenant::create([
                ...$data,
                'owner_id' => $owner->id,
                'status' => 'active'
            ]);
            
            // Associate owner with tenant
            $owner->update(['tenant_id' => $tenant->id]);
            
            // Create default settings
            $this->createDefaultSettings($tenant);
            
            return $tenant;
        });
    }
}
```

## Service Patterns

### 1. Transaction Management

Services handle database transactions for data consistency.

```php
class OrderService
{
    public function createOrder(array $orderData, array $items): Order
    {
        return DB::transaction(function () use ($orderData, $items) {
            // Create order
            $order = Order::create($orderData);
            
            // Create order items
            foreach ($items as $item) {
                $order->items()->create($item);
            }
            
            // Update inventory
            $this->inventoryService->reserveItems($items);
            
            // Calculate totals
            $order->calculateTotals();
            
            return $order->fresh(['items']);
        });
    }
}
```

### 2. Event Dispatching

Services dispatch domain events for decoupled architecture.

```php
class PaymentService
{
    public function processPayment(Order $order, array $paymentData): Payment
    {
        $payment = DB::transaction(function () use ($order, $paymentData) {
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'method' => $paymentData['method'],
                'status' => 'pending'
            ]);
            
            // Process with payment gateway
            $response = $this->gateway->charge($paymentData);
            
            // Update payment status
            $payment->update([
                'status' => $response->successful() ? 'completed' : 'failed',
                'gateway_response' => $response->toArray()
            ]);
            
            return $payment;
        });
        
        // Dispatch events outside transaction
        if ($payment->isCompleted()) {
            event(new PaymentCompleted($payment));
        } else {
            event(new PaymentFailed($payment));
        }
        
        return $payment;
    }
}
```

### 3. External API Integration

Services encapsulate external API calls.

```php
class EmailService
{
    protected Mail $mailer;
    
    public function sendWelcomeEmail(User $user): void
    {
        $this->mailer->to($user->email)
            ->send(new WelcomeEmail($user));
        
        // Log email activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'email_sent',
            'description' => 'Welcome email sent'
        ]);
    }
    
    public function sendBulkEmail(Collection $users, Mailable $mailable): void
    {
        $users->chunk(100)->each(function ($chunk) use ($mailable) {
            dispatch(new SendBulkEmailJob($chunk, $mailable));
        });
    }
}
```

### 4. Caching Strategy

Services implement caching for performance.

```php
class SettingsService
{
    protected array $cache = [];
    
    public function get(string $key, $default = null)
    {
        $tenantId = TenantService::getCurrentTenantId();
        $cacheKey = "settings_{$tenantId}_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)
                ->where('tenant_id', TenantService::getCurrentTenantId())
                ->first();
                
            return $setting?->value ?? $default;
        });
    }
    
    public function set(string $key, $value): void
    {
        $tenantId = TenantService::getCurrentTenantId();
        
        Setting::updateOrCreate(
            ['key' => $key, 'tenant_id' => $tenantId],
            ['value' => $value]
        );
        
        // Clear cache
        Cache::forget("settings_{$tenantId}_{$key}");
    }
}
```

## Service Registration

### Service Provider

```php
namespace App\Providers;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Singleton services
        $this->app->singleton(DateTimeManager::class);
        $this->app->singleton(SettingsService::class);
        
        // Bind interfaces to implementations
        $this->app->bind(PaymentGatewayInterface::class, StripeGateway::class);
        
        // Contextual binding
        $this->app->when(OrderController::class)
            ->needs(NotificationService::class)
            ->give(function () {
                return new NotificationService('orders');
            });
    }
}
```

## Dependency Injection

### Constructor Injection

```php
class EventController extends Controller
{
    public function __construct(
        private EventService $eventService,
        private VenueService $venueService
    ) {}
    
    public function store(StoreEventRequest $request)
    {
        $event = $this->eventService->createEvent(
            $request->validated(),
            $request->venue_id
        );
        
        return redirect()
            ->route('portal.event.show', $event)
            ->with('success', 'Event created successfully');
    }
}
```

### Method Injection

```php
public function export(Request $request, ExportService $exportService)
{
    $export = $exportService->exportEvents(
        $request->get('format', 'csv')
    );
    
    return $export->download();
}
```

## Service Testing

### Unit Tests

```php
class UserServiceTest extends TestCase
{
    private UserService $service;
    private MockInterface $emailService;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->emailService = Mockery::mock(EmailService::class);
        $this->service = new UserService($this->emailService);
    }
    
    public function test_creates_user_and_sends_welcome_email()
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        
        $this->emailService
            ->shouldReceive('sendWelcomeEmail')
            ->once()
            ->with(Mockery::on(function ($user) {
                return $user->email === 'john@example.com';
            }));
        
        $user = $this->service->createUser($data);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', $data);
    }
}
```

### Integration Tests

```php
class PaymentServiceIntegrationTest extends TestCase
{
    public function test_processes_payment_successfully()
    {
        $order = Order::factory()->create(['total' => 100.00]);
        
        $paymentData = [
            'method' => 'credit_card',
            'card_token' => 'tok_visa'
        ];
        
        $service = app(PaymentService::class);
        $payment = $service->processPayment($order, $paymentData);
        
        $this->assertEquals('completed', $payment->status);
        $this->assertEquals(100.00, $payment->amount);
        
        Event::assertDispatched(PaymentCompleted::class);
    }
}
```

## Best Practices

### 1. Keep Services Focused

```php
// Good - Single responsibility
class InvoiceService
{
    public function generate(Order $order): Invoice { }
    public function send(Invoice $invoice): void { }
    public function markAsPaid(Invoice $invoice): void { }
}

// Bad - Too many responsibilities
class BillingService
{
    public function createInvoice() { }
    public function processPayment() { }
    public function sendReceipt() { }
    public function handleRefund() { }
    public function generateReport() { }
}
```

### 2. Use Type Declarations

```php
// Good - Clear types
public function calculateDiscount(Order $order, Coupon $coupon): Money
{
    return new Money($order->subtotal * $coupon->percentage / 100);
}

// Bad - Unclear types
public function calculateDiscount($order, $coupon)
{
    return $order->subtotal * $coupon->percentage / 100;
}
```

### 3. Handle Errors Gracefully

```php
class PaymentService
{
    public function charge(Order $order): PaymentResult
    {
        try {
            $response = $this->gateway->charge([
                'amount' => $order->total,
                'currency' => $order->currency,
                'source' => $order->payment_token
            ]);
            
            return PaymentResult::success($response->id);
            
        } catch (CardDeclinedException $e) {
            return PaymentResult::declined($e->getMessage());
            
        } catch (GatewayException $e) {
            Log::error('Payment gateway error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return PaymentResult::error('Payment processing failed');
        }
    }
}
```

### 4. Use Value Objects

```php
class PriceCalculationService
{
    public function calculate(
        Money $basePrice,
        TaxRate $taxRate,
        ?DiscountRate $discount = null
    ): PriceBreakdown {
        $subtotal = $basePrice;
        
        if ($discount) {
            $discountAmount = $subtotal->multiply($discount->percentage / 100);
            $subtotal = $subtotal->subtract($discountAmount);
        }
        
        $tax = $subtotal->multiply($taxRate->percentage / 100);
        $total = $subtotal->add($tax);
        
        return new PriceBreakdown(
            basePrice: $basePrice,
            discount: $discountAmount ?? Money::zero(),
            subtotal: $subtotal,
            tax: $tax,
            total: $total
        );
    }
}
```

## Common Service Patterns

### Query Services

For complex database queries:

```php
class ReportService
{
    public function getMonthlyRevenue(int $year, int $month): Collection
    {
        return DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->where('payments.status', 'completed')
            ->whereYear('payments.created_at', $year)
            ->whereMonth('payments.created_at', $month)
            ->where('orders.tenant_id', TenantService::getCurrentTenantId())
            ->groupBy(DB::raw('DATE(payments.created_at)'))
            ->select(
                DB::raw('DATE(payments.created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(payments.amount) as total')
            )
            ->get();
    }
}
```

### Notification Services

For sending notifications:

```php
class NotificationService
{
    public function notifyUser(User $user, string $type, array $data): void
    {
        // Database notification
        $user->notify(new DatabaseNotification($type, $data));
        
        // Email notification if enabled
        if ($user->wantsEmailNotification($type)) {
            dispatch(new SendEmailNotification($user, $type, $data));
        }
        
        // Push notification if enabled
        if ($user->hasPushToken()) {
            dispatch(new SendPushNotification($user, $type, $data));
        }
    }
}
```

### Validation Services

For complex validation logic:

```php
class EventValidationService
{
    public function canCreateEvent(User $user, array $data): ValidationResult
    {
        // Check user permissions
        if (!$user->can('create-events')) {
            return ValidationResult::error('No permission to create events');
        }
        
        // Check venue availability
        if (!$this->isVenueAvailable($data['venue_id'], $data['starts_at'], $data['ends_at'])) {
            return ValidationResult::error('Venue is not available at selected time');
        }
        
        // Check event limits
        if ($this->hasReachedEventLimit($user)) {
            return ValidationResult::error('Event limit reached for your plan');
        }
        
        return ValidationResult::success();
    }
}
```

## Anti-Patterns to Avoid

### 1. Fat Services
Don't put everything in one service:

```php
// Bad
class SuperService
{
    public function handleEverything() { /* 500 lines */ }
}

// Good
class OrderService { }
class PaymentService { }
class ShippingService { }
```

### 2. Direct Model Access
Services should use dependency injection:

```php
// Bad
public function getUser($id)
{
    return User::find($id);
}

// Good
public function __construct(private UserRepository $users) {}

public function getUser($id)
{
    return $this->users->find($id);
}
```

### 3. Business Logic in Controllers
Keep controllers thin:

```php
// Bad - Logic in controller
public function store(Request $request)
{
    $order = Order::create([...]);
    // 50 lines of business logic
}

// Good - Logic in service
public function store(Request $request, OrderService $service)
{
    $order = $service->create($request->validated());
    return redirect()->route('orders.show', $order);
}
``` 