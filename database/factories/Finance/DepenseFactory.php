<?php

namespace Database\Factories\Finance;

use App\Models\Departement;
use App\Models\Finance\Categorie;
use App\Models\Finance\Depense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Depense>
 */
class DepenseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categorie_id' => Categorie::factory(),
            'departement_id' => Departement::factory(),
            'montant' => fake()->randomFloat(2, 10, 5000),
            'date' => fake()->date(),
        ];
    }
}
