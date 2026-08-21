<?php

namespace App\Models\Logistique;

use Database\Factories\Logistique\FournisseurFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nom
 * @property string|null $telephone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['nom', 'telephone'])]
class Fournisseur extends Model
{
    /** @use HasFactory<FournisseurFactory> */
    use HasFactory;

    /**
     * @return HasMany<Commande, $this>
     */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
}
