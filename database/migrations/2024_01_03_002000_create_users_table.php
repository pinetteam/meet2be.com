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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('username', 80);
            $table->string('email', 255);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('phone', 20)->nullable();
            $table->string('status', 15)->default('active')->index();
            $table->timestamp('last_login_at')->nullable()->index();
            $table->string('last_ip_address', 45)->nullable();
            $table->string('last_user_agent', 255)->nullable();
            $table->enum('type', ['admin', 'screener', 'operator'])->default('operator');
            $table->json('settings')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Unique constraints per tenant
            $table->unique(['tenant_id', 'email']);
            $table->unique(['tenant_id', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
