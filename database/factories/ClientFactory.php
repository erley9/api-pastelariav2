<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phonenumber' => fake()->numerify('## #########'),
            'dateofbirth' => fake()->date(),
            'address' => fake()->sentence(),
            'complement' => fake()->word(),
            'neighborhood' => fake()->word(),
            'zipcode' => fake()->numerify('#####-###')
        ];
    }
}
