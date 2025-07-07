<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        
        return [
            'tenant_id' => Tenant::factory(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => Carbon::now('UTC'),
            'password' => static::$password ??= Hash::make('password'),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => fake()->optional(0.7)->phoneNumber(),
            'status' => fake()->randomElement([User::STATUS_ACTIVE, User::STATUS_ACTIVE, User::STATUS_ACTIVE, User::STATUS_INACTIVE]),
            'type' => fake()->randomElement([User::TYPE_OPERATOR, User::TYPE_OPERATOR, User::TYPE_SCREENER, User::TYPE_ADMIN]),
            'settings' => [],
            'last_login_at' => fake()->optional(0.8)->dateTimeBetween('-1 month', 'now'),
            'last_ip_address' => fake()->optional(0.8)->ipv4(),
            'last_user_agent' => fake()->optional(0.8)->userAgent(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    /**
     * Attach user to specific tenant.
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $tenant->id,
        ]);
    }
    
    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::TYPE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);
    }
    
    /**
     * Create a screener user.
     */
    public function screener(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::TYPE_SCREENER,
            'status' => User::STATUS_ACTIVE,
        ]);
    }
    
    /**
     * Create an operator user.
     */
    public function operator(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::TYPE_OPERATOR,
            'status' => User::STATUS_ACTIVE,
        ]);
    }
    
    /**
     * Create an inactive user.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => User::STATUS_INACTIVE,
            'last_login_at' => null,
        ]);
    }
    
    /**
     * Create a suspended user.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => User::STATUS_SUSPENDED,
        ]);
    }
    
    /**
     * Create a user who has never logged in.
     */
    public function neverLoggedIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_login_at' => null,
            'last_ip_address' => null,
            'last_user_agent' => null,
        ]);
    }
}
