@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div style="text-align: center; padding: 3rem 0;">
    <h1 style="font-size: 3rem; margin-bottom: 1rem;">🏎️ Bienvenue sur LocAuto</h1>
    <p style="font-size: 1.25rem; margin-bottom: 2rem;">La meilleure plateforme de location de voitures</p>
    <a href="{{ route('voitures.index') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">Voir nos véhicules</a>
</div>

<div class="grid" style="margin-top: 3rem;">
    <div class="card">
        <div class="card-title">🚗 Large sélection</div>
        <p>Plus de 25 véhicules disponibles, du citadin économique au SUV de luxe.</p>
    </div>
    <div class="card">
        <div class="card-title">💰 Prix compétitifs</div>
        <p>Les meilleurs tarifs du marché avec des prix transparents et sans frais cachés.</p>
    </div>
    <div class="card">
        <div class="card-title">🔒 Sécurisé</div>
        <p>Paiements sécurisés et assurance complète incluse dans chaque location.</p>
    </div>
    <div class="card">
        <div class="card-title">⚡ Facile</div>
        <p>Réservation en quelques clics et prise de rendez-vous rapide et flexible.</p>
    </div>
</div>
@endsection
