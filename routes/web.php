<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MvtStockController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return \Inertia\Inertia::render('Dashboard', [
        'stats' => [
            'ventesJour' => \App\Models\Vente::whereDate('date_vente', today())->count(),
            'caJour' => \App\Models\Vente::whereDate('date_vente', today())->sum('total'),
            'produitsStock' => \App\Models\Produit::sum('quantite_stock'),
            'alertesStock' => \App\Models\Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count(),
        ],
        'dernieresVentes' => \App\Models\Vente::with(['client', 'user'])
            ->latest('date_vente')
            ->take(5)
            ->get(),
        'produitsAlerte' => \App\Models\Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')
            ->take(5)
            ->get(),
    ]);
})
->middleware(['auth', 'verified'])
->name('dashboard');

/*
|--------------------------------------------------------------------------
| Auth (tous les utilisateurs connectés)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Logout
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

    // Ventes (caissier, admin, etc.)
    Route::resource('ventes', VenteController::class);
    Route::resource('clients', ClientController::class);
});

/*
|--------------------------------------------------------------------------
| Admin + Gestionnaire Stock
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,gestionnaire_stock'])->group(function () {

    // Catalogue
    Route::resource('categories', CategorieController::class);
    Route::resource('produits', ProduitController::class);

    // Stock
    Route::resource('mvt-stocks', MvtStockController::class)->names('mvtstock');
});

/*
|--------------------------------------------------------------------------
| Admin uniquement
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
