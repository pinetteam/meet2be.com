<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_country_language', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->uuid('language_id');
            $table->boolean('is_official')->default(false); // Is official language
            $table->boolean('is_primary')->default(false); // Is primary language
            $table->decimal('percentage', 5, 2)->nullable(); // Percentage of speakers
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('country_id')->references('id')->on('system_countries')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('system_languages')->onDelete('cascade');
            
            // Indexes
            $table->unique(['country_id', 'language_id']);
            $table->index('is_official');
            $table->index('is_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_country_language');
    }
}; 