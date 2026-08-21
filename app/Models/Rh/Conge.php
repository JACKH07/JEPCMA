<?php

namespace App\Models\Rh;

use App\Enums\StatutConge;
use App\Enums\TypeConge;
use Database\Factories\Rh\CongeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employe_id
 * @property TypeConge $type_conge
 * @property Carbon $date_debut
 * @property Carbon $date_fin
 * @property StatutConge $statut
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['employe_id', 'type_conge', 'date_debut', 'date_fin', 'statut'])]
class Conge extends Model
{
    /** @use HasFactory<CongeFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'statut' => StatutConge::EnAttente->value,
    ];

    /**
     * @return BelongsTo<Employe, $this>
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    /**
     * @param  Builder<Conge>  $query
     * @return Builder<Conge>
     */
    #[Scope]
    protected function enAttente(Builder $query): Builder
    {
        return $query->where('statut', StatutConge::EnAttente);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type_conge' => TypeConge::class,
            'date_debut' => 'date',
            'date_fin' => 'date',
            'statut' => StatutConge::class,
        ];
    }
}
