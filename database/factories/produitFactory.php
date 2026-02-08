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
            'prix' => fake()->randomNumber(5),
            'stock' => fake()->randomNumber(2),
            'categorie_id' => fake()->numberBetween(1, 10),
        ];
    }
}
