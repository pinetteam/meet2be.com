<?php

namespace App\Models\Tenant;

use App\Models\System\Country;
use App\Models\System\Currency;
use App\Models\System\Language;
use App\Models\System\Timezone;
use App\Models\User\User;
use App\Traits\HasTimezone;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, HasUuids, HasTimezone;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'country_id',
        'language_id',
        'currency_id',
        'timezone_id',
        'settings',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 