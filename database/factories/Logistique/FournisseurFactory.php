<?php

namespace Database\Factories\Logistique;

use App\Models\Logistique\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fournisseur>
 */
class FournisseurFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->company(),
            'telephone' => fake()->numerify('0#########'),
        ];
    }
}
