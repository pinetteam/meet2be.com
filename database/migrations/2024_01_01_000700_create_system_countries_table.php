<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_countries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('iso2', 2)->unique(); // US, TR, DE
            $table->string('iso3', 3)->unique(); // USA, TUR, DEU
            $table->string('numeric_code', 3)->unique(); // 840, 792, 276
            $table->string('name_en'); // United States
            $table->string('name_native'); // United States (native name)
            $table->string('official_name_en')->nullable(); // United States of America
            $table->string('capital')->nullable(); // Washington D.C.
            $table->string('continent_code', 2); // NA, EU, AS
            $table->string('region')->nullable(); // Northern America
            $table->string('subregion')->nullable(); // 
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('phone_code', 10); // +1, +90, +49
            $table->string('currency_code', 3)->nullable(); // USD, TRY, EUR
            $table->string('tld')->nullable(); // .us, .tr, .de
            $table->json('languages')->nullable(); // ["en", "es"]
            $table->json('timezones')->nullable(); // ["America/New_York", "America/Chicago"]
            $table->boolean('is_eu')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index('name_en');
            $table->index('phone_code');
            $table->index('currency_code');
            $table->index('is_active');
            $table->index('continent_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_countries');
    }
}; 