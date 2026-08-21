<?php

namespace App\Models\Logistique;

use Database\Factories\Logistique\ProduitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nom
 * @property int $quantite
 * @property int $seuil_alerte
 * @property string $prix_unitaire
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['nom', 'quantite', 'seuil_alerte', 'prix_unitaire'])]
class Produit extends Model
{
    /** @use HasFactory<ProduitFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'quantite' => 0,
        'seuil_alerte' => 0,
    ];

    /**
     * @return HasMany<LigneCommande, $this>
     */
    public function ligneCommandes(): HasMany
    {
        return $this->hasMany(LigneCommande::class);
    }

    /**
     * @param  Builder<Produit>  $query
     * @return Builder<Produit>
     */
    #[Scope]
    protected function enAlerte(Builder $query): Builder
    {
        return $query->whereColumn('quantite', '<=', 'seuil_alerte');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
            'seuil_alerte' => 'integer',
            'prix_unitaire' => 'decimal:2',
        ];
    }
}
