<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Tenant\Tenant;
use Database\Factories\User\UserFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    const TYPE_ADMIN = 'admin';
    const TYPE_SCREENER = 'screener';
    const TYPE_OPERATOR = 'operator';

    const TYPES = [
        self::TYPE_ADMIN => 'Admin',
        self::TYPE_SCREENER => 'Screener',
        self::TYPE_OPERATOR => 'Operator',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_SUSPENDED => 'Suspended',
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
        'password',
        'first_name',
        'last_name',
        'phone',
        'status',
        'type',
        'settings',
        'last_login_at',
        'last_ip_address',
        'last_user_agent',
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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
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
            'last_login_at' => now(),
            'last_ip_address' => $ipAddress,
            'last_user_agent' => $userAgent ? substr($userAgent, 0, 250) : null,
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
}
