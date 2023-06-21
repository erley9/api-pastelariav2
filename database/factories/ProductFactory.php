<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Pastel '.fake()->word(),
            'price' => fake()->randomFloat(2,1,99999999.99),
            'photo' => fake()->imageUrl(640, 480,'pasty', true)
        ];
    }
}
