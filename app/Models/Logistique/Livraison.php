<?php

namespace App\Models\Logistique;

use App\Enums\StatutLivraison;
use Database\Factories\Logistique\LivraisonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $commande_id
 * @property int $vehicule_id
 * @property Carbon $date_livraison
 * @property StatutLivraison $statut
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['commande_id', 'vehicule_id', 'date_livraison', 'statut'])]
class Livraison extends Model
{
    /** @use HasFactory<LivraisonFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'statut' => StatutLivraison::Planifiee->value,
    ];

    /**
     * @return BelongsTo<Commande, $this>
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * @return BelongsTo<Vehicule, $this>
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_livraison' => 'date',
            'statut' => StatutLivraison::class,
        ];
    }
}
