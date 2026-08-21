<?php

namespace Database\Factories\Finance;

use App\Models\Departement;
use App\Models\Finance\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'departement_id' => Departement::factory(),
            'annee' => fake()->unique()->numberBetween(2020, 2035),
            'montant_prevu' => fake()->randomFloat(2, 5000, 200000),
        ];
    }
}
