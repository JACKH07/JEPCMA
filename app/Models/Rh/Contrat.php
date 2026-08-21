<?php

namespace App\Models\Rh;

use App\Enums\TypeContrat;
use Database\Factories\Rh\ContratFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employe_id
 * @property TypeContrat $type_contrat
 * @property Carbon $date_debut
 * @property Carbon|null $date_fin
 * @property string $salaire
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['employe_id', 'type_contrat', 'date_debut', 'date_fin', 'salaire'])]
class Contrat extends Model
{
    /** @use HasFactory<ContratFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Employe, $this>
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type_contrat' => TypeContrat::class,
            'date_debut' => 'date',
            'date_fin' => 'date',
            'salaire' => 'decimal:2',
        ];
    }
}
