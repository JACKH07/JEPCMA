<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Finance\OverviewController as FinanceOverviewController;
use App\Http\Controllers\Logistique\OverviewController as LogistiqueOverviewController;
use App\Http\Controllers\Rh\OverviewController as RhOverviewController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('rh', RhOverviewController::class)->name('rh.index');
    Route::get('finance', FinanceOverviewController::class)->name('finance.index');
    Route::get('logistique', LogistiqueOverviewController::class)->name('logistique.index');
});

require __DIR__.'/settings.php';
