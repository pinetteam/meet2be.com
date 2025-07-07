# Testing Strategy

## Overview

Meet2Be follows a comprehensive testing strategy using Pest PHP for testing. Our approach emphasizes test-driven development (TDD), high code coverage, and maintainable test suites.

## Testing Stack

- **Pest PHP**: Modern testing framework built on PHPUnit
- **Laravel Testing**: Built-in testing utilities
- **Mockery**: Mocking framework
- **Faker**: Test data generation
- **Database Factories**: Model factories for test data

## Test Types

### 1. Unit Tests
Test individual components in isolation.

```php
// tests/Unit/Services/DateTimeManagerTest.php
use App\Services\DateTime\DateTimeManager;
use App\Models\Tenant\Tenant;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create([
        'timezone' => 'America/New_York',
        'locale' => 'en',
        'date_format' => 'Y-m-d',
    ]);
    
    $this->manager = app(DateTimeManager::class);
});

test('parses datetime with tenant timezone', function () {
    $utcTime = '2025-01-15 12:00:00';
    
    $datetime = $this->manager->parse($utcTime, $this->tenant->id);
    
    expect($datetime->getTimezone())->toBe('America/New_York');
    expect($datetime->format('Y-m-d H:i:s'))->toBe('2025-01-15 07:00:00');
});

test('formats date according to tenant locale', function () {
    $datetime = $this->manager->parse('2025-01-15', $this->tenant->id);
    
    expect($datetime->date())->toBe('Jan 15, 2025');
});
```

### 2. Feature Tests
Test complete features with HTTP requests.

```php
// tests/Feature/Portal/EventManagementTest.php
use App\Models\User\User;
use App\Models\Event\Event;
use App\Models\Event\Venue\Venue;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->venue = Venue::factory()->for($this->user->tenant)->create();
});

test('user can create event', function () {
    $eventData = [
        'title' => 'Test Event',
        'description' => 'Event description',
        'venue_id' => $this->venue->id,
        'starts_at' => now()->addDay()->format('Y-m-d H:i'),
        'ends_at' => now()->addDays(2)->format('Y-m-d H:i'),
    ];
    
    $response = $this->actingAs($this->user)
        ->post(route('portal.event.store'), $eventData);
    
    $response->assertRedirect();
    
    $this->assertDatabaseHas('events', [
        'title' => 'Test Event',
        'tenant_id' => $this->user->tenant_id,
        'venue_id' => $this->venue->id,
    ]);
});

test('user cannot access other tenant events', function () {
    $otherTenantEvent = Event::factory()->create();
    
    $response = $this->actingAs($this->user)
        ->get(route('portal.event.show', $otherTenantEvent));
    
    $response->assertNotFound();
});

test('validation errors are shown for invalid data', function () {
    $response = $this->actingAs($this->user)
        ->post(route('portal.event.store'), [
            'title' => '', // Required field
        ]);
    
    $response->assertSessionHasErrors(['title']);
});
```

### 3. Integration Tests
Test interaction between multiple components.

```php
// tests/Integration/TenantServiceTest.php
use App\Services\TenantService;
use App\Models\Tenant\Tenant;
use App\Models\User\User;

test('creating tenant sets up all required data', function () {
    $owner = User::factory()->create();
    
    $tenantData = [
        'name' => 'Test Company',
        'code' => 'test-company',
        'timezone' => 'Europe/London',
    ];
    
    $service = app(TenantService::class);
    $tenant = $service->createTenant($tenantData, $owner);
    
    expect($tenant)->toBeInstanceOf(Tenant::class);
    expect($tenant->owner_id)->toBe($owner->id);
    expect($owner->fresh()->tenant_id)->toBe($tenant->id);
    
    $this->assertDatabaseHas('tenants', [
        'name' => 'Test Company',
        'code' => 'test-company',
    ]);
});
```

## Test Organization

### Directory Structure

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   ├── LogoutTest.php
│   │   └── PasswordResetTest.php
│   ├── Portal/
│   │   ├── DashboardTest.php
│   │   ├── EventManagementTest.php
│   │   └── UserManagementTest.php
│   └── Api/
│       └── EventApiTest.php
├── Unit/
│   ├── Models/
│   │   ├── EventTest.php
│   │   └── UserTest.php
│   ├── Services/
│   │   ├── DateTimeManagerTest.php
│   │   └── TenantServiceTest.php
│   └── Rules/
│       └── SecurePasswordTest.php
├── Integration/
│   ├── MultiTenancyTest.php
│   └── DateTimeSystemTest.php
├── Pest.php
└── TestCase.php
```

### Base Test Cases

```php
// tests/TestCase.php
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Tenant\Tenant;
use App\Services\TenantService;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected Tenant $tenant;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create default tenant for tests
        $this->tenant = Tenant::factory()->create();
        TenantService::setCurrentTenant($this->tenant);
    }
}
```

## Testing Patterns

### Arrange-Act-Assert (AAA)

```php
test('event can be published', function () {
    // Arrange
    $event = Event::factory()->create(['status' => 'draft']);
    
    // Act
    $event->publish();
    
    // Assert
    expect($event->status)->toBe('published');
    expect($event->published_at)->not->toBeNull();
});
```

### Given-When-Then (BDD Style)

```php
test('given an event with limited capacity, when capacity is reached, then registration is closed', function () {
    // Given
    $event = Event::factory()->create([
        'max_attendees' => 2,
        'current_attendees' => 2,
    ]);
    
    // When
    $canRegister = $event->hasCapacity();
    
    // Then
    expect($canRegister)->toBeFalse();
});
```

## Database Testing

### Database Transactions

```php
// Pest.php
uses(Tests\TestCase::class)
    ->afterEach(fn () => $this->artisan('migrate:fresh'))
    ->in('Feature', 'Integration');
```

### Factories

```php
// database/factories/Event/EventFactory.php
namespace Database\Factories\Event;

use App\Models\Event\Event;
use App\Models\Event\Venue\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;
    
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'venue_id' => Venue::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'starts_at' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'ends_at' => function (array $attributes) {
                return Carbon::parse($attributes['starts_at'])->addHours(2);
            },
            'timezone' => 'UTC',
            'status' => 'published',
            'max_attendees' => $this->faker->optional()->numberBetween(10, 100),
        ];
    }
    
    public function draft(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
    
    public function withUnlimitedCapacity(): self
    {
        return $this->state(fn (array $attributes) => [
            'max_attendees' => null,
        ]);
    }
}
```

### Database Assertions

```php
test('creates user with correct attributes', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ];
    
    $user = User::factory()->create($userData);
    
    $this->assertDatabaseHas('users', $userData);
    $this->assertDatabaseCount('users', 1);
});

test('soft deletes record', function () {
    $event = Event::factory()->create();
    
    $event->delete();
    
    $this->assertSoftDeleted($event);
    $this->assertDatabaseMissing('events', [
        'id' => $event->id,
        'deleted_at' => null,
    ]);
});
```

## Mocking

### Service Mocking

```php
use App\Services\EmailService;
use Mockery;

test('sends welcome email when user is created', function () {
    $emailService = Mockery::mock(EmailService::class);
    $emailService->shouldReceive('sendWelcomeEmail')
        ->once()
        ->with(Mockery::on(function ($user) {
            return $user->email === 'test@example.com';
        }));
    
    $this->app->instance(EmailService::class, $emailService);
    
    $user = User::factory()->create(['email' => 'test@example.com']);
});
```

### Partial Mocks

```php
test('calculates total with tax', function () {
    $service = Mockery::mock(PaymentService::class)->makePartial();
    $service->shouldReceive('getTaxRate')->andReturn(0.2);
    
    $total = $service->calculateTotal(100);
    
    expect($total)->toBe(120.0);
});
```

## Testing Multi-Tenancy

### Tenant Isolation Tests

```php
test('tenant data is isolated', function () {
    $tenant1 = Tenant::factory()->create();
    $tenant2 = Tenant::factory()->create();
    
    $user1 = User::factory()->for($tenant1)->create();
    $user2 = User::factory()->for($tenant2)->create();
    
    Event::factory()->count(3)->for($tenant1)->create();
    Event::factory()->count(2)->for($tenant2)->create();
    
    $this->actingAs($user1);
    
    $events = Event::all();
    
    expect($events)->toHaveCount(3);
    expect($events->pluck('tenant_id')->unique()->toArray())->toBe([$tenant1->id]);
});
```

### Cross-Tenant Security Tests

```php
test('user cannot modify other tenant resources', function () {
    $otherTenantEvent = Event::factory()->create();
    
    $response = $this->actingAs($this->user)
        ->put(route('portal.event.update', $otherTenantEvent), [
            'title' => 'Hacked Title',
        ]);
    
    $response->assertNotFound();
    
    expect($otherTenantEvent->fresh()->title)->not->toBe('Hacked Title');
});
```

## API Testing

### JSON API Tests

```php
test('api returns paginated events', function () {
    Event::factory()->count(25)->for($this->user->tenant)->create();
    
    $response = $this->actingAs($this->user)
        ->getJson('/api/events');
    
    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'starts_at', 'venue'],
            ],
            'meta' => ['current_page', 'total', 'per_page'],
            'links' => ['first', 'last', 'prev', 'next'],
        ])
        ->assertJsonCount(20, 'data');
});

test('api validates input', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/events', [
            'title' => '', // Invalid
        ]);
    
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});
```

## Test Helpers

### Custom Assertions

```php
// tests/Pest.php
expect()->extend('toBeUuid', function () {
    return $this->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
});

// Usage
test('generates uuid for model', function () {
    $user = User::factory()->create();
    
    expect($user->id)->toBeUuid();
});
```

### Helper Functions

```php
// tests/Helpers/functions.php
function createAuthenticatedUser(array $attributes = []): User
{
    $user = User::factory()->create($attributes);
    test()->actingAs($user);
    return $user;
}

function travelToTenant(Tenant $tenant): void
{
    TenantService::setCurrentTenant($tenant);
}
```

## Performance Testing

### Response Time Tests

```php
test('dashboard loads within acceptable time', function () {
    $start = microtime(true);
    
    $response = $this->actingAs($this->user)
        ->get(route('portal.dashboard'));
    
    $duration = microtime(true) - $start;
    
    $response->assertOk();
    expect($duration)->toBeLessThan(0.5); // 500ms
});
```

### Query Count Tests

```php
test('event list avoids n+1 queries', function () {
    Event::factory()->count(10)->create();
    
    DB::enableQueryLog();
    
    $this->actingAs($this->user)
        ->get(route('portal.event.index'));
    
    $queryCount = count(DB::getQueryLog());
    
    expect($queryCount)->toBeLessThan(5); // Should use eager loading
});
```

## Continuous Integration

### GitHub Actions Configuration

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: testing
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3
        ports:
          - 3306:3306
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, pdo, pdo_mysql
          coverage: xdebug
      
      - name: Install Dependencies
        run: composer install --no-interaction
      
      - name: Run Tests
        run: vendor/bin/pest --coverage
        env:
          DB_CONNECTION: mysql
          DB_DATABASE: testing
          DB_USERNAME: root
          DB_PASSWORD: password
```

## Code Coverage

### Coverage Requirements

- Minimum 80% overall coverage
- Critical paths require 100% coverage
- New features must include tests

### Running Coverage

```bash
# Generate coverage report
vendor/bin/pest --coverage

# HTML coverage report
vendor/bin/pest --coverage-html=coverage

# Coverage with minimum threshold
vendor/bin/pest --coverage --min=80
```

## Best Practices

1. **Test Behavior, Not Implementation**
   - Focus on what the code does, not how

2. **Keep Tests Simple**
   - One assertion per test when possible
   - Clear test names that describe behavior

3. **Use Factories**
   - Don't hardcode test data
   - Create reusable factory states

4. **Mock External Services**
   - Don't make real API calls in tests
   - Use dependency injection for testability

5. **Test Edge Cases**
   - Null values
   - Empty collections
   - Boundary conditions

6. **Maintain Test Performance**
   - Use in-memory SQLite for unit tests
   - Minimize database operations
   - Parallelize test execution 