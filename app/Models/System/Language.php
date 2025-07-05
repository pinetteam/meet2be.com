<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Language extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'system_languages';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'iso_639_1',
        'iso_639_2',
        'iso_639_3',
        'name_en',
        'name_native',
        'family',
        'script',
        'direction',
        'countries',
        'speakers_native',
        'speakers_total',
        'is_active',
        'is_translated',
    ];

    protected function casts(): array
    {
        return [
            'countries' => 'array',
            'is_active' => 'boolean',
            'is_translated' => 'boolean',
            'speakers_native' => 'integer',
            'speakers_total' => 'integer',
        ];
    }

    public function countriesSpoken()
    {
        return $this->belongsToMany(Country::class, 'system_country_language', 'language_id', 'country_id')
            ->withPivot('is_official', 'is_primary', 'percentage')
            ->withTimestamps();
    }

    public function officialCountries()
    {
        return $this->countriesSpoken()->wherePivot('is_official', true);
    }

    public function isRtl(): bool
    {
        return $this->direction === 'rtl';
    }

    public function isLtr(): bool
    {
        return $this->direction === 'ltr';
    }

    public function getCodeAttribute(): string
    {
        return $this->iso_639_1;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name_native . ' (' . $this->name_en . ')';
    }
} 