<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\ScolariteController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\TypeContributionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Remplace la route existante par celle-ci :
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/membres', [MembreController::class, 'index'])->name('membres.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/membres', [MembreController::class, 'index'])->name('membres.index');
    Route::get('/membres/creer', [MembreController::class, 'create'])->name('membres.create');
    
    // ON PLACE L'EXPORT ICI (Avant le paramètre dynamique {membre})
    Route::get('/membres/export', [MembreController::class, 'export'])->name('membres.export');
    Route::get('/membres/export-pdf', [MembreController::class, 'exportPdf'])->name('membres.export.pdf');

    Route::post('/membres', [MembreController::class, 'store'])->name('membres.store');
    Route::get('/membres/{membre}/modifier', [MembreController::class, 'edit'])->name('membres.edit');
    Route::put('/membres/{membre}', [MembreController::class, 'update'])->name('membres.update');
    Route::delete('/membres/{membre}', [MembreController::class, 'destroy'])->name('membres.destroy');
    Route::get('/membres/{membre}', [MembreController::class, 'show'])->name('membres.show');

    // Route Scolarité sécurisée
    Route::post('/scolarites', [ScolariteController::class, 'store'])->name('scolarites.store');
    Route::post('/scolarites', [ScolariteController::class, 'store'])->name('scolarites.store');
    Route::put('/scolarites/{scolarite}', [ScolariteController::class, 'update'])->name('scolarites.update');
    Route::delete('/scolarites/{scolarite}', [ScolariteController::class, 'destroy'])->name('scolarites.destroy');

    // Route pour la liste globale la gestion des contributions
    Route::get('/contributions', [ContributionController::class, 'index'])->name('contributions.index');
    Route::post('/contributions', [ContributionController::class, 'store'])->name('contributions.store');
    Route::put('/contributions/{contribution}', [ContributionController::class, 'update'])->name('contributions.update');
    Route::delete('/contributions/{contribution}', [ContributionController::class, 'destroy'])->name('contributions.destroy');

    // Gestion des types de contributions / projets
    // Types de contributions
    Route::post('/type-contributions', [TypeContributionController::class, 'store'])->name('type-contributions.store');
    Route::put('/type-contributions/{typeContribution}', [TypeContributionController::class, 'update'])->name('type-contributions.update');
    Route::delete('/type-contributions/{typeContribution}', [TypeContributionController::class, 'destroy'])->name('type-contributions.destroy');
});

Route::post('/scolarites', [ScolariteController::class, 'store'])->name('scolarites.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
