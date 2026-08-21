<?php

namespace App\Models\Finance;

use Database\Factories\Finance\CategorieFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nom
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['nom'])]
class Categorie extends Model
{
    /** @use HasFactory<CategorieFactory> */
    use HasFactory;

    /**
     * @return HasMany<Depense, $this>
     */
    public function depenses(): HasMany
    {
        return $this->hasMany(Depense::class);
    }

    /**
     * @return HasMany<Paiement, $this>
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
