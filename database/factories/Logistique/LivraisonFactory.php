<?php

namespace Database\Factories\Logistique;

use App\Enums\StatutLivraison;
use App\Models\Logistique\Commande;
use App\Models\Logistique\Livraison;
use App\Models\Logistique\Vehicule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livraison>
 */
class LivraisonFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commande_id' => Commande::factory(),
            'vehicule_id' => Vehicule::factory(),
            'date_livraison' => fake()->date(),
            'statut' => StatutLivraison::Planifiee,
        ];
    }
}
