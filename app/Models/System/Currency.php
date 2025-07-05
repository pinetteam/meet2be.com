<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Currency extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'system_currencies';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'code',
        'numeric_code',
        'name_en',
        'name_plural_en',
        'symbol',
        'symbol_native',
        'symbol_position',
        'decimal_digits',
        'decimal_separator',
        'thousands_separator',
        'exchange_rate',
        'is_active',
        'is_crypto',
    ];

    protected function casts(): array
    {
        return [
            'decimal_digits' => 'integer',
            'exchange_rate' => 'decimal:10',
            'is_active' => 'boolean',
            'is_crypto' => 'boolean',
        ];
    }

    public function countries()
    {
        return $this->hasMany(Country::class, 'currency_code', 'code');
    }

    public function formatAmount($amount, bool $includeSymbol = true): string
    {
        $formatted = number_format(
            $amount,
            $this->decimal_digits,
            $this->decimal_separator,
            $this->thousands_separator
        );

        if (!$includeSymbol) {
            return $formatted;
        }

        return $this->symbol_position === 'before'
            ? $this->symbol . $formatted
            : $formatted . $this->symbol;
    }

    public function convertTo(Currency $targetCurrency, float $amount): float
    {
        if ($this->code === $targetCurrency->code) {
            return $amount;
        }

        $baseAmount = $amount / ($this->exchange_rate ?? 1);
        return $baseAmount * ($targetCurrency->exchange_rate ?? 1);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name_en . ' (' . $this->code . ')';
    }

    public function isSymbolBefore(): bool
    {
        return $this->symbol_position === 'before';
    }

    public function isSymbolAfter(): bool
    {
        return $this->symbol_position === 'after';
    }
} 