<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_venues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('event_id');
            
            // Basic Information
            $table->string('code', 20); // VEN001, HALL002
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('type', [
                'hall',
                'meeting_room',
                'conference_room',
                'auditorium',
                'classroom',
                'workshop_room',
                'exhibition_hall',
                'ballroom',
                'boardroom',
                'virtual_room',
                'outdoor_space',
                'other'
            ])->default('hall');
            
            // Capacity & Location
            $table->integer('capacity')->default(0);
            $table->string('location', 200)->nullable(); // Building A, Floor 2, Room 201
            
            // WiFi Information
            $table->boolean('has_wifi')->default(true);
            $table->string('wifi_ssid', 150)->nullable();
            $table->string('wifi_password', 150)->nullable();
            
            // Accessibility
            $table->boolean('is_accessible')->default(true);
            $table->json('accessibility_features')->nullable(); // ["wheelchair", "elevator", "hearing_loop"]
            
            // Contact Information
            $table->string('contact_person', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('contact_email', 255)->nullable();
            
            // Media
            $table->string('image_name')->nullable();
            
            
            // Status Management
            $table->boolean('is_active')->default(true);
            $table->enum('status', [
                'available',
                'occupied',
                'reserved',
                'maintenance',
                'closed'
            ])->default('available');
            
            // Usage Tracking
            $table->timestamp('last_used_at')->nullable();
            $table->integer('usage_count')->default(0);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            
            // Indexes
            $table->index('tenant_id');
            $table->index('event_id');
            $table->index('type');
            $table->index('capacity');
            $table->index('status');
            $table->index('is_active');
            $table->index(['tenant_id', 'status', 'event_id']);
            $table->index(['event_id', 'status']);
            $table->index(['type', 'capacity']);
            $table->index('last_used_at');
            $table->index('usage_count');
            
            // Unique constraints per tenant
            $table->unique(['tenant_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_venues');
    }
}; 