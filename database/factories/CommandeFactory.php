<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commande>
 */
class CommandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::inRandomOrder()->first()->id ?? Client::factory(),
            'date_commande' => fake()->dateTimeBetween('-1 month', 'now'),
            'adresse_livraison' => fake()->address(),
            'statut' => fake()->randomElement(['brouillon', 'confirmee', 'en_cours', 'livree', 'annulee']),
            'montant_total' => fake()->randomFloat(2, 50, 5000),
        ];
    }
}
