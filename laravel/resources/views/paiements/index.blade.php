@extends('layouts.app')

@section('title', 'Mes paiements')

@section('content')
<h1 style="margin-bottom: 2rem;">Mes paiements</h1>

@if($paiements->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Réservation</th>
                <th>Montant</th>
                <th>Mode de paiement</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paiements as $paiement)
                <tr>
                    <td>{{ $paiement->reservation->voiture->fullName() }}</td>
                    <td>{{ number_format($paiement->montant, 2, ',', ' ') }}€</td>
                    <td>{{ $paiement->mode_paiement }}</td>
                    <td>
                        <span style="
                            padding: 0.25rem 0.75rem;
                            border-radius: 4px;
                            background: {{ $paiement->statut_paiement === 'validée' ? '#d4edda' : ($paiement->statut_paiement === 'échoué' ? '#f8d7da' : '#fff3cd') }};
                            color: {{ $paiement->statut_paiement === 'validée' ? '#155724' : ($paiement->statut_paiement === 'échoué' ? '#721c24' : '#856404') }};
                        ">
                            {{ ucfirst($paiement->statut_paiement) }}
                        </span>
                    </td>
                    <td>{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('paiements.show', $paiement) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Détail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $paiements->links() }}
@else
    <p>Vous n'avez pas de paiement pour le moment</p>
@endif
@endsection
