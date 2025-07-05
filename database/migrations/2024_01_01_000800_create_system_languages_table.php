<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('iso_639_1', 2)->unique(); // en, tr, de
            $table->string('iso_639_2', 3)->unique(); // eng, tur, deu
            $table->string('iso_639_3', 3)->unique(); // eng, tur, deu
            $table->string('name_en'); // English, Turkish, German
            $table->string('name_native'); // English, Türkçe, Deutsch
            $table->string('family')->nullable(); // Indo-European, Turkic
            $table->string('script')->nullable(); // Latin, Arabic, Cyrillic
            $table->enum('direction', ['ltr', 'rtl'])->default('ltr'); // left-to-right, right-to-left
            $table->json('countries')->nullable(); // ["US", "GB", "CA"]
            $table->integer('speakers_native')->nullable(); // Native speakers count
            $table->integer('speakers_total')->nullable(); // Total speakers count
            $table->boolean('is_active')->default(true);
            $table->boolean('is_translated')->default(false); // Is UI translated to this language
            $table->timestamps();
            
            // Indexes for performance
            $table->index('name_en');
            $table->index('is_active');
            $table->index('is_translated');
            $table->index('direction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_languages');
    }
}; 