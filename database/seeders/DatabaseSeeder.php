<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // System seeders - run first
        $this->call([
            System\CountriesSeeder::class,
            System\CurrenciesSeeder::class,
            System\LanguagesSeeder::class,
            System\TimezonesSeeder::class,
            System\CountryLanguageSeeder::class,
        ]);
        
        // Tenant seeders
        $this->call([
            Tenant\TenantsSeeder::class,
        ]);
        
        // Development seeders
        if (app()->environment('local', 'development')) {
            $this->call([
                User\UsersSeeder::class,
                Event\EventsSeeder::class,
            ]);
            
            // Create specific test users for the first tenant
            $tenant = \App\Models\Tenant\Tenant::first();
            
            if ($tenant) {
                // Create test admin if not exists
                if (!\App\Models\User\User::where('email', 'admin@example.com')->exists()) {
                    \App\Models\User\User::factory()->admin()->create([
                        'username' => 'admin',
                        'email' => 'admin@example.com',
                        'first_name' => 'Test',
                        'last_name' => 'Admin',
                        'tenant_id' => $tenant->id,
                    ]);
                }
            }
        }
    }
}
