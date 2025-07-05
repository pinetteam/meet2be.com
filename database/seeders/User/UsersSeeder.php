<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Tenant\Tenant;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->command->warn('No tenants found. Please run TenantsSeeder first.');
            return;
        }
        
        foreach ($tenants as $tenant) {
            $this->command->info("Creating users for tenant: {$tenant->name}");
            
            // Create one admin per tenant
            User::factory()
                ->admin()
                ->forTenant($tenant)
                ->create([
                    'username' => "admin_{$tenant->code}",
                    'email' => "admin@{$tenant->slug}.com",
                    'first_name' => 'Admin',
                    'last_name' => $tenant->name,
                ]);
            
            // Create 2-3 screeners per tenant
            $screenerCount = rand(2, 3);
            for ($i = 1; $i <= $screenerCount; $i++) {
                User::factory()
                    ->screener()
                    ->forTenant($tenant)
                    ->create([
                        'username' => "screener{$i}_{$tenant->code}",
                        'email' => "screener{$i}@{$tenant->slug}.com",
                    ]);
            }
            
            // Create 3-5 operators per tenant
            $operatorCount = rand(3, 5);
            for ($i = 1; $i <= $operatorCount; $i++) {
                User::factory()
                    ->operator()
                    ->forTenant($tenant)
                    ->create([
                        'username' => "operator{$i}_{$tenant->code}",
                        'email' => "operator{$i}@{$tenant->slug}.com",
                    ]);
            }
            
            // Create 1-2 inactive users
            $inactiveCount = rand(1, 2);
            for ($i = 1; $i <= $inactiveCount; $i++) {
                User::factory()
                    ->inactive()
                    ->forTenant($tenant)
                    ->create();
            }
            
            // Create 0-1 suspended users (30% chance)
            if (rand(1, 10) <= 3) {
                User::factory()
                    ->suspended()
                    ->forTenant($tenant)
                    ->create();
            }
        }
        
        $this->command->info('Users seeded successfully!');
        
        // Show summary
        $totalUsers = User::count();
        $adminCount = User::where('type', User::TYPE_ADMIN)->count();
        $screenerCount = User::where('type', User::TYPE_SCREENER)->count();
        $operatorCount = User::where('type', User::TYPE_OPERATOR)->count();
        
        $this->command->info("Total users created: {$totalUsers}");
        $this->command->info("- Admins: {$adminCount}");
        $this->command->info("- Screeners: {$screenerCount}");
        $this->command->info("- Operators: {$operatorCount}");
    }
} 