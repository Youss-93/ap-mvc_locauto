<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Voiture;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // Affiche les réservations de l'utilisateur connecté
    public function mesReservations()
    {
        $reservations = auth()->user()->reservations()->with('voiture')->paginate(10);
        return view('reservations.mes-reservations', compact('reservations'));
    }

    // Affiche le détail d'une réservation
    public function show(Reservation $reservation)
    {
        // Vérifier que c'est la réservation de l'utilisateur ou admin
        if ($reservation->client_resa !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $reservation->load('voiture', 'client', 'paiement');
        return view('reservations.show', compact('reservation'));
    }

    // Crée une réservation
    public function store(Request $request, Voiture $voiture)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        // Vérifier la disponibilité
        $existe = Reservation::where('voiture_resa', $voiture->id_voiture)
            ->where('statut_reservation', 'confirmée')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('date_debut', [$validated['date_debut'], $validated['date_fin']])
                      ->orWhereBetween('date_fin', [$validated['date_debut'], $validated['date_fin']]);
            })->exists();

        if ($existe) {
            return back()->with('error', 'La voiture n\'est pas disponible pour ces dates');
        }

        $validated['client_resa'] = auth()->id();
        $validated['voiture_resa'] = $voiture->id_voiture;
        $validated['statut_reservation'] = 'en attente';

        Reservation::create($validated);

        return redirect()->route('reservations.mes')->with('success', 'Réservation créée');
    }

    // Modifie une réservation
    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->client_resa !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.show', $reservation)
                        ->with('success', 'Réservation modifiée');
    }

    // Annule une réservation
    public function destroy(Reservation $reservation)
    {
        if ($reservation->client_resa !== auth()->id()) {
            abort(403);
        }

        $reservation->annuler();

        return redirect()->route('reservations.mes')
                        ->with('success', 'Réservation annulée');
    }

    // ADMIN - Liste toutes les réservations
    public function admin()
    {
        $this->authorize('admin', auth()->user());
        $reservations = Reservation::with('voiture', 'client')->paginate(20);
        return view('reservations.admin', compact('reservations'));
    }

    // ADMIN - Détail d'une réservation
    public function adminShow(Reservation $reservation)
    {
        $this->authorize('admin', auth()->user());
        $reservation->load('voiture', 'client', 'paiement');
        return view('reservations.admin-show', compact('reservation'));
    }
}
