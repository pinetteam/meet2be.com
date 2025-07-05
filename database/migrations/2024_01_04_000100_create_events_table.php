<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            
            // Basic Information
            $table->string('code', 20); // EVT001, CNF002
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('slug', 100);
            
            // Event Details
            $table->enum('type', [
                'conference', 
                'workshop', 
                'seminar', 
                'webinar', 
                'meetup', 
                'meeting',
                'summit',
                'symposium',
                'training',
                'course',
                'bootcamp',
                'hackathon',
                'networking',
                'panel',
                'roundtable',
                'expo',
                'other'
            ])->default('conference');
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            
            // Date & Time
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->uuid('timezone_id')->nullable();
            
            // Localization
            $table->uuid('language_id')->nullable();
            
            // Visibility & Access
            $table->boolean('is_public')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('require_approval')->default(true);
            
            // Capacity
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            
            // Media
            $table->string('cover_image_name')->nullable();
            $table->string('logo_name')->nullable();
            
            // Contact & Organization
            $table->json('organizers')->nullable(); // [{name, role, email, phone}]
            $table->json('contact_info')->nullable(); // {email, phone, website, social}
            
            // Configuration
            $table->json('settings')->nullable(); // General settings
            $table->json('metadata')->nullable(); // Additional data
            
            // Activity Tracking
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('timezone_id')->references('id')->on('system_timezones')->nullOnDelete();
            $table->foreign('language_id')->references('id')->on('system_languages')->nullOnDelete();
            
            // Indexes for performance
            $table->index('tenant_id');
            $table->index('status');
            $table->index('type');
            $table->index(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'status', 'start_date']);
            $table->index(['status', 'is_public', 'start_date']);
            $table->index(['is_featured', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('last_activity_at');
            
            // Unique constraints per tenant
            $table->unique(['tenant_id', 'slug']);
            $table->unique(['tenant_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
}; 