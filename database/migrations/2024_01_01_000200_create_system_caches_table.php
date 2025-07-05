<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_caches', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->longText('value');
            $table->integer('expiration');

            // Index for expiration-based cleanup
            $table->index('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_caches');
    }
};
