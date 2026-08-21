<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot visit module overviews', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with(['rh.index', 'finance.index', 'logistique.index']);

test('authenticated users can visit module overviews', function (string $routeName, string $component) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component($component)
            ->has('stats'),
        );
})->with([
    ['rh.index', 'rh/Index'],
    ['finance.index', 'finance/Index'],
    ['logistique.index', 'logistique/Index'],
]);
