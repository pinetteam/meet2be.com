<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_timezones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique(); // America/New_York, Europe/Istanbul
            $table->string('abbr'); // EST, TRT
            $table->integer('offset'); // Offset in minutes from UTC (-300 for EST)
            $table->boolean('is_dst')->default(false); // Is currently in daylight saving time
            $table->string('dst_abbr')->nullable(); // EDT for Eastern Daylight Time
            $table->integer('dst_offset')->nullable(); // DST offset in minutes
            $table->string('country_code', 2)->nullable(); // US, TR
            $table->string('display_name'); // Eastern Standard Time
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index('country_code');
            $table->index('is_active');
            $table->index('offset');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_timezones');
    }
}; 