@extends('layouts.app')

@section('title', 'Mes réservations')

@section('content')
<h1 style="margin-bottom: 2rem;">Mes réservations</h1>

@if($reservations->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Véhicule</th>
                <th>Dates</th>
                <th>Durée</th>
                <th>Prix total</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $resa)
                <tr>
                    <td>{{ $resa->voiture->fullName() }}</td>
                    <td>{{ $resa->date_debut->format('d/m/Y') }} → {{ $resa->date_fin->format('d/m/Y') }}</td>
                    <td>{{ $resa->duree() }} jours</td>
                    <td>{{ number_format($resa->prixTotal(), 2, ',', ' ') }}€</td>
                    <td>
                        <span style="
                            padding: 0.25rem 0.75rem;
                            border-radius: 4px;
                            background: {{ $resa->statut_reservation === 'confirmée' ? '#d4edda' : ($resa->statut_reservation === 'annulée' ? '#f8d7da' : '#fff3cd') }};
                            color: {{ $resa->statut_reservation === 'confirmée' ? '#155724' : ($resa->statut_reservation === 'annulée' ? '#721c24' : '#856404') }};
                        ">
                            {{ ucfirst($resa->statut_reservation) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('reservations.show', $resa) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Détail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $reservations->links() }}
@else
    <p>Vous n'avez pas de réservation pour le moment</p>
    <a href="{{ route('voitures.index') }}" class="btn btn-primary">Voir nos véhicules</a>
@endif
@endsection
