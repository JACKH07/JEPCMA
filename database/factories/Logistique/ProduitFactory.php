<?php

namespace Database\Factories\Logistique;

use App\Models\Logistique\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->words(3, true),
            'quantite' => fake()->numberBetween(0, 200),
            'seuil_alerte' => fake()->numberBetween(5, 20),
            'prix_unitaire' => fake()->randomFloat(2, 1, 500),
        ];
    }
}
