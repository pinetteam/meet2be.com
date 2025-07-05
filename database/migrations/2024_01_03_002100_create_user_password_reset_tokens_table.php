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
        Schema::create('user_password_reset_tokens', function (Blueprint $table) {
            $table->string('email');
            $table->uuid('tenant_id');
            $table->string('token');
            $table->timestamp('created_at')->nullable();

            // Foreign key constraint
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Primary key: email + tenant_id combination
            $table->primary(['email', 'tenant_id']);
            
            // Index for faster token lookups
            $table->index(['email', 'tenant_id', 'token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_password_reset_tokens');
    }
};
