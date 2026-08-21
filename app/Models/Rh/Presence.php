<?php

namespace App\Models\Rh;

use Database\Factories\Rh\PresenceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employe_id
 * @property Carbon $date
 * @property string|null $heure_arrivee
 * @property string|null $heure_depart
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['employe_id', 'date', 'heure_arrivee', 'heure_depart'])]
class Presence extends Model
{
    /** @use HasFactory<PresenceFactory> */
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
            'date' => 'date',
        ];
    }
}
