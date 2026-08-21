<?php

namespace App\Actions\Dashboard;

use App\Enums\StatutLivraison;
use App\Enums\TypePaiement;
use App\Models\Finance\Budget;
use App\Models\Finance\Depense;
use App\Models\Finance\Paiement;
use App\Models\Logistique\Commande;
use App\Models\Logistique\Livraison;
use App\Models\Logistique\Produit;
use App\Models\Rh\Conge;
use App\Models\Rh\Employe;
use App\Models\Rh\Presence;
use Illuminate\Support\Number;

class BuildDashboardOverview
{
    /**
     * @return array{
     *     rh: array{employesActifs: int, congesEnAttente: int, presencesAujourdhui: int},
     *     finance: array{depensesMois: string, entreesMois: string, sortiesMois: string, budgetPrevu: string, budgetUtilise: string},
     *     logistique: array{alertesStock: int, commandesEnCours: int, livraisonsPlanifiees: int},
     *     alertes: list<array{module: string, message: string}>
     * }
     */
    public function handle(): array
    {
        $annee = now()->year;
        $debutMois = now()->startOfMonth()->toDateString();
        $finMois = now()->endOfMonth()->toDateString();
        $aujourdHui = now()->toDateString();

        $employesActifs = Employe::query()->actifs()->count();
        $congesEnAttente = Conge::query()->enAttente()->count();
        $presencesAujourdhui = Presence::query()->whereDate('date', $aujourdHui)->count();

        $depensesMois = (float) Depense::query()
            ->whereBetween('date', [$debutMois, $finMois])
            ->sum('montant');
        $entreesMois = (float) Paiement::query()
            ->where('type', TypePaiement::Entree)
            ->whereBetween('date', [$debutMois, $finMois])
            ->sum('montant');
        $sortiesMois = (float) Paiement::query()
            ->where('type', TypePaiement::Sortie)
            ->whereBetween('date', [$debutMois, $finMois])
            ->sum('montant');
        $budgetPrevu = (float) Budget::query()->where('annee', $annee)->sum('montant_prevu');
        $budgetUtilise = (float) Depense::query()->whereYear('date', $annee)->sum('montant');

        $alertesStock = Produit::query()->enAlerte()->count();
        $commandesEnCours = Commande::query()->enCours()->count();
        $livraisonsPlanifiees = Livraison::query()
            ->where('statut', StatutLivraison::Planifiee)
            ->count();

        return [
            'rh' => [
                'employesActifs' => $employesActifs,
                'congesEnAttente' => $congesEnAttente,
                'presencesAujourdhui' => $presencesAujourdhui,
            ],
            'finance' => [
                'depensesMois' => $this->formatMoney($depensesMois),
                'entreesMois' => $this->formatMoney($entreesMois),
                'sortiesMois' => $this->formatMoney($sortiesMois),
                'budgetPrevu' => $this->formatMoney($budgetPrevu),
                'budgetUtilise' => $this->formatMoney($budgetUtilise),
            ],
            'logistique' => [
                'alertesStock' => $alertesStock,
                'commandesEnCours' => $commandesEnCours,
                'livraisonsPlanifiees' => $livraisonsPlanifiees,
            ],
            'alertes' => $this->alertes(
                $congesEnAttente,
                $alertesStock,
                $budgetPrevu,
                $budgetUtilise,
            ),
        ];
    }

    /**
     * @return list<array{module: string, message: string}>
     */
    private function alertes(int $congesEnAttente, int $alertesStock, float $budgetPrevu, float $budgetUtilise): array
    {
        $alertes = [];

        if ($congesEnAttente > 0) {
            $alertes[] = [
                'module' => 'rh',
                'message' => $congesEnAttente.' demande(s) de congé en attente',
            ];
        }

        if ($alertesStock > 0) {
            $alertes[] = [
                'module' => 'logistique',
                'message' => $alertesStock.' produit(s) sous le seuil d\'alerte',
            ];
        }

        if ($budgetPrevu > 0 && $budgetUtilise > $budgetPrevu) {
            $alertes[] = [
                'module' => 'finance',
                'message' => 'Les dépenses de l\'année dépassent le budget prévisionnel',
            ];
        }

        return $alertes;
    }

    private function formatMoney(float $montant): string
    {
        $formatted = Number::currency($montant, in: 'EUR', locale: 'fr');

        if ($formatted === false) {
            return number_format($montant, 2, ',', ' ').' €';
        }

        return $formatted;
    }
}
