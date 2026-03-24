<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use App\Models\Utilisateur;
use App\Models\Reservation;
use Illuminate\Http\Request;

class VoitureController extends Controller
{
    // Liste toutes les voitures disponibles
    public function index()
    {
        $voitures = Voiture::with('admin')->paginate(12);
        return view('voitures.index', compact('voitures'));
    }

    // Affiche le détail d'une voiture
    public function show(Voiture $voiture)
    {
        $voiture->load('admin', 'reservations');
        return view('voitures.show', compact('voiture'));
    }

    // Liste les voitures disponibles
    public function disponibles()
    {
        $voitures = Voiture::disponibles()->with('admin')->paginate(12);
        return view('voitures.disponibles', compact('voitures'));
    }

    // Admin : Liste des voitures d'un admin
    public function mesVoitures()
    {
        $voitures = auth()->user()->voitures()->paginate(12);
        return view('voitures.mes-voitures', compact('voitures'));
    }

    // Admin : Crée une nouvelle voiture
    public function create()
    {
        return view('voitures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'année' => 'required|integer|min:1900|max:' . date('Y'),
            'prix_jour' => 'required|numeric|min:0',
            'caution' => 'required|numeric|min:0',
            'image_loc' => 'nullable|image|max:2048',
        ]);

        $validated['id_admin'] = auth()->id();
        $validated['disponibilité'] = true;

        // Gestion du fichier image
        if ($request->hasFile('image_loc')) {
            $path = $request->file('image_loc')->store('voitures', 'public');
            $validated['image_loc'] = $path;
        }

        Voiture::create($validated);

        return redirect()->route('voitures.mes-voitures')
                        ->with('success', 'Voiture ajoutée avec succès');
    }

    // Admin : Modifie une voiture
    public function edit(Voiture $voiture)
    {
        $this->authorize('update', $voiture);
        return view('voitures.edit', compact('voiture'));
    }

    public function update(Request $request, Voiture $voiture)
    {
        $this->authorize('update', $voiture);

        $validated = $request->validate([
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'année' => 'required|integer|min:1900|max:' . date('Y'),
            'prix_jour' => 'required|numeric|min:0',
            'caution' => 'required|numeric|min:0',
            'disponibilité' => 'boolean',
            'image_loc' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_loc')) {
            $path = $request->file('image_loc')->store('voitures', 'public');
            $validated['image_loc'] = $path;
        }

        $voiture->update($validated);

        return redirect()->route('voitures.show', $voiture)
                        ->with('success', 'Voiture mise à jour');
    }

    // Admin : Supprime une voiture
    public function destroy(Voiture $voiture)
    {
        $this->authorize('delete', $voiture);
        $voiture->delete();

        return redirect()->route('voitures.mes-voitures')
                        ->with('success', 'Voiture supprimée');
    }

    // Cherche des voitures
    public function search(Request $request)
    {
        $query = Voiture::query();

        if ($request->has('marque') && $request->marque) {
            $query->where('marque', 'like', '%' . $request->marque . '%');
        }

        if ($request->has('modele') && $request->modele) {
            $query->where('modele', 'like', '%' . $request->modele . '%');
        }

        if ($request->has('prix_max') && $request->prix_max) {
            $query->where('prix_jour', '<=', $request->prix_max);
        }

        if ($request->has('disponibles') && $request->disponibles) {
            $query->disponibles();
        }

        $voitures = $query->with('admin')->paginate(12);

        return view('voitures.search', compact('voitures'));
    }
}
