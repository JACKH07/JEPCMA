<?php

namespace App\Http\Controllers\Logistique;

use App\Actions\Dashboard\BuildDashboardOverview;
use App\Http\Controllers\Controller;
use App\Models\Logistique\Commande;
use App\Models\Logistique\Produit;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    /**
     * Display the logistics module overview.
     */
    public function __invoke(BuildDashboardOverview $overview): Response
    {
        return Inertia::render('logistique/Index', [
            'stats' => $overview->handle()['logistique'],
            'produitsEnAlerte' => Produit::query()
                ->enAlerte()
                ->orderBy('nom')
                ->limit(8)
                ->get()
                ->map(fn (Produit $produit): array => [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'quantite' => $produit->quantite,
                    'seuilAlerte' => $produit->seuil_alerte,
                ]),
            'commandes' => Commande::query()
                ->with('fournisseur:id,nom')
                ->latest('date_commande')
                ->limit(8)
                ->get()
                ->map(fn (Commande $commande): array => [
                    'id' => $commande->id,
                    'fournisseur' => $commande->fournisseur->nom,
                    'dateCommande' => $commande->date_commande->toDateString(),
                    'statut' => $commande->statut->value,
                ]),
        ]);
    }
}
