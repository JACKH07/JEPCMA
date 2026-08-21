<?php

namespace Database\Factories\Rh;

use App\Enums\StatutConge;
use App\Enums\TypeConge;
use App\Models\Rh\Conge;
use App\Models\Rh\Employe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conge>
 */
class CongeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateDebut = fake()->dateTimeBetween('-2 months', 'now');

        return [
            'employe_id' => Employe::factory(),
            'type_conge' => fake()->randomElement(TypeConge::cases()),
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => (clone $dateDebut)->modify('+'.fake()->numberBetween(1, 15).' days')->format('Y-m-d'),
            'statut' => StatutConge::EnAttente,
        ];
    }
}
