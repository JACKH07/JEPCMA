<?php

namespace Database\Factories\Finance;

use App\Enums\TypePaiement;
use App\Models\Departement;
use App\Models\Finance\Categorie;
use App\Models\Finance\Paiement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paiement>
 */
class PaiementFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'departement_id' => Departement::factory(),
            'categorie_id' => Categorie::factory(),
            'type' => fake()->randomElement(TypePaiement::cases()),
            'montant' => fake()->randomFloat(2, 50, 10000),
            'date' => fake()->date(),
            'libelle' => fake()->sentence(3),
        ];
    }

    public function entree(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => TypePaiement::Entree,
        ]);
    }

    public function sortie(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => TypePaiement::Sortie,
        ]);
    }
}
