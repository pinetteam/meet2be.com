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
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Basic Information
            $table->string('code', 20)->unique(); // ACME001, TECH002
            $table->string('name', 200);
            $table->string('legal_name', 200)->nullable();
            $table->string('slug', 100)->unique()->index();
            $table->enum('type', ['individual', 'business', 'enterprise'])->default('business');
            
            // Contact Information
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('website')->nullable();
            
            // Address Information
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->uuid('country_id')->nullable();
            
            // Localization Settings
            $table->uuid('language_id')->nullable();
            $table->uuid('currency_id')->nullable();
            $table->uuid('timezone_id')->nullable();
            $table->string('date_format', 20)->default('Y-m-d');
            $table->string('time_format', 20)->default('H:i');
            
            // Branding
            $table->string('logo_name')->nullable();
            $table->string('favicon_name')->nullable();
                        
            // Subscription & Limits
            $table->string('plan', 50)->default('basic'); // basic, pro, enterprise
            $table->enum('status', ['active', 'inactive', 'suspended', 'expired', 'trial'])->default('active');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->integer('max_users')->default(5);
            $table->integer('max_storage_mb')->default(1024); // 1GB default
            $table->integer('max_events')->default(10);
            
            // Usage Tracking
            $table->integer('current_users')->default(0);
            $table->integer('current_storage_mb')->default(0);
            $table->integer('current_events')->default(0);
            
            // Settings & Metadata
            $table->json('settings')->nullable();
            $table->json('features')->nullable(); // Enabled features
            $table->json('metadata')->nullable(); // Additional custom data
            
            // Relationships
            $table->uuid('owner_id')->nullable(); // User who owns this tenant
            $table->uuid('created_by')->nullable(); // User who created this tenant
            
            // Timestamps
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('country_id')->references('id')->on('system_countries')->nullOnDelete();
            $table->foreign('language_id')->references('id')->on('system_languages')->nullOnDelete();
            $table->foreign('currency_id')->references('id')->on('system_currencies')->nullOnDelete();
            $table->foreign('timezone_id')->references('id')->on('system_timezones')->nullOnDelete();
            
            // Indexes for performance
            $table->index('status');
            $table->index('type');
            $table->index('plan');
            $table->index('trial_ends_at');
            $table->index('subscription_ends_at');
            $table->index(['status', 'trial_ends_at']);
            $table->index(['status', 'subscription_ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
