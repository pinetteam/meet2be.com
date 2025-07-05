<?php

namespace Database\Factories\Event;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event\Event;
use App\Models\Tenant\Tenant;
use App\Models\System\Timezone;
use App\Models\System\Language;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+3 months');
        $endDate = clone $startDate;
        $endDate->add(new \DateInterval('P' . $this->faker->numberBetween(1, 5) . 'D'));
        
        $types = array_keys(Event::TYPES);
        $type = $this->faker->randomElement($types);
        
        $titles = [
            Event::TYPE_CONFERENCE => [
                'Annual Tech Conference {year}',
                'Global Innovation Summit {year}',
                'International Business Conference',
                '{city} Tech Conference {year}',
                'Future of {industry} Conference',
            ],
            Event::TYPE_WORKSHOP => [
                '{skill} Workshop for Beginners',
                'Advanced {skill} Workshop',
                'Hands-on {technology} Workshop',
                'Professional {skill} Development',
                'Intensive {skill} Training Workshop',
            ],
            Event::TYPE_SEMINAR => [
                '{topic} Seminar Series',
                'Expert Talk: {topic}',
                'Professional Development Seminar',
                '{industry} Trends Seminar',
                'Leadership Excellence Seminar',
            ],
            Event::TYPE_WEBINAR => [
                'Online {topic} Masterclass',
                'Virtual {skill} Training',
                'Web Conference: {topic}',
                'Digital {industry} Summit',
                'Remote {skill} Workshop',
            ],
            Event::TYPE_MEETUP => [
                '{city} {technology} Meetup',
                '{hobby} Enthusiasts Gathering',
                'Monthly {topic} Meetup',
                '{city} Professionals Network',
                '{industry} Community Meetup',
            ],
            Event::TYPE_HACKATHON => [
                '{city} Hackathon {year}',
                '48-Hour Coding Challenge',
                '{technology} Hackathon',
                'Innovation Challenge {year}',
                'Global Hack {topic}',
            ],
        ];
        
        $titleTemplate = $titles[$type] ?? $titles[Event::TYPE_CONFERENCE];
        $title = $this->faker->randomElement($titleTemplate);
        
        $replacements = [
            '{year}' => date('Y'),
            '{city}' => $this->faker->city,
            '{industry}' => $this->faker->randomElement(['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing']),
            '{skill}' => $this->faker->randomElement(['Leadership', 'Communication', 'Project Management', 'Data Analysis', 'Design Thinking']),
            '{technology}' => $this->faker->randomElement(['AI', 'Blockchain', 'Cloud Computing', 'IoT', 'Machine Learning']),
            '{topic}' => $this->faker->randomElement(['Digital Transformation', 'Innovation', 'Sustainability', 'Future Trends', 'Best Practices']),
            '{hobby}' => $this->faker->randomElement(['Photography', 'Writing', 'Gaming', 'Music', 'Art']),
        ];
        
        foreach ($replacements as $placeholder => $value) {
            $title = str_replace($placeholder, $value, $title);
        }
        
        return [
            'tenant_id' => Tenant::factory(),
            'title' => $title,
            'description' => $this->faker->paragraphs(3, true),
            'slug' => \Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'type' => $type,
            'status' => $this->faker->randomElement(['draft', 'published', 'ongoing', 'completed', 'cancelled']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'timezone_id' => $this->faker->randomElement(Timezone::pluck('id')->toArray()),
            'language_id' => $this->faker->randomElement(Language::pluck('id')->toArray()),
            'is_public' => $this->faker->boolean(70),
            'is_featured' => $this->faker->boolean(20),
            'require_approval' => $this->faker->boolean(60),
            'max_participants' => $this->faker->optional(0.7)->numberBetween(10, 1000),
            'current_participants' => function (array $attributes) {
                return $attributes['max_participants'] 
                    ? $this->faker->numberBetween(0, (int)($attributes['max_participants'] * 0.8))
                    : 0;
            },
            'organizers' => $this->generateOrganizers(),
            'contact_info' => $this->generateContactInfo(),
            'settings' => $this->generateSettings(),
            'metadata' => [],
            'last_activity_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
    
    protected function generateOrganizers(): array
    {
        $count = $this->faker->numberBetween(1, 3);
        $organizers = [];
        
        for ($i = 0; $i < $count; $i++) {
            $organizers[] = [
                'name' => $this->faker->name,
                'role' => $this->faker->randomElement(['Event Manager', 'Coordinator', 'Director', 'Lead Organizer']),
                'email' => $this->faker->safeEmail,
                'phone' => $this->faker->phoneNumber,
            ];
        }
        
        return $organizers;
    }
    
    protected function generateContactInfo(): array
    {
        return [
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->optional()->url,
            'social' => [
                'twitter' => $this->faker->optional()->userName,
                'linkedin' => $this->faker->optional()->userName,
                'facebook' => $this->faker->optional()->userName,
            ],
        ];
    }
    
    protected function generateSettings(): array
    {
        return [
            'registration_opens_at' => $this->faker->optional()->dateTimeBetween('-2 weeks', 'now'),
            'registration_closes_at' => $this->faker->optional()->dateTimeBetween('now', '+2 weeks'),
            'early_bird_discount' => $this->faker->optional()->numberBetween(10, 30),
            'allow_cancellations' => $this->faker->boolean(),
            'cancellation_deadline_hours' => $this->faker->randomElement([24, 48, 72]),
            'send_reminders' => $this->faker->boolean(80),
            'reminder_intervals' => [24, 72, 168], // 1 day, 3 days, 1 week
        ];
    }
    
    public function draft()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'is_public' => false,
            'is_featured' => false,
        ]);
    }
    
    public function published()
    {
        return $this->state(function (array $attributes) {
            $start = $this->faker->dateTimeBetween('+1 week', '+2 months');
            $end = clone $start;
            $end->add(new \DateInterval('P' . $this->faker->numberBetween(1, 3) . 'D'));
            
            return [
                'status' => 'published',
                'start_date' => $start,
                'end_date' => $end,
            ];
        });
    }
    
    public function ongoing()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ongoing',
            'start_date' => $this->faker->dateTimeBetween('-2 days', 'now'),
            'end_date' => $this->faker->dateTimeBetween('+1 day', '+5 days'),
        ]);
    }
    
    public function completed()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'start_date' => $this->faker->dateTimeBetween('-2 months', '-1 week'),
            'end_date' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
        ]);
    }
    
    public function cancelled()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
    
    public function featured()
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_public' => true,
            'status' => 'published',
        ]);
    }
    
    public function private()
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
            'require_approval' => true,
        ]);
    }
    
    public function withCapacity(int $capacity)
    {
        return $this->state(fn (array $attributes) => [
            'max_participants' => $capacity,
            'current_participants' => $this->faker->numberBetween(0, (int)($capacity * 0.8)),
        ]);
    }
    
    public function full()
    {
        return $this->state(fn (array $attributes) => [
            'max_participants' => $this->faker->numberBetween(50, 200),
            'current_participants' => $attributes['max_participants'] ?? 100,
        ]);
    }
    
    public function ofType(string $type)
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}
