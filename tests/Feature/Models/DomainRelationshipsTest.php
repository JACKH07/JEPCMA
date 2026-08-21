<?php

use App\Enums\EmployeStatut;
use App\Enums\TypeContrat;
use App\Enums\TypePaiement;
use App\Models\Departement;
use App\Models\Finance\Budget;
use App\Models\Finance\Depense;
use App\Models\Finance\Paiement;
use App\Models\Logistique\Commande;
use App\Models\Logistique\LigneCommande;
use App\Models\Logistique\Livraison;
use App\Models\Logistique\Produit;
use App\Models\Rh\Conge;
use App\Models\Rh\Contrat;
use App\Models\Rh\Employe;
use App\Models\Rh\Paie;
use App\Models\Rh\Presence;
use App\Models\User;
use Illuminate\Database\QueryException;

test('an employe belongs to a department and can have an optional user account', function () {
    $user = User::factory()->create();
    $employe = Employe::factory()->withAccount($user)->create();

    expect($employe->departement)->toBeInstanceOf(Departement::class)
        ->and($employe->user->is($user))->toBeTrue()
        ->and($user->employe->is($employe))->toBeTrue()
        ->and($employe->statut)->toBe(EmployeStatut::Actif);

    $employeSansCompte = Employe::factory()->create();

    expect($employeSansCompte->user_id)->toBeNull();
});

test('an employe has contracts, leaves, attendances and payrolls', function () {
    $employe = Employe::factory()->create();

    $contrat = Contrat::factory()->recycle($employe)->create([
        'type_contrat' => TypeContrat::Cdi,
        'date_fin' => null,
    ]);
    $conge = Conge::factory()->recycle($employe)->create();
    $presence = Presence::factory()->recycle($employe)->create(['date' => '2026-03-10']);
    $paie = Paie::factory()->recycle($employe)->create([
        'mois' => 3,
        'annee' => 2026,
    ]);

    expect($employe->contrats)->toHaveCount(1)
        ->and($employe->conges)->toHaveCount(1)
        ->and($employe->presences)->toHaveCount(1)
        ->and($employe->paies)->toHaveCount(1)
        ->and($contrat->employe->is($employe))->toBeTrue()
        ->and($conge->employe->is($employe))->toBeTrue()
        ->and($presence->employe->is($employe))->toBeTrue()
        ->and($paie->employe->is($employe))->toBeTrue()
        ->and($contrat->date_fin)->toBeNull();
});

test('budget used amount is the sum of department expenses for that year', function () {
    $departement = Departement::factory()->create();
    $budget = Budget::factory()->recycle($departement)->create([
        'annee' => 2026,
        'montant_prevu' => '10000.00',
    ]);

    Depense::factory()->recycle($departement)->create([
        'montant' => '100.50',
        'date' => '2026-03-01',
    ]);
    Depense::factory()->recycle($departement)->create([
        'montant' => '49.50',
        'date' => '2026-06-15',
    ]);
    Depense::factory()->recycle($departement)->create([
        'montant' => '999.00',
        'date' => '2025-06-15',
    ]);

    expect($budget->montant_utilise)->toBe('150.00');
});

test('a payment can be an inflow or an outflow', function () {
    $entree = Paiement::factory()->entree()->create();
    $sortie = Paiement::factory()->sortie()->create();

    expect($entree->type)->toBe(TypePaiement::Entree)
        ->and($sortie->type)->toBe(TypePaiement::Sortie)
        ->and($entree->departement)->toBeInstanceOf(Departement::class)
        ->and($entree->categorie)->not->toBeNull();
});

test('an order has lines, products and deliveries', function () {
    $commande = Commande::factory()->create();
    $produit = Produit::factory()->create();

    LigneCommande::factory()->recycle($commande)->create([
        'produit_id' => $produit->id,
        'quantite' => 4,
        'prix_unitaire' => '12.50',
    ]);
    $livraison = Livraison::factory()->recycle($commande)->create();

    expect($commande->ligneCommandes)->toHaveCount(1)
        ->and($commande->produits)->toHaveCount(1)
        ->and($commande->livraisons)->toHaveCount(1)
        ->and($commande->produits->first()->is($produit))->toBeTrue()
        ->and($livraison->vehicule)->not->toBeNull()
        ->and($livraison->commande->is($commande))->toBeTrue();
});

test('employee matricules are unique', function () {
    Employe::factory()->create(['matricule' => 'EMP-0001']);
    Employe::factory()->create(['matricule' => 'EMP-0001']);
})->throws(QueryException::class);

test('an employee can only have one attendance record per day', function () {
    $employe = Employe::factory()->create();

    Presence::factory()->recycle($employe)->create(['date' => '2026-04-01']);
    Presence::factory()->recycle($employe)->create(['date' => '2026-04-01']);
})->throws(QueryException::class);

test('an employee can only have one payroll per month', function () {
    $employe = Employe::factory()->create();

    Paie::factory()->recycle($employe)->create(['mois' => 4, 'annee' => 2026]);
    Paie::factory()->recycle($employe)->create(['mois' => 4, 'annee' => 2026]);
})->throws(QueryException::class);

test('a department can only have one budget per year', function () {
    $departement = Departement::factory()->create();

    Budget::factory()->recycle($departement)->create(['annee' => 2026]);
    Budget::factory()->recycle($departement)->create(['annee' => 2026]);
})->throws(QueryException::class);
