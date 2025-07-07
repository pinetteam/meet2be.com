<?php

namespace App\Models\Event\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Event\Event;
use App\Traits\TenantAware;
use App\Traits\HasTimezone;

class Venue extends Model
{
    use HasFactory, HasUuids, SoftDeletes, TenantAware, HasTimezone;

    protected $table = 'event_venues';
    protected $keyType = 'string';
    public $incrementing = false;

    const TYPE_HALL = 'hall';
    const TYPE_MEETING_ROOM = 'meeting_room';
    const TYPE_CONFERENCE_ROOM = 'conference_room';
    const TYPE_AUDITORIUM = 'auditorium';
    const TYPE_CLASSROOM = 'classroom';
    const TYPE_WORKSHOP_ROOM = 'workshop_room';
    const TYPE_EXHIBITION_HALL = 'exhibition_hall';
    const TYPE_BALLROOM = 'ballroom';
    const TYPE_BOARDROOM = 'boardroom';
    const TYPE_VIRTUAL_ROOM = 'virtual_room';
    const TYPE_OUTDOOR_SPACE = 'outdoor_space';
    const TYPE_OTHER = 'other';

    const TYPES = [
        self::TYPE_HALL => 'Hall',
        self::TYPE_MEETING_ROOM => 'Meeting Room',
        self::TYPE_CONFERENCE_ROOM => 'Conference Room',
        self::TYPE_AUDITORIUM => 'Auditorium',
        self::TYPE_CLASSROOM => 'Classroom',
        self::TYPE_WORKSHOP_ROOM => 'Workshop Room',
        self::TYPE_EXHIBITION_HALL => 'Exhibition Hall',
        self::TYPE_BALLROOM => 'Ballroom',
        self::TYPE_BOARDROOM => 'Boardroom',
        self::TYPE_VIRTUAL_ROOM => 'Virtual Room',
        self::TYPE_OUTDOOR_SPACE => 'Outdoor Space',
        self::TYPE_OTHER => 'Other',
    ];

    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_RESERVED = 'reserved';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_CLOSED = 'closed';

    const STATUSES = [
        self::STATUS_AVAILABLE => 'Available',
        self::STATUS_OCCUPIED => 'Occupied',
        self::STATUS_RESERVED => 'Reserved',
        self::STATUS_MAINTENANCE => 'Under Maintenance',
        self::STATUS_CLOSED => 'Closed',
    ];

    protected $fillable = [
        'tenant_id',
        'event_id',
        'code',
        'name',
        'description',
        'type',
        'capacity',
        'location',
        'has_wifi',
        'wifi_ssid',
        'wifi_password',
        'is_accessible',
        'accessibility_features',
        'contact_person',
        'contact_phone',
        'contact_email',
        'image_name',
        'is_active',
        'status',
        'last_used_at',
        'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'has_wifi' => 'boolean',
            'is_accessible' => 'boolean',
            'is_active' => 'boolean',
            'capacity' => 'integer',
            'usage_count' => 'integer',
            'accessibility_features' => 'array',
            'last_used_at' => 'datetime',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant\Tenant::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE && $this->is_active;
    }

    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_OCCUPIED;
    }

    public function isReserved(): bool
    {
        return $this->status === self::STATUS_RESERVED;
    }

    public function isUnderMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED || !$this->is_active;
    }

    public function canAccommodate(int $attendees): bool
    {
        return $this->capacity >= $attendees;
    }

    public function getTypeName(): string
    {
        return self::TYPES[$this->type] ?? 'Unknown';
    }

    public function getStatusName(): string
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }

    public function getFullLocation(): string
    {
        return $this->location ?: 'Not specified';
    }

    public function markAsAvailable(): void
    {
        $this->update(['status' => self::STATUS_AVAILABLE]);
    }

    public function markAsOccupied(): void
    {
        $this->update([
            'status' => self::STATUS_OCCUPIED,
            'last_used_at' => $this->getCurrentTime(),
        ]);
        $this->increment('usage_count');
    }

    public function markAsReserved(): void
    {
        $this->update(['status' => self::STATUS_RESERVED]);
    }

    public function markAsMaintenance(): void
    {
        $this->update(['status' => self::STATUS_MAINTENANCE]);
    }

    public function markAsClosed(): void
    {
        $this->update(['status' => self::STATUS_CLOSED]);
    }

    public function hasAccessibilityFeature(string $feature): bool
    {
        return in_array($feature, $this->accessibility_features ?? []);
    }

    public function isLargeVenue(): bool
    {
        return $this->capacity >= 100;
    }

    public function isMediumVenue(): bool
    {
        return $this->capacity >= 30 && $this->capacity < 100;
    }

    public function isSmallVenue(): bool
    {
        return $this->capacity < 30;
    }

    public function isVirtualVenue(): bool
    {
        return $this->type === self::TYPE_VIRTUAL_ROOM;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($venue) {
            if (empty($venue->code)) {
                $venue->code = static::generateUniqueCode($venue->type, $venue->tenant_id);
            }
        });
    }

    protected static function generateUniqueCode(string $type, string $tenantId): string
    {
        $prefix = match ($type) {
            self::TYPE_HALL => 'HALL',
            self::TYPE_MEETING_ROOM => 'MTG',
            self::TYPE_CONFERENCE_ROOM => 'CNF',
            self::TYPE_AUDITORIUM => 'AUD',
            self::TYPE_CLASSROOM => 'CLS',
            self::TYPE_WORKSHOP_ROOM => 'WRK',
            self::TYPE_EXHIBITION_HALL => 'EXH',
            self::TYPE_BALLROOM => 'BLR',
            self::TYPE_BOARDROOM => 'BRD',
            self::TYPE_VIRTUAL_ROOM => 'VRT',
            self::TYPE_OUTDOOR_SPACE => 'OUT',
            default => 'VEN',
        };

        do {
            $code = $prefix . sprintf('%04d', rand(1, 9999));
        } while (static::where('tenant_id', $tenantId)->where('code', $code)->exists());
        
        return $code;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWithMinCapacity($query, int $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }

    public function scopeWithWifi($query)
    {
        return $query->where('has_wifi', true);
    }

    public function scopeAccessible($query)
    {
        return $query->where('is_accessible', true);
    }

    public function scopeLargeVenues($query)
    {
        return $query->where('capacity', '>=', 100);
    }

    public function scopeMediumVenues($query)
    {
        return $query->whereBetween('capacity', [30, 99]);
    }

    public function scopeSmallVenues($query)
    {
        return $query->where('capacity', '<', 30);
    }

    public static function getTypeOptions(): array
    {
        return self::TYPES;
    }

    public static function getStatusOptions(): array
    {
        return self::STATUSES;
    }

    public static function getAccessibilityFeatures(): array
    {
        return [
            'wheelchair' => 'Wheelchair Accessible',
            'elevator' => 'Elevator Access',
            'ramp' => 'Ramp Access',
            'hearing_loop' => 'Hearing Loop System',
            'braille' => 'Braille Signage',
            'accessible_restroom' => 'Accessible Restroom',
            'accessible_parking' => 'Accessible Parking',
        ];
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\Event\Venue\VenueFactory::new();
    }
} 