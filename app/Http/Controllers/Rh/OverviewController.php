<?php

namespace App\Http\Controllers\Rh;

use App\Actions\Dashboard\BuildDashboardOverview;
use App\Http\Controllers\Controller;
use App\Models\Rh\Conge;
use App\Models\Rh\Employe;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    /**
     * Display the HR module overview.
     */
    public function __invoke(BuildDashboardOverview $overview): Response
    {
        return Inertia::render('rh/Index', [
            'stats' => $overview->handle()['rh'],
            'employes' => Employe::query()
                ->with('departement:id,nom')
                ->latest()
                ->limit(8)
                ->get()
                ->map(fn (Employe $employe): array => [
                    'id' => $employe->id,
                    'matricule' => $employe->matricule,
                    'nom' => $employe->nom,
                    'prenom' => $employe->prenom,
                    'poste' => $employe->poste,
                    'departement' => $employe->departement->nom,
                    'statut' => $employe->statut->value,
                ]),
            'congesEnAttente' => Conge::query()
                ->enAttente()
                ->with('employe:id,nom,prenom')
                ->latest()
                ->limit(8)
                ->get()
                ->map(fn (Conge $conge): array => [
                    'id' => $conge->id,
                    'employe' => $conge->employe->prenom.' '.$conge->employe->nom,
                    'type' => $conge->type_conge->value,
                    'dateDebut' => $conge->date_debut->toDateString(),
                    'dateFin' => $conge->date_fin->toDateString(),
                ]),
        ]);
    }
}
