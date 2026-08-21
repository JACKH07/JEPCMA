<?php

namespace App\Models\Rh;

use App\Enums\EmployeStatut;
use App\Models\Departement;
use App\Models\User;
use Database\Factories\Rh\EmployeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $departement_id
 * @property int|null $user_id
 * @property string $matricule
 * @property string $nom
 * @property string $prenom
 * @property string $poste
 * @property Carbon $date_embauche
 * @property EmployeStatut $statut
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['departement_id', 'user_id', 'matricule', 'nom', 'prenom', 'poste', 'date_embauche', 'statut'])]
class Employe extends Model
{
    /** @use HasFactory<EmployeFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'statut' => EmployeStatut::Actif->value,
    ];

    /**
     * @return BelongsTo<Departement, $this>
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Contrat, $this>
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    /**
     * @return HasMany<Conge, $this>
     */
    public function conges(): HasMany
    {
        return $this->hasMany(Conge::class);
    }

    /**
     * @return HasMany<Presence, $this>
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * @return HasMany<Paie, $this>
     */
    public function paies(): HasMany
    {
        return $this->hasMany(Paie::class);
    }

    /**
     * @param  Builder<Employe>  $query
     * @return Builder<Employe>
     */
    #[Scope]
    protected function actifs(Builder $query): Builder
    {
        return $query->where('statut', EmployeStatut::Actif);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_embauche' => 'date',
            'statut' => EmployeStatut::class,
        ];
    }
}
