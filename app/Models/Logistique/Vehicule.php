<?php

namespace App\Models\Logistique;

use App\Enums\StatutVehicule;
use Database\Factories\Logistique\VehiculeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $immatriculation
 * @property StatutVehicule $statut
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['immatriculation', 'statut'])]
class Vehicule extends Model
{
    /** @use HasFactory<VehiculeFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'statut' => StatutVehicule::Disponible->value,
    ];

    /**
     * @return HasMany<Livraison, $this>
     */
    public function livraisons(): HasMany
    {
        return $this->hasMany(Livraison::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'statut' => StatutVehicule::class,
        ];
    }
}
