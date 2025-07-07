# API Design Standards

## Overview

Meet2Be follows RESTful API design principles with consistent patterns for resource management, authentication, and response formatting.

## API Architecture

### Versioning

API versioning through URL path:

```
/api/v1/events
/api/v2/events  # Future version
```

### Route Structure

```php
// routes/api.php
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Public endpoints
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);
    
    // Authenticated endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('events', EventController::class)->except(['index', 'show']);
        Route::post('events/{event}/attend', [EventAttendanceController::class, 'store']);
        Route::delete('events/{event}/attend', [EventAttendanceController::class, 'destroy']);
    });
});
```

## RESTful Conventions

### HTTP Methods

| Method | Action | Route Example | Description |
|--------|--------|---------------|-------------|
| GET | Index | `/api/v1/events` | List resources |
| GET | Show | `/api/v1/events/{id}` | Get single resource |
| POST | Store | `/api/v1/events` | Create resource |
| PUT/PATCH | Update | `/api/v1/events/{id}` | Update resource |
| DELETE | Destroy | `/api/v1/events/{id}` | Delete resource |

### Status Codes

```php
return response()->json($data, 200);  // OK
return response()->json($data, 201);  // Created
return response()->json(null, 204);   // No Content
return response()->json($errors, 400); // Bad Request
return response()->json($error, 401);  // Unauthorized
return response()->json($error, 403);  // Forbidden
return response()->json($error, 404);  // Not Found
return response()->json($errors, 422); // Unprocessable Entity
return response()->json($error, 500);  // Server Error
```

## Request Format

### Headers

```http
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
X-Tenant-ID: {tenant_uuid}
```

### Request Body

```json
{
    "title": "Annual Conference",
    "description": "Company annual conference",
    "venue_id": "550e8400-e29b-41d4-a716-446655440000",
    "starts_at": "2025-03-15T09:00:00Z",
    "ends_at": "2025-03-15T17:00:00Z",
    "max_attendees": 100
}
```

## Response Format

### Success Response

```json
{
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "type": "event",
        "attributes": {
            "title": "Annual Conference",
            "description": "Company annual conference",
            "starts_at": "2025-03-15T09:00:00Z",
            "ends_at": "2025-03-15T17:00:00Z",
            "status": "published"
        },
        "relationships": {
            "venue": {
                "data": {
                    "id": "660e8400-e29b-41d4-a716-446655440000",
                    "type": "venue"
                }
            }
        },
        "links": {
            "self": "https://api.meet2be.com/v1/events/550e8400-e29b-41d4-a716-446655440000"
        }
    }
}
```

### Error Response

```json
{
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "errors": {
            "title": [
                "The title field is required."
            ],
            "starts_at": [
                "The starts at must be a date after now."
            ]
        }
    }
}
```

### Collection Response

```json
{
    "data": [
        {
            "id": "550e8400-e29b-41d4-a716-446655440000",
            "type": "event",
            "attributes": {
                "title": "Event 1"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 20,
        "to": 20,
        "total": 95
    },
    "links": {
        "first": "https://api.meet2be.com/v1/events?page=1",
        "last": "https://api.meet2be.com/v1/events?page=5",
        "prev": null,
        "next": "https://api.meet2be.com/v1/events?page=2"
    }
}
```

## API Resources

### Resource Classes

```php
namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => 'event',
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'starts_at' => $this->starts_at->toIso8601String(),
                'ends_at' => $this->ends_at->toIso8601String(),
                'status' => $this->status,
                'capacity' => $this->max_attendees,
                'available_seats' => $this->available_seats,
                'created_at' => $this->created_at->toIso8601String(),
                'updated_at' => $this->updated_at->toIso8601String(),
            ],
            'relationships' => [
                'venue' => new VenueResource($this->whenLoaded('venue')),
                'organizer' => new UserResource($this->whenLoaded('organizer')),
                'attendees' => UserResource::collection($this->whenLoaded('attendees')),
            ],
            'links' => [
                'self' => route('api.v1.events.show', $this),
            ],
        ];
    }
}
```

### Collection Resources

```php
class EventCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total_revenue' => $this->collection->sum('revenue'),
                'average_attendance' => $this->collection->avg('current_attendees'),
            ],
        ];
    }
    
    public function with($request): array
    {
        return [
            'links' => [
                'self' => $request->url(),
            ],
        ];
    }
}
```

## Filtering & Sorting

### Query Parameters

```
GET /api/v1/events?filter[status]=published&filter[venue_id]=uuid&sort=-starts_at&include=venue,organizer
```

### Implementation

```php
class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();
        
        // Filtering
        if ($request->has('filter')) {
            foreach ($request->get('filter') as $field => $value) {
                $query->where($field, $value);
            }
        }
        
        // Sorting
        if ($request->has('sort')) {
            $sortField = $request->get('sort');
            $sortDirection = 'asc';
            
            if (str_starts_with($sortField, '-')) {
                $sortDirection = 'desc';
                $sortField = substr($sortField, 1);
            }
            
            $query->orderBy($sortField, $sortDirection);
        }
        
        // Including relationships
        if ($request->has('include')) {
            $includes = explode(',', $request->get('include'));
            $query->with($includes);
        }
        
        return EventResource::collection($query->paginate());
    }
}
```

## Pagination

### Cursor Pagination

```php
$events = Event::cursorPaginate(20);

return EventResource::collection($events);
```

Response includes cursor:

```json
{
    "data": [...],
    "meta": {
        "path": "https://api.meet2be.com/v1/events",
        "per_page": 20,
        "next_cursor": "eyJpZCI6MTUsInBvaW50cyI6eyJjcmVhdGVkX2F0IjoiMjAyNS0wMS0xNSJ9fQ",
        "prev_cursor": null
    }
}
```

### Offset Pagination

```php
$events = Event::paginate(20);

return EventResource::collection($events);
```

## Authentication

### Sanctum Setup

```php
// User model
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasUuids;
}
```

### Token Generation

```php
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'The provided credentials are incorrect.',
                ],
            ], 401);
        }
        
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        
        return response()->json([
            'data' => [
                'token' => $token,
                'type' => 'Bearer',
                'expires_in' => config('sanctum.expiration') * 60,
            ],
            'user' => new UserResource($user),
        ]);
    }
}
```

## Rate Limiting

### Configuration

```php
// app/Providers/RouteServiceProvider.php
protected function configureRateLimiting()
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
    
    RateLimiter::for('api-strict', function (Request $request) {
        return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
    });
}
```

### Response Headers

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 58
X-RateLimit-Reset: 1642291200
```

## Caching

### Cache Headers

```php
return response()
    ->json(new EventResource($event))
    ->header('Cache-Control', 'max-age=3600, public')
    ->header('ETag', md5($event->updated_at));
```

### Conditional Requests

```php
public function show(Request $request, Event $event)
{
    $etag = md5($event->updated_at);
    
    if ($request->header('If-None-Match') === $etag) {
        return response(null, 304);
    }
    
    return response()
        ->json(new EventResource($event))
        ->header('ETag', $etag);
}
```

## API Documentation

### OpenAPI Specification

```yaml
openapi: 3.0.0
info:
  title: Meet2Be API
  version: 1.0.0
  
paths:
  /api/v1/events:
    get:
      summary: List events
      parameters:
        - name: filter[status]
          in: query
          schema:
            type: string
            enum: [draft, published, cancelled]
        - name: sort
          in: query
          schema:
            type: string
            example: -starts_at
      responses:
        200:
          description: Successful response
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/EventCollection'
```

## Testing API

### API Tests

```php
test('api returns paginated events', function () {
    Event::factory()->count(25)->create();
    
    $response = $this->getJson('/api/v1/events');
    
    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => ['title', 'starts_at'],
                ],
            ],
            'meta',
            'links',
        ]);
});

test('api filters events by status', function () {
    Event::factory()->count(3)->published()->create();
    Event::factory()->count(2)->draft()->create();
    
    $response = $this->getJson('/api/v1/events?filter[status]=published');
    
    $response->assertOk()
        ->assertJsonCount(3, 'data');
});
```

## Best Practices

1. **Use API Resources** for consistent formatting
2. **Version your API** from the start
3. **Implement proper authentication** with tokens
4. **Use appropriate HTTP status codes**
5. **Include pagination** for list endpoints
6. **Add rate limiting** to prevent abuse
7. **Document all endpoints** with OpenAPI
8. **Validate all input** with FormRequests
9. **Handle errors consistently**
10. **Use UTC for all timestamps** 