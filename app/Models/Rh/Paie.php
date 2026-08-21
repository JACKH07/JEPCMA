<?php

namespace App\Models\Rh;

use Database\Factories\Rh\PaieFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employe_id
 * @property int $mois
 * @property int $annee
 * @property string $salaire_net
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['employe_id', 'mois', 'annee', 'salaire_net'])]
class Paie extends Model
{
    /** @use HasFactory<PaieFactory> */
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
            'mois' => 'integer',
            'annee' => 'integer',
            'salaire_net' => 'decimal:2',
        ];
    }
}
