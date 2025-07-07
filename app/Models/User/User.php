<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Tenant\Tenant;
use App\Traits\TenantAware;
use App\Traits\HasTimezone;
use Database\Factories\User\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes, TenantAware, HasTimezone;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tenant'];

    const TYPE_ADMIN = 'admin';
    const TYPE_SCREENER = 'screener';
    const TYPE_OPERATOR = 'operator';

    const TYPES = [
        self::TYPE_ADMIN => 'user.types.admin',
        self::TYPE_SCREENER => 'user.types.screener',
        self::TYPE_OPERATOR => 'user.types.operator',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    const STATUSES = [
        self::STATUS_ACTIVE => 'user.statuses.active',
        self::STATUS_INACTIVE => 'user.statuses.inactive',
        self::STATUS_SUSPENDED => 'user.statuses.suspended',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'username',
        'email',
        'email_verified_at',
        'password',
        'first_name',
        'last_name',
        'phone',
        'status',
        'last_login_at',
        'last_ip_address',
        'last_user_agent',
        'type',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'settings' => 'array',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function isAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN;
    }

    public function isScreener(): bool
    {
        return $this->type === self::TYPE_SCREENER;
    }

    public function isOperator(): bool
    {
        return $this->type === self::TYPE_OPERATOR;
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

    public function updateLoginInfo($ipAddress = null, $userAgent = null): void
    {
        $this->update([
            'last_login_at' => $this->getCurrentTime(),
            'last_ip_address' => $ipAddress,
            'last_user_agent' => $userAgent,
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeAdmins($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }

    public function scopeScreeners($query)
    {
        return $query->where('type', self::TYPE_SCREENER);
    }

    public function scopeOperators($query)
    {
        return $query->where('type', self::TYPE_OPERATOR);
    }

    public function canAccessPortal(): bool
    {
        return in_array($this->type, [self::TYPE_ADMIN, self::TYPE_SCREENER, self::TYPE_OPERATOR]) 
            && $this->isActive();
    }

    public function getStatusText(): string
    {
        $key = self::STATUSES[$this->status] ?? null;
        return $key ? __($key) : 'Unknown';
    }

    public function getTypeText(): string
    {
        $key = self::TYPES[$this->type] ?? null;
        return $key ? __($key) : 'Unknown';
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'green',
            self::STATUS_INACTIVE => 'gray',
            self::STATUS_SUSPENDED => 'red',
            default => 'gray'
        };
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Get the number of minutes the remember me cookie should be valid for.
     *
     * @return int
     */
    public function getRememberTokenDuration(): int
    {
        return 1440; // 1 gün (dakika cinsinden)
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => trim(($attributes['first_name'] ?? '') . ' ' . ($attributes['last_name'] ?? '')),
        );
    }

    protected function typeText(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $key = self::TYPES[$attributes['type']] ?? null;
                return $key ? __($key) : $attributes['type'];
            },
        );
    }

    protected function statusText(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $key = self::STATUSES[$attributes['status']] ?? null;
                return $key ? __($key) : $attributes['status'];
            },
        );
    }

    protected function lastActivity(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (isset($attributes['last_login_at'])) {
                    return $attributes['last_login_at'] instanceof \Carbon\Carbon 
                        ? $attributes['last_login_at'] 
                        : \Carbon\Carbon::parse($attributes['last_login_at']);
                }
                return null;
            },
        );
    }
}
