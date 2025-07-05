<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant\Tenant;
use App\Models\System\Timezone;
use App\Models\System\Language;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    const TYPE_CONFERENCE = 'conference';
    const TYPE_WORKSHOP = 'workshop';
    const TYPE_SEMINAR = 'seminar';
    const TYPE_WEBINAR = 'webinar';
    const TYPE_MEETUP = 'meetup';
    const TYPE_MEETING = 'meeting';
    const TYPE_SUMMIT = 'summit';
    const TYPE_SYMPOSIUM = 'symposium';
    const TYPE_TRAINING = 'training';
    const TYPE_COURSE = 'course';
    const TYPE_BOOTCAMP = 'bootcamp';
    const TYPE_HACKATHON = 'hackathon';
    const TYPE_NETWORKING = 'networking';
    const TYPE_PANEL = 'panel';
    const TYPE_ROUNDTABLE = 'roundtable';
    const TYPE_EXPO = 'expo';
    const TYPE_OTHER = 'other';

    const TYPES = [
        self::TYPE_CONFERENCE => 'Conference',
        self::TYPE_WORKSHOP => 'Workshop',
        self::TYPE_SEMINAR => 'Seminar',
        self::TYPE_WEBINAR => 'Webinar',
        self::TYPE_MEETUP => 'Meetup',
        self::TYPE_MEETING => 'Meeting',
        self::TYPE_SUMMIT => 'Summit',
        self::TYPE_SYMPOSIUM => 'Symposium',
        self::TYPE_TRAINING => 'Training',
        self::TYPE_COURSE => 'Course',
        self::TYPE_BOOTCAMP => 'Bootcamp',
        self::TYPE_HACKATHON => 'Hackathon',
        self::TYPE_NETWORKING => 'Networking Event',
        self::TYPE_PANEL => 'Panel Discussion',
        self::TYPE_ROUNDTABLE => 'Roundtable',
        self::TYPE_EXPO => 'Expo',
        self::TYPE_OTHER => 'Other',
    ];

    protected $fillable = [
        'tenant_id',
        'code',
        'title',
        'description',
        'slug',
        'type',
        'status',
        'start_date',
        'end_date',
        'timezone_id',
        'language_id',
        'is_public',
        'is_featured',
        'require_approval',
        'max_participants',
        'current_participants',
        'cover_image_name',
        'logo_name',
        'organizers',
        'contact_info',
        'settings',
        'metadata',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'last_activity_at' => 'datetime',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'require_approval' => 'boolean',
            'max_participants' => 'integer',
            'current_participants' => 'integer',
            'organizers' => 'array',
            'contact_info' => 'array',
            'settings' => 'array',
            'metadata' => 'array',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function venues()
    {
        return $this->hasMany(\App\Models\Event\Venue\Venue::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing' || 
               ($this->status === 'published' && now()->between($this->start_date, $this->end_date));
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed' || 
               ($this->status === 'published' && now()->isAfter($this->end_date));
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'published' && now()->isBefore($this->start_date);
    }

    public function hasCapacity(): bool
    {
        if (!$this->max_participants) {
            return true;
        }
        
        return $this->current_participants < $this->max_participants;
    }

    public function getRemainingCapacity(): int
    {
        if (!$this->max_participants) {
            return PHP_INT_MAX;
        }
        
        return max(0, $this->max_participants - $this->current_participants);
    }

    public function getCapacityPercentage(): float
    {
        if (!$this->max_participants) {
            return 0;
        }
        
        return round(($this->current_participants / $this->max_participants) * 100, 2);
    }

    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getDurationInHours(): int
    {
        return $this->start_date->diffInHours($this->end_date);
    }

    public function getTotalVenueCapacity(): int
    {
        return $this->venues()->sum('capacity');
    }

    public function getAvailableVenues()
    {
        return $this->venues()->available()->get();
    }

    public function hasVenues(): bool
    {
        return $this->venues()->exists();
    }

    public function hasAvailableVenues(): bool
    {
        return $this->venues()->available()->exists();
    }

    public function updateActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function incrementParticipants(int $count = 1): void
    {
        $this->increment('current_participants', $count);
        $this->updateActivity();
    }

    public function decrementParticipants(int $count = 1): void
    {
        $this->decrement('current_participants', max(0, $count));
        $this->updateActivity();
    }

    public function publish(): void
    {
        $this->update(['status' => 'published']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function getLocalizedStartDate($timezone = null, $format = 'Y-m-d H:i:s'): string
    {
        $tz = $timezone ?? $this->timezone?->name ?? config('app.timezone');
        return $this->start_date->timezone($tz)->format($format);
    }

    public function getLocalizedEndDate($timezone = null, $format = 'Y-m-d H:i:s'): string
    {
        $tz = $timezone ?? $this->timezone?->name ?? config('app.timezone');
        return $this->end_date->timezone($tz)->format($format);
    }

    public function getTypeName(): string
    {
        return self::TYPES[$this->type] ?? 'Unknown';
    }

    public function isOnlineEvent(): bool
    {
        return in_array($this->type, [self::TYPE_WEBINAR, self::TYPE_MEETING]);
    }

    public function isEducationalEvent(): bool
    {
        return in_array($this->type, [
            self::TYPE_WORKSHOP,
            self::TYPE_SEMINAR,
            self::TYPE_TRAINING,
            self::TYPE_COURSE,
            self::TYPE_BOOTCAMP,
            self::TYPE_SYMPOSIUM
        ]);
    }

    public function isNetworkingEvent(): bool
    {
        return in_array($this->type, [
            self::TYPE_MEETUP,
            self::TYPE_NETWORKING,
            self::TYPE_PANEL,
            self::TYPE_ROUNDTABLE
        ]);
    }

    public function isExhibitionEvent(): bool
    {
        return $this->type === self::TYPE_EXPO;
    }

    public function isCompetitiveEvent(): bool
    {
        return $this->type === self::TYPE_HACKATHON;
    }

    public static function getTypeOptions(): array
    {
        return self::TYPES;
    }

    public static function getEducationalTypes(): array
    {
        return [
            self::TYPE_WORKSHOP => self::TYPES[self::TYPE_WORKSHOP],
            self::TYPE_SEMINAR => self::TYPES[self::TYPE_SEMINAR],
            self::TYPE_TRAINING => self::TYPES[self::TYPE_TRAINING],
            self::TYPE_COURSE => self::TYPES[self::TYPE_COURSE],
            self::TYPE_BOOTCAMP => self::TYPES[self::TYPE_BOOTCAMP],
            self::TYPE_SYMPOSIUM => self::TYPES[self::TYPE_SYMPOSIUM],
        ];
    }

    public static function getOnlineTypes(): array
    {
        return [
            self::TYPE_WEBINAR => self::TYPES[self::TYPE_WEBINAR],
            self::TYPE_MEETING => self::TYPES[self::TYPE_MEETING],
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            if (empty($event->code)) {
                $event->code = static::generateUniqueCode($event->tenant_id);
            }
            
            if (empty($event->slug)) {
                $event->slug = \Str::slug($event->title);
            }
        });
        
        static::updating(function ($event) {
            // Auto-update status based on dates
            if ($event->isDirty(['start_date', 'end_date', 'status'])) {
                if ($event->status === 'published') {
                    $now = now();
                    if ($now->between($event->start_date, $event->end_date)) {
                        $event->status = 'ongoing';
                    } elseif ($now->isAfter($event->end_date)) {
                        $event->status = 'completed';
                    }
                }
            }
        });
    }

    protected static function generateUniqueCode(string $tenantId): string
    {
        do {
            $code = 'EVT' . strtoupper(\Str::random(2)) . sprintf('%05d', rand(1, 99999));
        } while (static::where('tenant_id', $tenantId)->where('code', $code)->exists());
        
        return $code;
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->published()->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'ongoing')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'published')
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
              });
        });
    }

    public function scopePast($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'completed')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'published')
                     ->where('end_date', '<', now());
              });
        });
    }

    public function scopeWithAvailableCapacity($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('max_participants')
              ->orWhereRaw('current_participants < max_participants');
        });
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfTypes($query, array $types)
    {
        return $query->whereIn('type', $types);
    }

    public function scopeOnlineEvents($query)
    {
        return $query->whereIn('type', [self::TYPE_WEBINAR, self::TYPE_MEETING]);
    }

    public function scopeEducationalEvents($query)
    {
        return $query->whereIn('type', [
            self::TYPE_WORKSHOP,
            self::TYPE_SEMINAR,
            self::TYPE_TRAINING,
            self::TYPE_COURSE,
            self::TYPE_BOOTCAMP,
            self::TYPE_SYMPOSIUM
        ]);
    }

    public function scopeNetworkingEvents($query)
    {
        return $query->whereIn('type', [
            self::TYPE_MEETUP,
            self::TYPE_NETWORKING,
            self::TYPE_PANEL,
            self::TYPE_ROUNDTABLE
        ]);
    }

    public function scopeExhibitionEvents($query)
    {
        return $query->where('type', self::TYPE_EXPO);
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\Event\EventFactory::new();
    }
} 