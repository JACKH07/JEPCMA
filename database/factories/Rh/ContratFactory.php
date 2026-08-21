<?php

namespace Database\Factories\Rh;

use App\Enums\TypeContrat;
use App\Models\Rh\Contrat;
use App\Models\Rh\Employe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contrat>
 */
class ContratFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employe_id' => Employe::factory(),
            'type_contrat' => TypeContrat::Cdi,
            'date_debut' => fake()->date(),
            'date_fin' => null,
            'salaire' => fake()->randomFloat(2, 1500, 8000),
        ];
    }

    public function cdd(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type_contrat' => TypeContrat::Cdd,
            'date_fin' => fake()->dateTimeBetween('+6 months', '+2 years')->format('Y-m-d'),
        ]);
    }
}
