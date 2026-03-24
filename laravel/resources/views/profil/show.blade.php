@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem;">Mon profil</h1>

    <div class="grid" style="grid-template-columns: 1fr 1fr;">
        <div class="card">
            <div class="card-title">{{ $user->full_name }}</div>
            <table>
                <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                <tr><th>Téléphone</th><td>{{ $user->num_tel }}</td></tr>
                <tr><th>Rôle</th><td>{{ ucfirst($user->role_utilisateur) }}</td></tr>
            </table>
            <a href="{{ route('profil.edit') }}" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">Modifier mes informations</a>
        </div>

        <div class="card">
            <div class="card-title">Statistiques</div>
            <p><strong>Réservations :</strong> {{ $user->reservations->count() }}</p>
            <p><strong>Réservations confirmées :</strong> {{ $user->reservations->where('statut_reservation', 'confirmée')->count() }}</p>
            @if($user->isAdmin())
                <p><strong>Mes véhicules :</strong> {{ $user->voitures->count() }}</p>
            @endif
        </div>
    </div>

    <form action="{{ route('profil.destroy') }}" method="POST" style="margin-top: 2rem;" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
    </form>
</div>
@endsection
