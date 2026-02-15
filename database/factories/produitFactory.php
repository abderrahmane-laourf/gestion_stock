<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\produit>
 */
class produitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'description' => fake()->text(),
            'prix' => fake()->randomFloat(2, 10, 1000), // Safer price range
            'stock' => fake()->randomNumber(2),
            'categorie_id' => fake()->numberBetween(1, 10),
        ];
    }
}
