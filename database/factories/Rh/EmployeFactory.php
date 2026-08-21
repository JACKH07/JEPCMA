<?php

namespace Database\Factories\Rh;

use App\Enums\EmployeStatut;
use App\Models\Departement;
use App\Models\Rh\Employe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employe>
 */
class EmployeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'departement_id' => Departement::factory(),
            'user_id' => null,
            'matricule' => fake()->unique()->bothify('EMP-####'),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'poste' => fake()->jobTitle(),
            'date_embauche' => fake()->date(),
            'statut' => EmployeStatut::Actif,
        ];
    }

    public function withAccount(?User $user = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => $user?->id ?? User::factory(),
        ]);
    }
}
