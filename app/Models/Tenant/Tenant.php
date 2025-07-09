<?php

namespace App\Models\Tenant;

use App\Models\System\Country;
use App\Models\System\Currency;
use App\Models\System\Language;
use App\Models\System\Timezone;
use App\Models\User\User;
use App\Models\Event\Event;
use App\Traits\HasTimezone;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Tenant extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasTimezone;

    protected $keyType = 'string';
    public $incrementing = false;

    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_BUSINESS = 'business';
    const TYPE_ENTERPRISE = 'enterprise';

    const TYPES = [
        self::TYPE_INDIVIDUAL => 'Individual',
        self::TYPE_BUSINESS => 'Business',
        self::TYPE_ENTERPRISE => 'Enterprise',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_EXPIRED = 'expired';
    const STATUS_TRIAL = 'trial';

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_SUSPENDED => 'Suspended',
        self::STATUS_EXPIRED => 'Expired',
        self::STATUS_TRIAL => 'Trial',
    ];

    const PLAN_BASIC = 'basic';
    const PLAN_PRO = 'pro';
    const PLAN_ENTERPRISE = 'enterprise';

    const PLANS = [
        self::PLAN_BASIC => 'Basic',
        self::PLAN_PRO => 'Professional',
        self::PLAN_ENTERPRISE => 'Enterprise',
    ];

    // Date format constants
    const DATE_FORMAT_DMY_SLASH = 'd/m/Y';
    const DATE_FORMAT_MDY_SLASH = 'm/d/Y';
    const DATE_FORMAT_YMD_DASH = 'Y-m-d';
    const DATE_FORMAT_DMY_DOT = 'd.m.Y';
    
    const DATE_FORMATS = [
        self::DATE_FORMAT_DMY_SLASH => 'DD/MM/YYYY',
        self::DATE_FORMAT_MDY_SLASH => 'MM/DD/YYYY',
        self::DATE_FORMAT_YMD_DASH => 'YYYY-MM-DD',
        self::DATE_FORMAT_DMY_DOT => 'DD.MM.YYYY',
    ];
    
    // Time format constants
    const TIME_FORMAT_12 = '12';
    const TIME_FORMAT_24 = '24';
    
    const TIME_FORMATS = [
        self::TIME_FORMAT_12 => '12-hour',
        self::TIME_FORMAT_24 => '24-hour',
    ];

    protected $fillable = [
        // Basic Information
        'code',
        'name',
        'legal_name',
        'slug',
        'type',
        
        // Contact Information
        'email',
        'phone',
        'website',
        
        // Address Information
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country_id',
        
        // Localization Settings
        'language_id',
        'currency_id',
        'timezone_id',
        'date_format',
        'time_format',
        
        // Branding
        'logo_name',
        'favicon_name',
        
        // Subscription & Limits
        'plan',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'max_users',
        'max_storage_mb',
        'max_events',
        
        // Usage Tracking
        'current_users',
        'current_storage_mb',
        'current_events',
        
        // Settings & Metadata
        'settings',
        'features',
        'metadata',
        
        // Relationships
        'owner_id',
        'created_by',
        
        // Timestamps
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'features' => 'array',
            'metadata' => 'array',
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'max_users' => 'integer',
            'max_storage_mb' => 'integer',
            'max_events' => 'integer',
            'current_users' => 'integer',
            'current_storage_mb' => 'integer',
            'current_events' => 'integer',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isTrial(): bool
    {
        return $this->status === self::STATUS_TRIAL;
    }

    public function isOnTrial(): bool
    {
        return $this->status === self::STATUS_TRIAL && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    public function hasExpiredTrial(): bool
    {
        return $this->status === self::STATUS_TRIAL && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isPast();
    }

    public function hasActiveSubscription(): bool
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_TRIAL]) &&
               ($this->subscription_ends_at === null || $this->subscription_ends_at->isFuture());
    }

    public function canAddUsers(): bool
    {
        return $this->current_users < $this->max_users;
    }

    public function canAddEvents(): bool
    {
        return $this->current_events < $this->max_events;
    }

    public function canUseStorage(int $sizeInMb): bool
    {
        return ($this->current_storage_mb + $sizeInMb) <= $this->max_storage_mb;
    }

    public function getRemainingUsers(): int
    {
        return max(0, $this->max_users - $this->current_users);
    }

    public function getRemainingEvents(): int
    {
        return max(0, $this->max_events - $this->current_events);
    }

    public function getRemainingStorageMb(): int
    {
        return max(0, $this->max_storage_mb - $this->current_storage_mb);
    }

    public function getUserUsagePercentage(): float
    {
        if ($this->max_users === 0) return 0;
        return round(($this->current_users / $this->max_users) * 100, 2);
    }

    public function getEventUsagePercentage(): float
    {
        if ($this->max_events === 0) return 0;
        return round(($this->current_events / $this->max_events) * 100, 2);
    }

    public function getStorageUsagePercentage(): float
    {
        if ($this->max_storage_mb === 0) return 0;
        return round(($this->current_storage_mb / $this->max_storage_mb) * 100, 2);
    }

    public function incrementUsers(int $count = 1): void
    {
        $this->increment('current_users', $count);
    }

    public function decrementUsers(int $count = 1): void
    {
        $this->decrement('current_users', max(0, $count));
    }

    public function incrementEvents(int $count = 1): void
    {
        $this->increment('current_events', $count);
    }

    public function decrementEvents(int $count = 1): void
    {
        $this->decrement('current_events', max(0, $count));
    }

    public function incrementStorage(int $sizeInMb): void
    {
        $this->increment('current_storage_mb', $sizeInMb);
    }

    public function decrementStorage(int $sizeInMb): void
    {
        $this->decrement('current_storage_mb', max(0, $sizeInMb));
    }

    public function updateActivity(): void
    {
        $this->update(['last_activity_at' => $this->getCurrentTime()]);
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }

    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->update(['settings' => $settings]);
    }

    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country?->name_en
        ]);
        
        return implode(', ', $parts);
    }

    public function getTypeName(): string
    {
        return self::TYPES[$this->type] ?? 'Unknown';
    }

    public function getStatusName(): string
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }

    public function getPlanName(): string
    {
        return self::PLANS[$this->plan] ?? 'Unknown';
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'green',
            self::STATUS_INACTIVE => 'gray',
            self::STATUS_SUSPENDED => 'red',
            self::STATUS_EXPIRED => 'yellow',
            self::STATUS_TRIAL => 'blue',
            default => 'gray'
        };
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tenant) {
            if (empty($tenant->code)) {
                $tenant->code = static::generateUniqueCode();
            }
            
            if (empty($tenant->slug)) {
                $tenant->slug = \Str::slug($tenant->name);
            }
            
            // Set default date/time formats if not provided
            $tenant->date_format = $tenant->date_format ?? 'Y-m-d';
            $tenant->time_format = $tenant->time_format ?? 'H:i';
        });
    }

    protected static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(\Str::random(3)) . sprintf('%03d', rand(1, 999));
        } while (static::where('code', $code)->exists());
        
        return $code;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOnTrial($query)
    {
        return $query->where('status', self::STATUS_TRIAL)
                    ->where('trial_ends_at', '>', Carbon::now('UTC'));
    }

    public function scopeExpiredTrial($query)
    {
        return $query->where('status', self::STATUS_TRIAL)
                    ->where('trial_ends_at', '<=', Carbon::now('UTC'));
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPlan($query, string $plan)
    {
        return $query->where('plan', $plan);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Tenant\TenantFactory::new();
    }
} 