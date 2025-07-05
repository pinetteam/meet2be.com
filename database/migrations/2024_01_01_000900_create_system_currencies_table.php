<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_currencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 5)->unique(); // USD, EUR, TRY, USDT
            $table->string('numeric_code', 3)->unique(); // 840, 978, 949
            $table->string('name_en'); // US Dollar, Euro, Turkish Lira
            $table->string('name_plural_en'); // US Dollars, Euros, Turkish Liras
            $table->string('symbol'); // $, €, ₺
            $table->string('symbol_native'); // $, €, ₺
            $table->enum('symbol_position', ['before', 'after'])->default('before'); // $100 or 100₺
            $table->unsignedTinyInteger('decimal_digits')->default(2); // 2 for USD, 0 for JPY
            $table->string('decimal_separator', 1)->default('.'); // . or ,
            $table->string('thousands_separator', 1)->default(','); // , or .
            $table->decimal('exchange_rate', 20, 10)->nullable(); // Current exchange rate to base currency
            $table->boolean('is_active')->default(true);
            $table->boolean('is_crypto')->default(false); // Is cryptocurrency
            $table->timestamps();
            
            // Indexes for performance
            $table->index('code');
            $table->index('is_active');
            $table->index('is_crypto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_currencies');
    }
}; 