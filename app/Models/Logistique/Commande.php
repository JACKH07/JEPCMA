<?php

namespace App\Models\Logistique;

use App\Enums\StatutCommande;
use Database\Factories\Logistique\CommandeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $fournisseur_id
 * @property Carbon $date_commande
 * @property StatutCommande $statut
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['fournisseur_id', 'date_commande', 'statut'])]
class Commande extends Model
{
    /** @use HasFactory<CommandeFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'statut' => StatutCommande::Brouillon->value,
    ];

    /**
     * @param  Builder<Commande>  $query
     * @return Builder<Commande>
     */
    #[Scope]
    protected function enCours(Builder $query): Builder
    {
        return $query->whereNotIn('statut', [
            StatutCommande::Livree,
            StatutCommande::Annulee,
        ]);
    }

    /**
     * @return BelongsTo<Fournisseur, $this>
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * @return HasMany<LigneCommande, $this>
     */
    public function ligneCommandes(): HasMany
    {
        return $this->hasMany(LigneCommande::class);
    }

    /**
     * @return HasMany<Livraison, $this>
     */
    public function livraisons(): HasMany
    {
        return $this->hasMany(Livraison::class);
    }

    /**
     * @return BelongsToMany<Produit, $this>
     */
    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class, 'ligne_commandes')
            ->withPivot(['quantite', 'prix_unitaire'])
            ->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_commande' => 'date',
            'statut' => StatutCommande::class,
        ];
    }
}
