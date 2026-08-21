<?php

namespace App\Actions\Dashboard;

use App\Enums\StatutCommande;
use App\Enums\StatutConge;
use App\Enums\StatutLivraison;
use App\Enums\StatutVehicule;
use App\Enums\TypePaiement;
use App\Models\Finance\Budget;
use App\Models\Finance\Depense;
use App\Models\Finance\Paiement;
use App\Models\Logistique\Commande;
use App\Models\Logistique\Livraison;
use App\Models\Logistique\Produit;
use App\Models\Logistique\Vehicule;
use App\Models\Rh\Conge;
use App\Models\Rh\Employe;
use App\Models\Rh\Presence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class BuildDashboardOverview
{
    /**
     * @return array{
     *     rh: array{employesActifs: int, congesEnAttente: int, presencesAujourdhui: int, totalEmployes: int, congesApprouves: int},
     *     finance: array{depensesMois: string, entreesMois: string, sortiesMois: string, budgetPrevu: string, budgetUtilise: string, budgetRatio: int, depensesParJour: list<array{label: string, total: float}>},
     *     logistique: array{alertesStock: int, commandesEnCours: int, livraisonsPlanifiees: int, vehiculesEnMission: int, vehiculesDisponibles: int, vehiculesMaintenance: int, commandesParStatut: list<array{label: string, value: string, count: int, percent: float}>, vehiculesParStatut: list<array{label: string, value: string, count: int, percent: float}>, livraisonsParStatut: list<array{label: string, value: string, count: int, percent: float}>},
     *     alertes: list<array{module: string, message: string}>
     * }
     */
    public function handle(): array
    {
        $annee = now()->year;
        $debutMois = now()->startOfMonth()->toDateString();
        $finMois = now()->endOfMonth()->toDateString();
        $aujourdHui = now()->toDateString();

        $totalEmployes = Employe::query()->count();
        $employesActifs = Employe::query()->actifs()->count();
        $congesEnAttente = Conge::query()->enAttente()->count();
        $congesApprouves = Conge::query()->where('statut', StatutConge::Approuve)->count();
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
        $vehiculesDisponibles = Vehicule::query()->where('statut', StatutVehicule::Disponible)->count();
        $vehiculesEnMission = Vehicule::query()->where('statut', StatutVehicule::EnMission)->count();
        $vehiculesMaintenance = Vehicule::query()->where('statut', StatutVehicule::Maintenance)->count();

        return [
            'rh' => [
                'employesActifs' => $employesActifs,
                'congesEnAttente' => $congesEnAttente,
                'presencesAujourdhui' => $presencesAujourdhui,
                'totalEmployes' => $totalEmployes,
                'congesApprouves' => $congesApprouves,
            ],
            'finance' => [
                'depensesMois' => $this->formatMoney($depensesMois),
                'entreesMois' => $this->formatMoney($entreesMois),
                'sortiesMois' => $this->formatMoney($sortiesMois),
                'budgetPrevu' => $this->formatMoney($budgetPrevu),
                'budgetUtilise' => $this->formatMoney($budgetUtilise),
                'budgetRatio' => $this->ratio($budgetUtilise, $budgetPrevu),
                'depensesParJour' => $this->depensesParJour(),
            ],
            'logistique' => [
                'alertesStock' => $alertesStock,
                'commandesEnCours' => $commandesEnCours,
                'livraisonsPlanifiees' => $livraisonsPlanifiees,
                'vehiculesEnMission' => $vehiculesEnMission,
                'vehiculesDisponibles' => $vehiculesDisponibles,
                'vehiculesMaintenance' => $vehiculesMaintenance,
                'commandesParStatut' => $this->repartition(Commande::class, [
                    StatutCommande::Brouillon->value => 'Brouillon',
                    StatutCommande::Validee->value => 'Validée',
                    StatutCommande::Livree->value => 'Livrée',
                    StatutCommande::Annulee->value => 'Annulée',
                ]),
                'vehiculesParStatut' => $this->repartition(Vehicule::class, [
                    StatutVehicule::EnMission->value => 'En mission',
                    StatutVehicule::Disponible->value => 'Disponible',
                    StatutVehicule::Maintenance->value => 'Maintenance',
                ]),
                'livraisonsParStatut' => $this->repartition(Livraison::class, [
                    StatutLivraison::Planifiee->value => 'Planifiée',
                    StatutLivraison::EnCours->value => 'En cours',
                    StatutLivraison::Livree->value => 'Livrée',
                ]),
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
     * @param  class-string<Model>  $model
     * @param  array<string, string>  $labels
     * @return list<array{label: string, value: string, count: int, percent: float}>
     */
    private function repartition(string $model, array $labels): array
    {
        $counts = $model::query()
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $total = (int) $counts->sum();

        $repartition = [];

        foreach ($labels as $value => $label) {
            $count = (int) $counts->get($value, 0);

            $repartition[] = [
                'label' => $label,
                'value' => $value,
                'count' => $count,
                'percent' => $this->percent($count, $total),
            ];
        }

        return $repartition;
    }

    /**
     * @return list<array{label: string, total: float}>
     */
    private function depensesParJour(): array
    {
        $debut = now()->subDays(9)->toDateString();
        $totaux = Depense::query()
            ->where('date', '>=', $debut)
            ->selectRaw('date, SUM(montant) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $series = [];

        for ($jour = 9; $jour >= 0; $jour--) {
            $date = now()->subDays($jour)->toDateString();

            $series[] = [
                'label' => now()->subDays($jour)->format('d'),
                'total' => (float) $totaux->get($date, 0),
            ];
        }

        return $series;
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

    private function percent(int $part, int $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round(($part / $total) * 100, 1);
    }

    private function ratio(float $utilise, float $prevu): int
    {
        if ($prevu <= 0) {
            return 0;
        }

        return (int) min(100, round(($utilise / $prevu) * 100));
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
