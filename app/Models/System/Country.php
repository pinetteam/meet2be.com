<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Country extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'system_countries';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'iso2',
        'iso3',
        'numeric_code',
        'name_en',
        'name_native',
        'official_name_en',
        'capital',
        'continent_code',
        'region',
        'subregion',
        'latitude',
        'longitude',
        'phone_code',
        'currency_code',
        'tld',
        'languages',
        'timezones',
        'is_eu',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'languages' => 'array',
            'timezones' => 'array',
            'is_eu' => 'boolean',
            'is_active' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function spokenLanguages()
    {
        return $this->belongsToMany(Language::class, 'system_country_language', 'country_id', 'language_id')
            ->withPivot('is_official', 'is_primary', 'percentage')
            ->withTimestamps();
    }

    public function officialLanguages()
    {
        return $this->spokenLanguages()->wherePivot('is_official', true);
    }

    public function primaryLanguage()
    {
        return $this->spokenLanguages()->wherePivot('is_primary', true)->first();
    }

    public function timezoneModels()
    {
        return $this->hasMany(Timezone::class, 'country_code', 'iso2');
    }

    public function getFullNameAttribute(): string
    {
        return $this->official_name_en ?? $this->name_en;
    }

    public function getPhoneCodeWithPlusAttribute(): string
    {
        return '+' . ltrim($this->phone_code, '+');
    }

    public function getName(): string
    {
        $locale = app()->getLocale();
        
        // Eğer Türkçe ise ve native name varsa onu döndür
        if ($locale === 'tr' && $this->name_native) {
            return $this->name_native;
        }
        
        // Diğer durumlar için İngilizce isim döndür
        return $this->name_en;
    }
} 