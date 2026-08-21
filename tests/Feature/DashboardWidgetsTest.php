<?php

use App\Enums\EmployeStatut;
use App\Enums\StatutCommande;
use App\Enums\StatutConge;
use App\Models\Finance\Depense;
use App\Models\Finance\Paiement;
use App\Models\Logistique\Commande;
use App\Models\Logistique\Produit;
use App\Models\Rh\Conge;
use App\Models\Rh\Employe;
use App\Models\Rh\Presence;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('dashboard widgets display live module statistics', function () {
    $user = User::factory()->create();
    $employe = Employe::factory()->create(['statut' => EmployeStatut::Actif]);

    Employe::factory()->create(['statut' => EmployeStatut::Inactif]);
    Conge::factory()->recycle($employe)->create(['statut' => StatutConge::EnAttente]);
    Presence::factory()->recycle($employe)->create(['date' => now()->toDateString()]);

    Depense::factory()->create([
        'montant' => '120.00',
        'date' => now()->toDateString(),
    ]);
    Paiement::factory()->entree()->create([
        'montant' => '300.00',
        'date' => now()->toDateString(),
    ]);

    Produit::factory()->create([
        'quantite' => 2,
        'seuil_alerte' => 5,
    ]);
    Commande::factory()->create(['statut' => StatutCommande::Validee]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('rh.employesActifs', 1)
            ->where('rh.congesEnAttente', 1)
            ->where('rh.presencesAujourdhui', 1)
            ->where('logistique.alertesStock', 1)
            ->where('logistique.commandesEnCours', 1)
            ->has('finance.depensesMois')
            ->has('finance.entreesMois')
            ->has('alertes', 2),
        );
});
