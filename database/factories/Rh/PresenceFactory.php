<?php

namespace Database\Factories\Rh;

use App\Models\Rh\Employe;
use App\Models\Rh\Presence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Presence>
 */
class PresenceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employe_id' => Employe::factory(),
            'date' => fake()->unique()->date(),
            'heure_arrivee' => '08:00:00',
            'heure_depart' => '17:00:00',
        ];
    }
}
