<?php

use App\Http\Controllers\VoitureController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('accueil');
})->name('accueil');

// VOITURES - Publiques
Route::get('/voitures', [VoitureController::class, 'index'])->name('voitures.index');
Route::get('/voitures/{voiture}', [VoitureController::class, 'show'])->name('voitures.show');
Route::get('/voitures/search', [VoitureController::class, 'search'])->name('voitures.search');

// AUTH - Publiques
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // PROFIL UTILISATEUR
    Route::get('/profil', [UtilisateurController::class, 'profil'])->name('profil');
    Route::get('/profil/modifier', [UtilisateurController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [UtilisateurController::class, 'update'])->name('profil.update');
    Route::delete('/profil', [UtilisateurController::class, 'destroy'])->name('profil.destroy');
    
    // RÉSERVATIONS - Client
    Route::get('/mes-reservations', [ReservationController::class, 'mesReservations'])->name('reservations.mes');
    Route::post('/voitures/{voiture}/reserver', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    
    // PAIEMENTS
    Route::get('/mes-paiements', [PaiementController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/{paiement}', [PaiementController::class, 'show'])->name('paiements.show');
    Route::post('/reservations/{reservation}/payer', [PaiementController::class, 'store'])->name('paiements.store');
    Route::put('/paiements/{paiement}', [PaiementController::class, 'update'])->name('paiements.update');
    
    // ADMIN - Voitures
    Route::middleware('admin')->group(function () {
        Route::get('/admin/voitures', [VoitureController::class, 'mesVoitures'])->name('voitures.mes');
        Route::get('/admin/voitures/create', [VoitureController::class, 'create'])->name('voitures.create');
        Route::post('/admin/voitures', [VoitureController::class, 'store'])->name('voitures.store');
        Route::get('/admin/voitures/{voiture}/edit', [VoitureController::class, 'edit'])->name('voitures.edit');
        Route::put('/admin/voitures/{voiture}', [VoitureController::class, 'update'])->name('voitures.update');
        Route::delete('/admin/voitures/{voiture}', [VoitureController::class, 'destroy'])->name('voitures.destroy');
        
        // ADMIN - Réservations
        Route::get('/admin/reservations', [ReservationController::class, 'admin'])->name('reservations.admin');
        Route::get('/admin/reservations/{reservation}', [ReservationController::class, 'adminShow'])->name('reservations.adminShow');
        
        // ADMIN - Paiements
        Route::get('/admin/paiements', [PaiementController::class, 'admin'])->name('paiements.admin');
        Route::get('/admin/paiements/{paiement}', [PaiementController::class, 'adminShow'])->name('paiements.adminShow');
    });
});
