<?php

namespace App\Models\Logistique;

use Database\Factories\Logistique\LigneCommandeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $commande_id
 * @property int $produit_id
 * @property int $quantite
 * @property string $prix_unitaire
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['commande_id', 'produit_id', 'quantite', 'prix_unitaire'])]
class LigneCommande extends Model
{
    /** @use HasFactory<LigneCommandeFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Commande, $this>
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * @return BelongsTo<Produit, $this>
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
            'prix_unitaire' => 'decimal:2',
        ];
    }
}
