<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Tenant;
use App\Models\System\Country;
use App\Models\System\Language;
use App\Models\System\Currency;
use App\Models\System\Timezone;
use Carbon\Carbon;

class TenantsSeeder extends Seeder
{
    public function run(): void
    {
        // Get reference data
        $countryTurkey = Country::where('iso2', 'TR')->first();
        $countryUSA = Country::where('iso2', 'US')->first();
        $countryGermany = Country::where('iso2', 'DE')->first();
        
        $languageTurkish = Language::where('iso_639_1', 'tr')->first();
        $languageEnglish = Language::where('iso_639_1', 'en')->first();
        $languageGerman = Language::where('iso_639_1', 'de')->first();
        
        $currencyTRY = Currency::where('code', 'TRY')->first();
        $currencyUSD = Currency::where('code', 'USD')->first();
        $currencyEUR = Currency::where('code', 'EUR')->first();
        
        $timezoneIstanbul = Timezone::where('name', 'Europe/Istanbul')->first();
        $timezoneNewYork = Timezone::where('name', 'America/New_York')->first();
        $timezoneBerlin = Timezone::where('name', 'Europe/Berlin')->first();
        
        // Check if reference data exists
        if (!$countryTurkey || !$languageTurkish || !$currencyTRY || !$timezoneIstanbul) {
            $this->command->error('Required reference data not found. Please run system seeders first.');
            return;
        }
        
        // Tenant 1: Turkish Enterprise Company
        Tenant::create([
            'code' => 'ACM001',
            'name' => 'Acme Teknoloji A.Ş.',
            'legal_name' => 'Acme Teknoloji Anonim Şirketi',
            'slug' => 'acme-teknoloji',
            'type' => 'enterprise',
            'email' => 'info@acme-teknoloji.com.tr',
            'phone' => '+905551234567',
            'website' => 'https://www.acme-teknoloji.com.tr',
            'address_line_1' => 'Levent Mahallesi, Büyükdere Caddesi No:123',
            'address_line_2' => 'Levent Plaza, Kat:15',
            'city' => 'Istanbul',
            'state' => 'Istanbul',
            'postal_code' => '34394',
            'country_id' => $countryTurkey->id,
            'language_id' => $languageTurkish->id,
            'currency_id' => $currencyTRY->id,
            'timezone_id' => $timezoneIstanbul->id,
            'date_format' => 'd.m.Y',
            'time_format' => 'H:i',
            'plan' => 'enterprise',
            'status' => 'active',
            'max_users' => 100,
            'max_storage_mb' => 102400, // 100GB
            'max_events' => 1000,
            'current_users' => 25,
            'current_storage_mb' => 5120, // 5GB
            'current_events' => 45,
            'subscription_ends_at' => Carbon::now('UTC')->addYear(),
            'last_activity_at' => Carbon::now('UTC'),
            'settings' => [
                'theme' => 'dark',
                'notifications' => true,
                'email_reports' => true,
            ],
            'features' => ['api_access', 'custom_branding', 'priority_support', 'advanced_analytics'],
        ]);
        
        // Tenant 2: US Business Company
        if ($countryUSA && $languageEnglish && $currencyUSD && $timezoneNewYork) {
            Tenant::create([
                'code' => 'TEC002',
                'name' => 'TechStart Inc.',
                'legal_name' => 'TechStart Incorporated',
                'slug' => 'techstart',
                'type' => 'business',
                'email' => 'contact@techstart.com',
                'phone' => '+12125551234',
                'website' => 'https://www.techstart.com',
                'address_line_1' => '123 Main Street, Suite 456',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country_id' => $countryUSA->id,
                'language_id' => $languageEnglish->id,
                'currency_id' => $currencyUSD->id,
                'timezone_id' => $timezoneNewYork->id,
                'date_format' => 'm/d/Y',
                'time_format' => 'h:i A',
                'plan' => 'pro',
                'status' => 'active',
                'max_users' => 50,
                'max_storage_mb' => 51200, // 50GB
                'max_events' => 500,
                'current_users' => 12,
                'current_storage_mb' => 2048, // 2GB
                'current_events' => 28,
                'subscription_ends_at' => Carbon::now('UTC')->addMonths(6),
                'last_activity_at' => Carbon::now('UTC')->subHours(2),
                'settings' => [
                    'theme' => 'light',
                    'notifications' => true,
                    'email_reports' => false,
                ],
                'features' => ['api_access', 'custom_branding'],
            ]);
        }
        
        // Tenant 3: German Small Business
        if ($countryGermany && $languageGerman && $currencyEUR && $timezoneBerlin) {
            Tenant::create([
                'code' => 'INF003',
                'name' => 'Innovate GmbH',
                'legal_name' => 'Innovate Gesellschaft mit beschränkter Haftung',
                'slug' => 'innovate-gmbh',
                'type' => 'business',
                'email' => 'info@innovate-gmbh.de',
                'phone' => '+493012345678',
                'website' => 'https://www.innovate-gmbh.de',
                'address_line_1' => 'Alexanderplatz 1',
                'city' => 'Berlin',
                'postal_code' => '10178',
                'country_id' => $countryGermany->id,
                'language_id' => $languageGerman->id,
                'currency_id' => $currencyEUR->id,
                'timezone_id' => $timezoneBerlin->id,
                'date_format' => 'd.m.Y',
                'time_format' => 'H:i',
                'plan' => 'basic',
                'status' => 'active',
                'max_users' => 10,
                'max_storage_mb' => 10240, // 10GB
                'max_events' => 50,
                'current_users' => 4,
                'current_storage_mb' => 512, // 512MB
                'current_events' => 8,
                'subscription_ends_at' => Carbon::now('UTC')->addMonths(3),
                'last_activity_at' => Carbon::now('UTC')->subDays(1),
                'settings' => [
                    'theme' => 'light',
                    'notifications' => true,
                ],
                'features' => ['basic_analytics'],
            ]);
        }
        
        // Tenant 4: Individual Trial Account
        Tenant::create([
            'code' => 'FRE004',
            'name' => 'Freelancer Demo',
            'slug' => 'freelancer-demo',
            'type' => 'individual',
            'email' => 'demo@freelancer.com',
            'phone' => '+905559876543',
            'city' => 'Ankara',
            'country_id' => $countryTurkey->id,
            'language_id' => $languageTurkish->id,
            'currency_id' => $currencyTRY->id,
            'timezone_id' => $timezoneIstanbul->id,
            'date_format' => 'd.m.Y',
            'time_format' => 'H:i',
            'plan' => 'basic',
            'status' => 'trial',
            'max_users' => 5,
            'max_storage_mb' => 1024, // 1GB
            'max_events' => 10,
            'current_users' => 1,
            'current_storage_mb' => 50,
            'current_events' => 2,
            'trial_ends_at' => Carbon::now('UTC')->addDays(7),
            'last_activity_at' => Carbon::now('UTC')->subHours(5),
            'settings' => [
                'theme' => 'dark',
            ],
            'features' => [],
        ]);
        
        // Tenant 5: Inactive Account
        Tenant::create([
            'code' => 'OLD005',
            'name' => 'Old Company Ltd.',
            'slug' => 'old-company',
            'type' => 'business',
            'email' => 'contact@oldcompany.com',
            'city' => 'Izmir',
            'country_id' => $countryTurkey->id,
            'language_id' => $languageTurkish->id,
            'currency_id' => $currencyTRY->id,
            'timezone_id' => $timezoneIstanbul->id,
            'plan' => 'basic',
            'status' => 'inactive',
            'max_users' => 10,
            'max_storage_mb' => 5120, // 5GB
            'max_events' => 50,
            'current_users' => 3,
            'current_storage_mb' => 1024,
            'current_events' => 12,
            'subscription_ends_at' => Carbon::now('UTC')->addMonths(1),
            'last_activity_at' => Carbon::now('UTC')->subDays(3),
            'settings' => [],
            'features' => [],
        ]);
        
        $this->command->info('Tenants seeded successfully!');
        
        // Display summary
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $trialTenants = Tenant::where('status', 'trial')->count();
        
        $this->command->info("Total tenants created: {$totalTenants}");
        $this->command->info("- Active: {$activeTenants}");
        $this->command->info("- Trial: {$trialTenants}");
    }
} 