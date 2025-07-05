<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Tenant;
use App\Models\User\User;

class TenantsSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo tenants
        $tenants = [
            // Enterprise tenant
            [
                'code' => 'DEMO001',
                'name' => 'Demo Enterprise Corp',
                'legal_name' => 'Demo Enterprise Corporation',
                'slug' => 'demo-enterprise',

                'type' => 'enterprise',
                'email' => 'info@demo-enterprise.com',
                'phone' => '+1 555 123 4567',
                'website' => 'https://demo-enterprise.com',
                'address_line_1' => '123 Enterprise Way',
                'city' => 'San Francisco',
                'state' => 'CA',
                'postal_code' => '94105',
                'plan' => 'enterprise',
                'status' => 'active',
                'max_users' => 1000,
                'max_storage_mb' => 1048576, // 1TB
                'max_events' => 9999,
                'current_users' => 245,
                'current_storage_mb' => 102400, // 100GB
                'current_events' => 87,
                'settings' => [
                    'notifications' => [
                        'email' => true,
                        'sms' => true,
                        'push' => true,
                    ],
                    'billing' => [
                        'auto_renew' => true,
                        'invoice_prefix' => 'ENT',
                    ],
                    'security' => [
                        'two_factor' => true,
                        'ip_whitelist' => true,
                    ],
                ],
                'features' => [
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
                ],
                'subscription_ends_at' => now()->addYear(),
                'last_activity_at' => now(),
            ],
            
            // Pro tenant
            [
                'code' => 'DEMO002',
                'name' => 'Tech Innovators Inc',
                'legal_name' => 'Tech Innovators Incorporated',
                'slug' => 'tech-innovators',

                'type' => 'business',
                'email' => 'contact@techinnovators.com',
                'phone' => '+1 555 234 5678',
                'website' => 'https://techinnovators.com',
                'address_line_1' => '456 Innovation Blvd',
                'city' => 'Austin',
                'state' => 'TX',
                'postal_code' => '78701',
                'plan' => 'pro',
                'status' => 'active',
                'max_users' => 50,
                'max_storage_mb' => 51200, // 50GB
                'max_events' => 100,
                'current_users' => 28,
                'current_storage_mb' => 15360, // 15GB
                'current_events' => 34,
                'settings' => [
                    'notifications' => [
                        'email' => true,
                        'sms' => false,
                        'push' => true,
                    ],
                    'billing' => [
                        'auto_renew' => true,
                        'invoice_prefix' => 'TI',
                    ],
                    'security' => [
                        'two_factor' => true,
                        'ip_whitelist' => false,
                    ],
                ],
                'features' => [
                    'api_access' => true,
                    'team_collaboration' => true,
                    'custom_domain' => true,
                    'advanced_reporting' => true,
                    'bulk_export' => true,
                    'audit_logs' => true,
                ],
                'subscription_ends_at' => now()->addMonths(6),
                'last_activity_at' => now()->subHours(2),
            ],
            
            // Basic tenant
            [
                'code' => 'DEMO003',
                'name' => 'Startup Solutions',
                'slug' => 'startup-solutions',

                'type' => 'business',
                'email' => 'hello@startupsolutions.io',
                'phone' => '+1 555 345 6789',
                'city' => 'Seattle',
                'state' => 'WA',
                'postal_code' => '98101',
                'plan' => 'basic',
                'status' => 'active',
                'max_users' => 10,
                'max_storage_mb' => 5120, // 5GB
                'max_events' => 10,
                'current_users' => 4,
                'current_storage_mb' => 512, // 512MB
                'current_events' => 3,
                'settings' => [
                    'notifications' => [
                        'email' => true,
                        'sms' => false,
                        'push' => false,
                    ],
                    'billing' => [
                        'auto_renew' => true,
                        'invoice_prefix' => 'SS',
                    ],
                    'security' => [
                        'two_factor' => false,
                        'ip_whitelist' => false,
                    ],
                ],
                'features' => [
                    'api_access' => true,
                    'team_collaboration' => true,
                ],
                'subscription_ends_at' => now()->addMonths(3),
                'last_activity_at' => now()->subDays(1),
            ],
            
            // Trial tenant
            [
                'code' => 'DEMO004',
                'name' => 'New Business Ventures',
                'slug' => 'new-business',

                'type' => 'business',
                'email' => 'info@newbusiness.com',
                'phone' => '+1 555 456 7890',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'plan' => 'basic',
                'status' => 'trial',
                'max_users' => 5,
                'max_storage_mb' => 1024, // 1GB
                'max_events' => 5,
                'current_users' => 2,
                'current_storage_mb' => 50,
                'current_events' => 1,
                'settings' => [
                    'notifications' => [
                        'email' => true,
                        'sms' => false,
                        'push' => false,
                    ],
                    'billing' => [
                        'auto_renew' => false,
                        'invoice_prefix' => 'NBV',
                    ],
                    'security' => [
                        'two_factor' => false,
                        'ip_whitelist' => false,
                    ],
                ],
                'features' => [
                    'api_access' => true,
                    'team_collaboration' => true,
                ],
                'trial_ends_at' => now()->addDays(7),
                'last_activity_at' => now()->subHours(5),
            ],
            
            // Individual tenant
            [
                'code' => 'DEMO005',
                'name' => 'John Doe Consulting',
                'slug' => 'john-doe',

                'type' => 'individual',
                'email' => 'john@johndoeconsulting.com',
                'phone' => '+1 555 567 8901',
                'city' => 'Chicago',
                'state' => 'IL',
                'postal_code' => '60601',
                'plan' => 'basic',
                'status' => 'active',
                'max_users' => 5,
                'max_storage_mb' => 2048, // 2GB
                'max_events' => 10,
                'current_users' => 1,
                'current_storage_mb' => 256,
                'current_events' => 4,
                'settings' => [
                    'notifications' => [
                        'email' => true,
                        'sms' => false,
                        'push' => false,
                    ],
                    'billing' => [
                        'auto_renew' => true,
                        'invoice_prefix' => 'JDC',
                    ],
                    'security' => [
                        'two_factor' => false,
                        'ip_whitelist' => false,
                    ],
                ],
                'features' => [
                    'api_access' => true,
                    'team_collaboration' => false,
                ],
                'subscription_ends_at' => now()->addMonths(1),
                'last_activity_at' => now()->subDays(3),
            ],
        ];
        
        // Set default localization values from system tables
        $defaultCountry = \App\Models\System\Country::where('iso2', 'US')->first();
        $defaultLanguage = \App\Models\System\Language::where('iso_639_1', 'en')->first();
        $defaultCurrency = \App\Models\System\Currency::where('code', 'USD')->first();
        $defaultTimezone = \App\Models\System\Timezone::where('name', 'America/New_York')->first();
        
        foreach ($tenants as $tenantData) {
            // Add default localization
            $tenantData['country_id'] = $tenantData['country_id'] ?? $defaultCountry?->id;
            $tenantData['language_id'] = $tenantData['language_id'] ?? $defaultLanguage?->id;
            $tenantData['currency_id'] = $tenantData['currency_id'] ?? $defaultCurrency?->id;
            $tenantData['timezone_id'] = $tenantData['timezone_id'] ?? $defaultTimezone?->id;
            $tenantData['date_format'] = $tenantData['date_format'] ?? 'Y-m-d';
            $tenantData['time_format'] = $tenantData['time_format'] ?? 'H:i';
            
            Tenant::create($tenantData);
        }
        
        // Create some additional random tenants
        if (app()->environment('local', 'development')) {
            Tenant::factory()
                ->count(5)
                ->active()
                ->create();
                
            Tenant::factory()
                ->count(3)
                ->trial()
                ->create();
                
            Tenant::factory()
                ->count(2)
                ->suspended()
                ->create();
        }
        
        $this->command->info('Tenants seeded successfully!');
    }
} 