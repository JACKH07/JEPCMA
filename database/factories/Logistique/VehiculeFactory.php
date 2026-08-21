<?php

namespace Database\Factories\Logistique;

use App\Enums\StatutVehicule;
use App\Models\Logistique\Vehicule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicule>
 */
class VehiculeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'immatriculation' => fake()->unique()->bothify('??-###-??'),
            'statut' => StatutVehicule::Disponible,
        ];
    }
}
