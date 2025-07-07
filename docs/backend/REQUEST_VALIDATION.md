# Request Validation

## Overview

Meet2Be uses Laravel's FormRequest classes for comprehensive input validation. This ensures data integrity, security, and provides clear error messages to users.

## FormRequest Structure

### Basic FormRequest

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
            'max_attendees' => 'nullable|integer|min:1|max:10000',
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required.',
            'starts_at.after' => 'Event must start in the future.',
            'ends_at.after' => 'Event must end after it starts.',
            'venue_id.exists' => 'Selected venue does not exist.',
        ];
    }
    
    public function attributes(): array
    {
        return [
            'starts_at' => 'start date',
            'ends_at' => 'end date',
            'max_attendees' => 'maximum attendees',
        ];
    }
}
```

## Validation Rules

### Common Laravel Rules

```php
// Required fields
'name' => 'required|string',

// Optional fields
'description' => 'nullable|string',

// String validation
'title' => 'string|min:3|max:255',

// Numeric validation
'price' => 'numeric|min:0|max:999999.99',
'quantity' => 'integer|min:1',

// Email validation
'email' => 'required|email:rfc,dns',

// Date validation
'date' => 'date|date_format:Y-m-d',
'datetime' => 'date|after:now',

// Boolean validation
'is_active' => 'boolean',
'accepted' => 'accepted', // Must be true

// File validation
'avatar' => 'image|mimes:jpg,png|max:2048', // 2MB max
'document' => 'file|mimes:pdf,doc,docx|max:10240',

// Array validation
'tags' => 'array|min:1|max:5',
'tags.*' => 'string|distinct',

// UUID validation
'user_id' => 'uuid|exists:users,id',

// Enum validation
'status' => 'in:draft,published,archived',

// Regex validation
'phone' => 'regex:/^[0-9]{10,15}$/',

// Unique validation
'email' => 'unique:users,email',
'slug' => 'unique:events,slug,NULL,id,tenant_id,' . $this->user()->tenant_id,
```

### Conditional Validation

```php
public function rules(): array
{
    return [
        'type' => 'required|in:physical,virtual,hybrid',
        
        // Required only for physical venues
        'address' => 'required_if:type,physical|string',
        'city' => 'required_if:type,physical|string',
        'postal_code' => 'required_if:type,physical|string',
        
        // Required only for virtual venues
        'virtual_url' => 'required_if:type,virtual|url',
        'platform' => 'required_if:type,virtual|in:zoom,teams,meet',
        
        // Optional unless specified
        'capacity' => 'required_unless:unlimited_capacity,true|integer|min:1',
        
        // Exclude from validation if null
        'notes' => 'exclude_if:has_notes,false|string',
        
        // Sometimes rules (field exists in request)
        'password' => 'sometimes|required|min:8',
    ];
}
```

### Complex Validation

```php
public function rules(): array
{
    return [
        // Array of objects
        'attendees' => 'array',
        'attendees.*.name' => 'required|string',
        'attendees.*.email' => 'required|email',
        'attendees.*.role' => 'required|in:participant,speaker,organizer',
        
        // Nested validation
        'location' => 'required|array',
        'location.lat' => 'required|numeric|between:-90,90',
        'location.lng' => 'required|numeric|between:-180,180',
        
        // Custom validation with closure
        'discount_code' => [
            'nullable',
            'string',
            function ($attribute, $value, $fail) {
                if ($value && !$this->validateDiscountCode($value)) {
                    $fail('The discount code is invalid or expired.');
                }
            },
        ],
    ];
}
```

## Custom Validation Rules

### Creating Custom Rules

```php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SecurePassword implements Rule
{
    protected array $errors = [];
    
    public function passes($attribute, $value): bool
    {
        $this->errors = [];
        
        if (strlen($value) < 12) {
            $this->errors[] = 'at least 12 characters';
        }
        
        if (!preg_match('/[A-Z]/', $value)) {
            $this->errors[] = 'one uppercase letter';
        }
        
        if (!preg_match('/[a-z]/', $value)) {
            $this->errors[] = 'one lowercase letter';
        }
        
        if (!preg_match('/[0-9]/', $value)) {
            $this->errors[] = 'one number';
        }
        
        if (!preg_match('/[@$!%*?&]/', $value)) {
            $this->errors[] = 'one special character (@$!%*?&)';
        }
        
        return empty($this->errors);
    }
    
    public function message(): string
    {
        return 'The :attribute must contain ' . implode(', ', $this->errors) . '.';
    }
}
```

### Using Custom Rules

```php
public function rules(): array
{
    return [
        'password' => ['required', 'confirmed', new SecurePassword],
    ];
}
```

### Invokable Rules

```php
namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Uppercase implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): void
    {
        if (strtoupper($value) !== $value) {
            $fail('The :attribute must be uppercase.');
        }
    }
}
```

## Tenant-Aware Validation

### Unique Within Tenant

```php
namespace App\Http\Requests;

use App\Services\TenantService;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        $tenantId = TenantService::getCurrentTenantId();
        
        return [
            'sku' => [
                'required',
                'string',
                "unique:products,sku,NULL,id,tenant_id,{$tenantId}",
            ],
            'name' => 'required|string|max:255',
        ];
    }
}
```

### Exists Within Tenant

```php
public function rules(): array
{
    return [
        'category_id' => [
            'required',
            'uuid',
            Rule::exists('categories', 'id')->where('tenant_id', $this->user()->tenant_id),
        ],
    ];
}
```

## Data Preparation

### Preparing Data Before Validation

```php
protected function prepareForValidation(): void
{
    $this->merge([
        'slug' => Str::slug($this->title),
        'phone' => preg_replace('/[^0-9]/', '', $this->phone),
        'price' => round((float) $this->price, 2),
    ]);
}
```

### Modifying Validated Data

```php
public function validated($key = null, $default = null)
{
    $data = parent::validated($key, $default);
    
    // Add computed fields
    if (is_null($key)) {
        $data['tenant_id'] = $this->user()->tenant_id;
        $data['created_by'] = $this->user()->id;
    }
    
    return $data;
}
```

### Sanitizing Input

```php
protected function prepareForValidation(): void
{
    $this->merge([
        'name' => trim($this->name),
        'email' => strtolower(trim($this->email)),
        'tags' => array_map('trim', $this->tags ?? []),
    ]);
}
```

## Authorization

### Simple Authorization

```php
public function authorize(): bool
{
    return $this->user()->can('create', Event::class);
}
```

### Complex Authorization

```php
public function authorize(): bool
{
    $event = $this->route('event');
    
    return $this->user()->can('update', $event) 
        && $event->status !== 'published'
        && $event->starts_at->isFuture();
}
```

### Authorization with Messages

```php
public function authorize(): bool
{
    $event = $this->route('event');
    
    if ($event->status === 'cancelled') {
        $this->failedAuthorization = 'Cannot modify cancelled events.';
        return false;
    }
    
    return $this->user()->can('update', $event);
}

protected function failedAuthorization()
{
    throw new AuthorizationException($this->failedAuthorization ?? 'This action is unauthorized.');
}
```

## After Validation Hooks

### After Validation

```php
public function withValidator($validator): void
{
    $validator->after(function ($validator) {
        if ($this->hasOverlappingEvent()) {
            $validator->errors()->add('starts_at', 'This time slot overlaps with another event.');
        }
    });
}

protected function hasOverlappingEvent(): bool
{
    return Event::where('venue_id', $this->venue_id)
        ->where('id', '!=', $this->route('event')?->id)
        ->where(function ($query) {
            $query->whereBetween('starts_at', [$this->starts_at, $this->ends_at])
                ->orWhereBetween('ends_at', [$this->starts_at, $this->ends_at]);
        })
        ->exists();
}
```

### Passed Validation

```php
protected function passedValidation(): void
{
    // Log successful validation
    Log::info('Event validation passed', [
        'user_id' => $this->user()->id,
        'event_data' => $this->validated(),
    ]);
}
```

## Error Handling

### Custom Error Messages

```php
public function messages(): array
{
    return [
        'email.required' => 'We need your email address.',
        'email.email' => 'Please provide a valid email address.',
        'password.min' => 'Your password is too short. Use at least :min characters.',
        'starts_at.after' => 'The event cannot start in the past.',
    ];
}
```

### Custom Attribute Names

```php
public function attributes(): array
{
    return [
        'email' => 'email address',
        'dob' => 'date of birth',
        'starts_at' => 'start date and time',
    ];
}
```

### Failed Validation Response

```php
protected function failedValidation(Validator $validator)
{
    if ($this->expectsJson()) {
        throw new HttpResponseException(
            response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
    
    parent::failedValidation($validator);
}
```

## File Upload Validation

### Image Validation

```php
public function rules(): array
{
    return [
        'avatar' => [
            'required',
            'image',
            'mimes:jpeg,png,jpg',
            'max:2048', // 2MB
            'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ],
        'gallery' => 'array|max:10',
        'gallery.*' => [
            'image',
            'mimes:jpeg,png,jpg',
            'max:5120', // 5MB each
        ],
    ];
}
```

### Document Validation

```php
public function rules(): array
{
    return [
        'document' => [
            'required',
            'file',
            'mimes:pdf,doc,docx,xls,xlsx',
            'max:10240', // 10MB
        ],
        'attachments' => 'array|max:5',
        'attachments.*' => [
            'file',
            'max:5120',
            new VirusScanRule, // Custom virus scan
        ],
    ];
}
```

## Testing Validation

### Unit Tests

```php
test('validates required fields', function () {
    $request = new StoreEventRequest();
    
    $validator = Validator::make([], $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->keys())->toContain('title', 'venue_id', 'starts_at');
});

test('validates date is in future', function () {
    $request = new StoreEventRequest();
    
    $validator = Validator::make([
        'starts_at' => now()->subDay(),
    ], $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('starts_at'))->toContain('after');
});
```

### Feature Tests

```php
test('returns validation errors for invalid data', function () {
    $response = $this->actingAs($this->user)
        ->postJson(route('portal.event.store'), [
            'title' => '', // Invalid
            'starts_at' => 'not-a-date', // Invalid
        ]);
    
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'starts_at', 'venue_id']);
});
```

## Best Practices

1. **Always use FormRequest classes** for complex validation
2. **Authorize before validating** to prevent information leakage
3. **Prepare data before validation** for consistency
4. **Use custom rules** for complex business logic
5. **Provide clear error messages** with context
6. **Test all validation rules** thoroughly
7. **Validate file uploads** strictly
8. **Consider performance** for complex validations
9. **Use database transactions** when validation involves DB queries
10. **Log validation failures** for security monitoring 