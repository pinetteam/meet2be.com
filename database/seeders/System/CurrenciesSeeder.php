<?php

namespace Database\Seeders\System;

use App\Models\System\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'USD',
                'numeric_code' => '840',
                'name_en' => 'United States Dollar',
                'name_plural_en' => 'United States Dollars',
                'symbol' => '$',
                'symbol_native' => '$',
                'symbol_position' => 'before',
                'decimal_digits' => 2,
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'exchange_rate' => 1.00,
                'is_active' => true,
                'is_crypto' => false,
            ],
            [
                'code' => 'EUR',
                'numeric_code' => '978',
                'name_en' => 'Euro',
                'name_plural_en' => 'Euros',
                'symbol' => '€',
                'symbol_native' => '€',
                'symbol_position' => 'before',
                'decimal_digits' => 2,
                'decimal_separator' => ',',
                'thousands_separator' => '.',
                'exchange_rate' => 0.92,
                'is_active' => true,
                'is_crypto' => false,
            ],
            [
                'code' => 'TRY',
                'numeric_code' => '949',
                'name_en' => 'Turkish Lira',
                'name_plural_en' => 'Turkish Lira',
                'symbol' => '₺',
                'symbol_native' => '₺',
                'symbol_position' => 'after',
                'decimal_digits' => 2,
                'decimal_separator' => ',',
                'thousands_separator' => '.',
                'exchange_rate' => 28.50,
                'is_active' => true,
                'is_crypto' => false,
            ],
            [
                'code' => 'AED',
                'numeric_code' => '784',
                'name_en' => 'UAE Dirham',
                'name_plural_en' => 'UAE Dirhams',
                'symbol' => 'AED',
                'symbol_native' => 'د.إ',
                'symbol_position' => 'after',
                'decimal_digits' => 2,
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'exchange_rate' => 3.67,
                'is_active' => true,
                'is_crypto' => false,
            ],
            
            // Crypto Currencies
            [
                'code' => 'BTC',
                'numeric_code' => '991',
                'name_en' => 'Bitcoin',
                'name_plural_en' => 'Bitcoin',
                'symbol' => '₿',
                'symbol_native' => '₿',
                'symbol_position' => 'before',
                'decimal_digits' => 8,
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'exchange_rate' => 0.000023,
                'is_active' => true,
                'is_crypto' => true,
            ],
            [
                'code' => 'ETH',
                'numeric_code' => '992',
                'name_en' => 'Ethereum',
                'name_plural_en' => 'Ethereum',
                'symbol' => 'Ξ',
                'symbol_native' => 'Ξ',
                'symbol_position' => 'before',
                'decimal_digits' => 8,
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'exchange_rate' => 0.00042,
                'is_active' => true,
                'is_crypto' => true,
            ],
            [
                'code' => 'USDT',
                'numeric_code' => '993',
                'name_en' => 'Tether',
                'name_plural_en' => 'Tether',
                'symbol' => '₮',
                'symbol_native' => '₮',
                'symbol_position' => 'before',
                'decimal_digits' => 6,
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'exchange_rate' => 1.00,
                'is_active' => true,
                'is_crypto' => true,
            ],
        ];
        
        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
        
        $this->command->info('Currencies seeded successfully! Total: ' . count($currencies));
    }
} 