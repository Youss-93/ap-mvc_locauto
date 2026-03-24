<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    // Liste les paiements de l'utilisateur
    public function index()
    {
        $paiements = Paiement::whereHas('reservation', function ($query) {
            $query->where('client_resa', auth()->id());
        })->with('reservation.voiture')->paginate(10);

        return view('paiements.index', compact('paiements'));
    }

    // Détail d'un paiement
    public function show(Paiement $paiement)
    {
        if ($paiement->reservation->client_resa !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $paiement->load('reservation.voiture', 'reservation.client');
        return view('paiements.show', compact('paiement'));
    }

    // Crée un paiement pour une réservation
    public function store(Request $request, Reservation $reservation)
    {
        if ($reservation->client_resa !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'mode_paiement' => 'required|in:par Carte,Par Virement,Paypal',
        ]);

        // Vérifier qu'il n'y a pas déjà un paiement validé
        if ($reservation->paiement && $reservation->paiement->isValidee()) {
            return back()->with('error', 'Un paiement validé existe déjà');
        }

        $montant = $reservation->prixTotal();
        $validated['paiement_resa'] = $reservation->id_reservation;
        $validated['montant'] = $montant;
        $validated['statut_paiement'] = 'en attente';

        Paiement::create($validated);

        return redirect()->route('paiements.index')
                        ->with('success', 'Paiement créé');
    }

    // Modifie un paiement
    public function update(Request $request, Paiement $paiement)
    {
        if ($paiement->reservation->client_resa !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'statut_paiement' => 'required|in:en attente,validée,échoué',
        ]);

        $paiement->update($validated);

        return redirect()->route('paiements.show', $paiement)
                        ->with('success', 'Paiement mis à jour');
    }

    // ADMIN - Liste tous les paiements
    public function admin()
    {
        $this->authorize('admin', auth()->user());
        $paiements = Paiement::with('reservation.voiture', 'reservation.client')->paginate(20);
        return view('paiements.admin', compact('paiements'));
    }

    // ADMIN - Détail d'un paiement
    public function adminShow(Paiement $paiement)
    {
        $this->authorize('admin', auth()->user());
        $paiement->load('reservation.voiture', 'reservation.client');
        return view('paiements.admin-show', compact('paiement'));
    }
}
