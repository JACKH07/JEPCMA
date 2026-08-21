<?php

namespace App\Models\Finance;

use App\Models\Departement;
use Database\Factories\Finance\BudgetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $departement_id
 * @property int $annee
 * @property string $montant_prevu
 * @property-read string $montant_utilise
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['departement_id', 'annee', 'montant_prevu'])]
class Budget extends Model
{
    /** @use HasFactory<BudgetFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Departement, $this>
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    /**
     * @return Attribute<numeric-string, never>
     */
    protected function montantUtilise(): Attribute
    {
        return Attribute::get(function (): string {
            $total = Depense::query()
                ->where('departement_id', $this->departement_id)
                ->whereYear('date', $this->annee)
                ->sum('montant');

            return number_format((float) $total, 2, '.', '');
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'annee' => 'integer',
            'montant_prevu' => 'decimal:2',
        ];
    }
}
