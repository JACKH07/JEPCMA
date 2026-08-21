<?php

namespace App\Http\Controllers\Finance;

use App\Actions\Dashboard\BuildDashboardOverview;
use App\Http\Controllers\Controller;
use App\Models\Finance\Depense;
use App\Models\Finance\Paiement;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    /**
     * Display the finance module overview.
     */
    public function __invoke(BuildDashboardOverview $overview): Response
    {
        return Inertia::render('finance/Index', [
            'stats' => $overview->handle()['finance'],
            'depenses' => Depense::query()
                ->with(['categorie:id,nom', 'departement:id,nom'])
                ->latest('date')
                ->limit(8)
                ->get()
                ->map(fn (Depense $depense): array => [
                    'id' => $depense->id,
                    'categorie' => $depense->categorie->nom,
                    'departement' => $depense->departement->nom,
                    'montant' => $depense->montant,
                    'date' => $depense->date->toDateString(),
                ]),
            'paiements' => Paiement::query()
                ->with('departement:id,nom')
                ->latest('date')
                ->limit(8)
                ->get()
                ->map(fn (Paiement $paiement): array => [
                    'id' => $paiement->id,
                    'libelle' => $paiement->libelle,
                    'type' => $paiement->type->value,
                    'montant' => $paiement->montant,
                    'departement' => $paiement->departement->nom,
                    'date' => $paiement->date->toDateString(),
                ]),
        ]);
    }
}
