<?php

namespace Database\Seeders\Event;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event\Event;
use App\Models\Event\Venue\Venue;
use App\Models\Tenant\Tenant;
use Carbon\Carbon;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->command->warn('No tenants found. Please run TenantsSeeder first.');
            return;
        }
        
        foreach ($tenants as $tenant) {
            $this->command->info("Creating events for tenant: {$tenant->name}");
            
            // Create different types of events
            $this->createConferenceEvent($tenant);
            $this->createWorkshopEvent($tenant);
            $this->createWebinarEvent($tenant);
            $this->createHackathonEvent($tenant);
            $this->createMeetupEvents($tenant);
            
            // Create random events
            $randomCount = rand(3, 8);
            for ($i = 0; $i < $randomCount; $i++) {
                $event = Event::factory()
                    ->for($tenant)
                    ->create();
                    
                $this->createVenuesForEvent($event);
            }
        }
        
        $this->command->info('Events seeded successfully!');
    }
    
    protected function createConferenceEvent(Tenant $tenant): void
    {
        $event = Event::factory()
            ->for($tenant)
            ->ofType(Event::TYPE_CONFERENCE)
            ->published()
            ->featured()
            ->withCapacity(500)
            ->create([
                'title' => 'Annual Tech Conference 2025',
                'description' => 'Join us for the biggest technology conference of the year. Network with industry leaders, attend workshops, and discover the latest innovations.',
            ]);
            
        // Create multiple venues for conference
        Venue::factory()
            ->for($tenant)
            ->for($event)
            ->ofType(Venue::TYPE_AUDITORIUM)
            ->available()
            ->create([
                'name' => 'Main Auditorium',
                'capacity' => 500,
            ]);
            
        Venue::factory()
            ->for($tenant)
            ->for($event)
            ->ofType(Venue::TYPE_CONFERENCE_ROOM)
            ->available()
            ->count(3)
            ->create();
            
        Venue::factory()
            ->for($tenant)
            ->for($event)
            ->ofType(Venue::TYPE_EXHIBITION_HALL)
            ->available()
            ->create([
                'name' => 'Exhibition Hall A',
                'capacity' => 1000,
            ]);
    }
    
    protected function createWorkshopEvent(Tenant $tenant): void
    {
        $event = Event::factory()
            ->for($tenant)
            ->ofType(Event::TYPE_WORKSHOP)
            ->published()
            ->withCapacity(30)
            ->create([
                'title' => 'Advanced Web Development Workshop',
                'description' => 'Hands-on workshop covering modern web development techniques including React, TypeScript, and cloud deployment.',
            ]);
            
        Venue::factory()
            ->for($tenant)
            ->for($event)
            ->ofType(Venue::TYPE_WORKSHOP_ROOM)
            ->available()
            ->create([
                'name' => 'Tech Lab 1',
                'capacity' => 30,
            ]);
    }
    
    protected function createWebinarEvent(Tenant $tenant): void
    {
        $event = Event::factory()
            ->for($tenant)
            ->ofType(Event::TYPE_WEBINAR)
            ->published()
            ->withCapacity(1000)
            ->create([
                'title' => 'Digital Marketing Strategies 2025',
                'description' => 'Learn the latest digital marketing strategies and tools to grow your business online.',
            ]);
            
        Venue::factory()
            ->for($tenant)
            ->for($event)
            ->virtual()
            ->available()
            ->create([
                'name' => 'Virtual Studio 1',
                'capacity' => 1000,
            ]);
    }
    
    protected function createHackathonEvent(Tenant $tenant): void
    {
        $event = Event::factory()
            ->for($tenant)
            ->ofType(Event::TYPE_HACKATHON)
            ->published()
            ->withCapacity(200)
            ->create([
                'title' => '48-Hour AI Hackathon',
                'description' => 'Build innovative AI solutions in 48 hours. Great prizes, mentorship, and networking opportunities.',
                'start_date' => Carbon::now('UTC')->addMonths(2),
                'end_date' => Carbon::now('UTC')->addMonths(2)->addDays(2),
            ]);
            
        Venue::factory()
            ->for($tenant)
            ->for($event)
            ->ofType(Venue::TYPE_HALL)
            ->available()
            ->create([
                'name' => 'Innovation Hall',
                'capacity' => 200,
            ]);
    }
    
    protected function createMeetupEvents(Tenant $tenant): void
    {
        // Past meetup
        $pastMeetup = Event::factory()
            ->for($tenant)
            ->ofType(Event::TYPE_MEETUP)
            ->completed()
            ->create([
                'title' => 'Laravel Developers Meetup - December',
                'description' => 'Monthly gathering of Laravel developers to share knowledge and network.',
            ]);
            
        Venue::factory()
            ->for($tenant)
            ->for($pastMeetup)
            ->ofType(Venue::TYPE_MEETING_ROOM)
            ->available()
            ->create();
            
        // Upcoming meetup
        $upcomingMeetup = Event::factory()
            ->for($tenant)
            ->ofType(Event::TYPE_MEETUP)
            ->published()
            ->create([
                'title' => 'Laravel Developers Meetup - January',
                'description' => 'Monthly gathering of Laravel developers to share knowledge and network.',
                'start_date' => Carbon::now('UTC')->addWeeks(2),
                'end_date' => Carbon::now('UTC')->addWeeks(2)->addHours(3),
            ]);
            
        Venue::factory()
            ->for($tenant)
            ->for($upcomingMeetup)
            ->ofType(Venue::TYPE_MEETING_ROOM)
            ->available()
            ->create();
    }
    
    protected function createVenuesForEvent(Event $event): void
    {
        $venueCount = match($event->type) {
            Event::TYPE_CONFERENCE => rand(3, 6),
            Event::TYPE_SUMMIT, Event::TYPE_EXPO => rand(2, 4),
            Event::TYPE_WORKSHOP, Event::TYPE_TRAINING => rand(1, 2),
            default => 1,
        };
        
        $venueTypes = match($event->type) {
            Event::TYPE_CONFERENCE => [Venue::TYPE_AUDITORIUM, Venue::TYPE_CONFERENCE_ROOM, Venue::TYPE_HALL],
            Event::TYPE_WORKSHOP => [Venue::TYPE_WORKSHOP_ROOM, Venue::TYPE_CLASSROOM],
            Event::TYPE_SEMINAR => [Venue::TYPE_CONFERENCE_ROOM, Venue::TYPE_AUDITORIUM],
            Event::TYPE_WEBINAR => [Venue::TYPE_VIRTUAL_ROOM],
            Event::TYPE_TRAINING => [Venue::TYPE_CLASSROOM, Venue::TYPE_WORKSHOP_ROOM],
            Event::TYPE_EXPO => [Venue::TYPE_EXHIBITION_HALL, Venue::TYPE_HALL],
            default => [Venue::TYPE_MEETING_ROOM, Venue::TYPE_CONFERENCE_ROOM],
        };
        
        for ($i = 0; $i < $venueCount; $i++) {
            Venue::factory()
                ->for($event->tenant)
                ->for($event)
                ->ofType(fake()->randomElement($venueTypes))
                ->available()
                ->create();
        }
    }
}
