<?php

namespace Database\Factories\Rh;

use App\Models\Rh\Employe;
use App\Models\Rh\Paie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paie>
 */
class PaieFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employe_id' => Employe::factory(),
            'mois' => fake()->numberBetween(1, 12),
            'annee' => fake()->numberBetween(2020, 2026),
            'salaire_net' => fake()->randomFloat(2, 1200, 6000),
        ];
    }
}
