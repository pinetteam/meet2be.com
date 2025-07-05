<?php

namespace Database\Factories\Event\Venue;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event\Venue\Venue;
use App\Models\Event\Event;
use App\Models\Tenant\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event\Venue>
 */
class VenueFactory extends Factory
{
    protected $model = Venue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(array_keys(Venue::TYPES));
        
        $names = [
            Venue::TYPE_HALL => [
                'Grand Hall',
                'Main Conference Hall',
                'Exhibition Hall {letter}',
                'Assembly Hall',
                'Multipurpose Hall {number}',
            ],
            Venue::TYPE_MEETING_ROOM => [
                'Meeting Room {letter}{number}',
                'Executive Meeting Room',
                'Team Meeting Space {number}',
                'Collaboration Room {letter}',
                'Discussion Room {number}',
            ],
            Venue::TYPE_CONFERENCE_ROOM => [
                'Conference Room {letter}',
                'Summit Room',
                'Board Conference Room',
                'Video Conference Suite {number}',
                'Executive Conference Room',
            ],
            Venue::TYPE_AUDITORIUM => [
                'Main Auditorium',
                'Theater {letter}',
                'Lecture Hall {number}',
                'Performance Auditorium',
                'Academic Auditorium',
            ],
            Venue::TYPE_CLASSROOM => [
                'Classroom {letter}{number}',
                'Training Room {number}',
                'Learning Lab {letter}',
                'Tutorial Room {number}',
                'Study Room {letter}{number}',
            ],
            Venue::TYPE_BALLROOM => [
                'Grand Ballroom',
                'Crystal Ballroom',
                'Royal Ballroom',
                'Garden Ballroom',
                'Heritage Ballroom',
            ],
            Venue::TYPE_VIRTUAL_ROOM => [
                'Virtual Studio {number}',
                'Online Meeting Room {letter}',
                'Digital Conference Space',
                'Webinar Studio {number}',
                'Streaming Room {letter}',
            ],
        ];
        
        $nameTemplate = $names[$type] ?? ['Venue {letter}{number}'];
        $name = $this->faker->randomElement($nameTemplate);
        
        $replacements = [
            '{letter}' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            '{number}' => $this->faker->numberBetween(101, 599),
        ];
        
        foreach ($replacements as $placeholder => $value) {
            $name = str_replace($placeholder, $value, $name);
        }
        
        $capacity = $this->getCapacityByType($type);
        
        return [
            'tenant_id' => Tenant::factory(),
            'event_id' => Event::factory(),
            'name' => $name,
            'description' => $this->faker->optional(0.8)->paragraph(),
            'type' => $type,
            'capacity' => $capacity,
            'location' => $this->generateLocation(),
            'has_wifi' => $this->faker->boolean(90),
            'wifi_ssid' => function (array $attributes) {
                return $attributes['has_wifi'] ? 'Event_WiFi_' . $this->faker->randomNumber(4) : null;
            },
            'wifi_password' => function (array $attributes) {
                return $attributes['has_wifi'] ? $this->faker->password(8, 12) : null;
            },
            'is_accessible' => $this->faker->boolean(85),
            'accessibility_features' => function (array $attributes) {
                if (!$attributes['is_accessible']) return [];
                
                $features = ['wheelchair', 'elevator', 'ramp', 'hearing_loop', 'braille', 'accessible_restroom', 'accessible_parking'];
                return $this->faker->randomElements($features, $this->faker->numberBetween(2, 5));
            },
            'contact_person' => $this->faker->optional(0.6)->name(),
            'contact_phone' => $this->faker->optional(0.6)->phoneNumber(),
            'contact_email' => $this->faker->optional(0.6)->safeEmail(),
            'is_active' => $this->faker->boolean(95),
            'status' => $this->faker->randomElement(array_keys(Venue::STATUSES)),
            'last_used_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', 'now'),
            'usage_count' => $this->faker->numberBetween(0, 100),
        ];
    }
    
    protected function getCapacityByType(string $type): int
    {
        return match ($type) {
            Venue::TYPE_BOARDROOM => $this->faker->numberBetween(8, 20),
            Venue::TYPE_MEETING_ROOM => $this->faker->numberBetween(10, 30),
            Venue::TYPE_CLASSROOM => $this->faker->numberBetween(20, 50),
            Venue::TYPE_CONFERENCE_ROOM => $this->faker->numberBetween(30, 100),
            Venue::TYPE_WORKSHOP_ROOM => $this->faker->numberBetween(20, 60),
            Venue::TYPE_AUDITORIUM => $this->faker->numberBetween(100, 500),
            Venue::TYPE_HALL => $this->faker->numberBetween(100, 1000),
            Venue::TYPE_EXHIBITION_HALL => $this->faker->numberBetween(200, 2000),
            Venue::TYPE_BALLROOM => $this->faker->numberBetween(150, 800),
            Venue::TYPE_VIRTUAL_ROOM => $this->faker->numberBetween(50, 1000),
            Venue::TYPE_OUTDOOR_SPACE => $this->faker->numberBetween(50, 500),
            default => $this->faker->numberBetween(20, 100),
        };
    }
    
    protected function generateLocation(): string
    {
        $templates = [
            'Room {number}',
            'Suite {letter}',
            'Hall {letter}{number}',
            'Space {number}',
            '{floor} Floor, Room {number}',
            'Section {letter}',
        ];
        
        $location = $this->faker->randomElement($templates);
        
        $replacements = [
            '{letter}' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            '{number}' => $this->faker->numberBetween(101, 599),
            '{floor}' => $this->faker->randomElement(['First', 'Second', 'Third', 'Fourth', 'Fifth']),
        ];
        
        foreach ($replacements as $placeholder => $value) {
            $location = str_replace($placeholder, $value, $location);
        }
        
        return $location;
    }
    

    
    public function available()
    {
        return $this->state(fn (array $attributes) => [
            'status' => Venue::STATUS_AVAILABLE,
            'is_active' => true,
        ]);
    }
    
    public function occupied()
    {
        return $this->state(fn (array $attributes) => [
            'status' => Venue::STATUS_OCCUPIED,
            'last_used_at' => now(),
        ]);
    }
    
    public function maintenance()
    {
        return $this->state(fn (array $attributes) => [
            'status' => Venue::STATUS_MAINTENANCE,
            'is_active' => false,
        ]);
    }
    
    public function withCapacity(int $capacity)
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => $capacity,
        ]);
    }
    
    public function ofType(string $type)
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
            'capacity' => $this->getCapacityByType($type),
        ]);
    }
    
    public function virtual()
    {
        return $this->state(fn (array $attributes) => [
            'type' => Venue::TYPE_VIRTUAL_ROOM,
            'location' => 'Online Platform',
        ]);
    }
}
