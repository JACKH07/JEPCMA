<?php

namespace Database\Factories\Logistique;

use App\Models\Logistique\Commande;
use App\Models\Logistique\LigneCommande;
use App\Models\Logistique\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LigneCommande>
 */
class LigneCommandeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commande_id' => Commande::factory(),
            'produit_id' => Produit::factory(),
            'quantite' => fake()->numberBetween(1, 50),
            'prix_unitaire' => fake()->randomFloat(2, 1, 500),
        ];
    }
}
