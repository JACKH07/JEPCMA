<?php

namespace App\Models\Finance;

use App\Enums\TypePaiement;
use App\Models\Departement;
use Database\Factories\Finance\PaiementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $departement_id
 * @property int|null $categorie_id
 * @property TypePaiement $type
 * @property string $montant
 * @property Carbon $date
 * @property string $libelle
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['departement_id', 'categorie_id', 'type', 'montant', 'date', 'libelle'])]
class Paiement extends Model
{
    /** @use HasFactory<PaiementFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Departement, $this>
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    /**
     * @return BelongsTo<Categorie, $this>
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TypePaiement::class,
            'montant' => 'decimal:2',
            'date' => 'date',
        ];
    }
}
