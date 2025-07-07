<?php

namespace Database\Factories\Tenant;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tenant\Tenant;
use App\Models\System\Country;
use App\Models\System\Language;
use App\Models\System\Currency;
use App\Models\System\Timezone;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $companyName = fake()->company();
        $slug = Str::slug($companyName);
        
        return [
            'name' => $companyName,
            'legal_name' => fake()->optional(0.7)->randomElement([
                $companyName . ' Inc.',
                $companyName . ' LLC',
                $companyName . ' Ltd.',
                $companyName . ' Corporation',
                $companyName,
            ]),
            'slug' => $slug,
            'type' => fake()->randomElement(['individual', 'business', 'enterprise']),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'fax' => fake()->optional(0.3)->phoneNumber(),
            'website' => fake()->optional(0.6)->url(),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional(0.3)->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country_id' => Country::where('iso2', fake()->randomElement(['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'TR']))->first()?->id,
            'language_id' => Language::where('iso_639_1', fake()->randomElement(['en', 'tr', 'de', 'fr', 'es']))->first()?->id,
            'currency_id' => Currency::where('code', fake()->randomElement(['USD', 'EUR', 'TRY', 'AED']))->first()?->id,
            'timezone_id' => Timezone::where('is_active', true)->inRandomOrder()->first()?->id,
            'date_format' => fake()->randomElement(['Y-m-d', 'd/m/Y', 'm/d/Y', 'd.m.Y']),
            'time_format' => fake()->randomElement(['H:i', 'h:i A', 'H:i:s']),
            'logo_name' => fake()->optional(0.5)->randomElement(['logo-1.png', 'logo-2.png', 'logo-3.png', 'logo-4.png', 'logo-5.png']),
            'favicon_name' => fake()->optional(0.3)->randomElement(['favicon-1.ico', 'favicon-2.ico', 'favicon-3.ico']),
            'plan' => fake()->randomElement(['basic', 'pro', 'enterprise']),
            'status' => fake()->randomElement(['active', 'active', 'active', 'trial', 'inactive']),
            'trial_ends_at' => function (array $attributes) {
                return $attributes['status'] === 'trial' ? Carbon::now('UTC')->addDays(fake()->numberBetween(1, 30)) : null;
            },
            'subscription_ends_at' => function (array $attributes) {
                return in_array($attributes['status'], ['active', 'trial']) 
                    ? Carbon::now('UTC')->addMonths(fake()->numberBetween(1, 12)) 
                    : null;
            },
            'max_users' => function (array $attributes) {
                return match($attributes['plan']) {
                    'basic' => fake()->numberBetween(5, 10),
                    'pro' => fake()->numberBetween(20, 50),
                    'enterprise' => fake()->numberBetween(100, 1000),
                    default => 5,
                };
            },
            'max_storage_mb' => function (array $attributes) {
                return match($attributes['plan']) {
                    'basic' => fake()->numberBetween(1024, 5120), // 1-5 GB
                    'pro' => fake()->numberBetween(10240, 51200), // 10-50 GB
                    'enterprise' => fake()->numberBetween(102400, 1048576), // 100GB-1TB
                    default => 1024,
                };
            },
            'max_events' => function (array $attributes) {
                return match($attributes['plan']) {
                    'basic' => fake()->numberBetween(5, 10),
                    'pro' => fake()->numberBetween(25, 100),
                    'enterprise' => fake()->numberBetween(500, 9999),
                    default => 10,
                };
            },
            'current_users' => function (array $attributes) {
                return fake()->numberBetween(0, (int)($attributes['max_users'] * 0.8));
            },
            'current_storage_mb' => function (array $attributes) {
                return fake()->numberBetween(0, (int)($attributes['max_storage_mb'] * 0.6));
            },
            'current_events' => function (array $attributes) {
                return fake()->numberBetween(0, (int)($attributes['max_events'] * 0.7));
            },
            'settings' => [
                'notifications' => [
                    'email' => fake()->boolean(80),
                    'sms' => fake()->boolean(30),
                    'push' => fake()->boolean(60),
                ],
                'billing' => [
                    'auto_renew' => fake()->boolean(90),
                    'invoice_prefix' => strtoupper(fake()->lexify('???')),
                ],
                'security' => [
                    'two_factor' => fake()->boolean(40),
                    'ip_whitelist' => fake()->boolean(20),
                ],
            ],
            'features' => function (array $attributes) {
                $allFeatures = [
                    'api_access' => true,
                    'custom_domain' => true,
                    'advanced_reporting' => true,
                    'team_collaboration' => true,
                    'priority_support' => true,
                    'white_label' => true,
                    'bulk_export' => true,
                    'audit_logs' => true,
                    'webhooks' => true,
                    'sso' => true,
                ];
                
                return match($attributes['plan']) {
                    'basic' => array_intersect_key($allFeatures, array_flip(['api_access', 'team_collaboration'])),
                    'pro' => array_intersect_key($allFeatures, array_flip(['api_access', 'team_collaboration', 'custom_domain', 'advanced_reporting', 'bulk_export', 'audit_logs'])),
                    'enterprise' => $allFeatures,
                    default => [],
                };
            },
            'metadata' => [
                'source' => fake()->randomElement(['website', 'referral', 'direct', 'social', 'search']),
                'campaign' => fake()->optional(0.4)->word(),
                'referrer' => fake()->optional(0.3)->userName(),
            ],
            'last_activity_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'created_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
            'updated_at' => function (array $attributes) {
                return fake()->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
    
    public function trial(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'trial',
                'plan' => 'basic',
                'trial_ends_at' => Carbon::now('UTC')->addDays(14),
                'subscription_ends_at' => null,
            ];
        });
    }
    
    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'active',
                'subscription_ends_at' => Carbon::now('UTC')->addYear(),
            ];
        });
    }
    
    public function suspended(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'suspended',
                'subscription_ends_at' => Carbon::now('UTC')->subDays(fake()->numberBetween(1, 30)),
            ];
        });
    }
    
    public function expired(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'expired',
                'subscription_ends_at' => Carbon::now('UTC')->subDays(fake()->numberBetween(31, 365)),
            ];
        });
    }
    
    public function withOwner($user = null): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'owner_id' => $user?->id ?? \App\Models\User\User::factory()->create()->id,
                'created_by' => $user?->id ?? $attributes['owner_id'] ?? \App\Models\User\User::factory()->create()->id,
            ];
        });
    }
    
    public function basic(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'plan' => 'basic',
                'max_users' => 5,
                'max_storage_mb' => 1024,
                'max_events' => 10,
            ];
        });
    }
    
    public function pro(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'plan' => 'pro',
                'max_users' => 50,
                'max_storage_mb' => 51200,
                'max_events' => 100,
            ];
        });
    }
    
    public function enterprise(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'plan' => 'enterprise',
                'type' => 'enterprise',
                'max_users' => 1000,
                'max_storage_mb' => 1048576,
                'max_events' => 9999,
            ];
        });
    }
} 