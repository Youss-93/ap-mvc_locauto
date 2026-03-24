@extends('layouts.app')

@section('title', 'Nos véhicules')

@section('content')
<h1 style="margin-bottom: 2rem;">Nos véhicules</h1>

<div class="grid">
    @forelse($voitures as $voiture)
        <div class="card">
            @if($voiture->image_loc)
                <img src="{{ $voiture->image_loc }}" alt="{{ $voiture->fullName() }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 1rem;">
            @endif
            <div class="card-title">{{ $voiture->fullName() }} ({{ $voiture->année }})</div>
            <p><strong>Prix :</strong> {{ number_format($voiture->prix_jour, 2, ',', ' ') }}€/jour</p>
            <p><strong>Caution :</strong> {{ number_format($voiture->caution, 2, ',', ' ') }}€</p>
            <p><strong>Disponibilité :</strong> 
                @if($voiture->disponibilité)
                    <span style="color: green;">✓ Disponible</span>
                @else
                    <span style="color: red;">✗ Indisponible</span>
                @endif
            </p>
            <a href="{{ route('voitures.show', $voiture) }}" class="btn btn-primary" style="width: 100%; text-align: center;">Voir les détails</a>
        </div>
    @empty
        <p style="grid-column: 1 / -1;">Aucun véhicule disponible</p>
    @endforelse
</div>

{{ $voitures->links() }}
@endsection
