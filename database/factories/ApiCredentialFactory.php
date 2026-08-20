<?php

namespace Database\Factories;

use App\Models\ApiCredential;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<ApiCredential>
 */
class ApiCredentialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'client_id' => 'api_'.Str::lower(Str::random(20)),
            'client_secret_hash' => Hash::make('credential-secret'),
            'created_by' => User::factory(),
            'last_used_at' => null,
            'revoked_at' => null,
        ];
    }
}
