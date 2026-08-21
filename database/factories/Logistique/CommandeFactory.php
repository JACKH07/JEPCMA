<?php

namespace Database\Factories\Logistique;

use App\Enums\StatutCommande;
use App\Models\Logistique\Commande;
use App\Models\Logistique\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commande>
 */
class CommandeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fournisseur_id' => Fournisseur::factory(),
            'date_commande' => fake()->date(),
            'statut' => StatutCommande::Brouillon,
        ];
    }
}
