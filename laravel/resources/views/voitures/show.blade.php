@extends('layouts.app')

@section('title', $voiture->fullName())

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem;">{{ $voiture->fullName() }} ({{ $voiture->année }})</h1>

    @if($voiture->image_loc)
        <img src="{{ $voiture->image_loc }}" alt="{{ $voiture->fullName() }}" style="width: 100%; height: 400px; object-fit: cover; border-radius: 4px; margin-bottom: 2rem;">
    @endif

    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <h3>Informations</h3>
            <table>
                <tr><th>Marque</th><td>{{ $voiture->marque }}</td></tr>
                <tr><th>Modèle</th><td>{{ $voiture->modele }}</td></tr>
                <tr><th>Année</th><td>{{ $voiture->année }}</td></tr>
                <tr><th>Prix/jour</th><td>{{ number_format($voiture->prix_jour, 2, ',', ' ') }}€</td></tr>
                <tr><th>Caution</th><td>{{ number_format($voiture->caution, 2, ',', ' ') }}€</td></tr>
                <tr><th>Disponibilité</th><td>
                    @if($voiture->disponibilité)
                        <span style="color: green;">✓ Disponible</span>
                    @else
                        <span style="color: red;">✗ Indisponible</span>
                    @endif
                </td></tr>
            </table>
        </div>

        @auth
            <div>
                <h3>Réserver</h3>
                @if($voiture->disponibilité)
                    <form action="{{ route('reservations.store', $voiture) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="date_debut">Date de début</label>
                            <input type="date" id="date_debut" name="date_debut" required>
                        </div>
                        <div class="form-group">
                            <label for="date_fin">Date de fin</label>
                            <input type="date" id="date_fin" name="date_fin" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Réserver</button>
                    </form>
                @else
                    <p style="color: red;">Cette voiture n'est pas disponible pour le moment</p>
                @endif
            </div>
        @else
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 4px;">
                <p>Vous devez être connecté pour réserver</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
            </div>
        @endauth
    </div>
</div>
@endsection
