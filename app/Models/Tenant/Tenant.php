<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User\User;
use App\Models\System\Country;
use App\Models\System\Language;
use App\Models\System\Currency;
use App\Models\System\Timezone;
use Database\Factories\Tenant\TenantFactory;

class Tenant extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'code',
        'name',
        'legal_name',
        'slug',
        'type',
        'email',
        'phone',
        'fax',
        'website',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country_id',
        'language_id',
        'currency_id',
        'timezone_id',
        'date_format',
        'time_format',
        'logo_name',
        'favicon_name',
        'plan',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'max_users',
        'max_storage_mb',
        'max_events',
        'current_users',
        'current_storage_mb',
        'current_events',
        'settings',
        'features',
        'metadata',
        'owner_id',
        'created_by',
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
        return $this->status === 'active';
    }
    
    public function isTrial(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }
    
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->subscription_ends_at && $this->subscription_ends_at->isPast());
    }
    
    public function hasReachedUserLimit(): bool
    {
        return $this->current_users >= $this->max_users;
    }
    
    public function hasReachedStorageLimit(): bool
    {
        return $this->current_storage_mb >= $this->max_storage_mb;
    }
    
    public function hasReachedEventLimit(): bool
    {
        return $this->current_events >= $this->max_events;
    }
    
    public function getRemainingTrialDays(): int
    {
        if (!$this->isTrial()) {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }
    
    public function updateActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }
    
    public function getStorageUsagePercentage(): float
    {
        if ($this->max_storage_mb === 0) {
            return 0;
        }
        
        return round(($this->current_storage_mb / $this->max_storage_mb) * 100, 2);
    }
    
    public function getUserUsagePercentage(): float
    {
        if ($this->max_users === 0) {
            return 0;
        }
        
        return round(($this->current_users / $this->max_users) * 100, 2);
    }
    
    public function getEventUsagePercentage(): float
    {
        if ($this->max_events === 0) {
            return 0;
        }
        
        return round(($this->current_events / $this->max_events) * 100, 2);
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
        });
    }
    
    protected static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(\Str::random(3) . sprintf('%04d', rand(1, 9999)));
        } while (static::where('code', $code)->exists());
        
        return $code;
    }
    
    protected static function newFactory()
    {
        return TenantFactory::new();
    }
} 