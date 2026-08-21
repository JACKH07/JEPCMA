<?php

namespace App\Models;

use App\Models\Finance\Budget;
use App\Models\Finance\Depense;
use App\Models\Finance\Paiement;
use App\Models\Rh\Employe;
use Database\Factories\DepartementFactory;
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
class Departement extends Model
{
    /** @use HasFactory<DepartementFactory> */
    use HasFactory;

    /**
     * @return HasMany<Employe, $this>
     */
    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class);
    }

    /**
     * @return HasMany<Depense, $this>
     */
    public function depenses(): HasMany
    {
        return $this->hasMany(Depense::class);
    }

    /**
     * @return HasMany<Budget, $this>
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * @return HasMany<Paiement, $this>
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
