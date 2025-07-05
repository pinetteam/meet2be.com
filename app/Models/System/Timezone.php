<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Timezone extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'system_timezones';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'abbr',
        'offset',
        'is_dst',
        'dst_abbr',
        'dst_offset',
        'country_code',
        'display_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'offset' => 'integer',
            'dst_offset' => 'integer',
            'is_dst' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'iso2');
    }

    public function getOffsetHoursAttribute(): float
    {
        return $this->offset / 60;
    }

    public function getOffsetStringAttribute(): string
    {
        $hours = abs($this->offset) / 60;
        $minutes = abs($this->offset) % 60;
        $sign = $this->offset >= 0 ? '+' : '-';
        
        return sprintf('%s%02d:%02d', $sign, (int)$hours, $minutes);
    }

    public function getCurrentTimeAttribute(): Carbon
    {
        return Carbon::now($this->name);
    }

    public function convertTime(Carbon $time): Carbon
    {
        return $time->setTimezone($this->name);
    }

    public function getUtcOffsetAttribute(): string
    {
        return 'UTC' . $this->offset_string;
    }

    public function getDisplayNameWithOffsetAttribute(): string
    {
        return $this->display_name . ' (' . $this->utc_offset . ')';
    }

    public function isAheadOfUtc(): bool
    {
        return $this->offset > 0;
    }

    public function isBehindUtc(): bool
    {
        return $this->offset < 0;
    }

    public function isUtc(): bool
    {
        return $this->offset === 0;
    }
} 