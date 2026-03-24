<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UtilisateurController extends Controller
{
    // Affiche le profil de l'utilisateur
    public function profil()
    {
        $user = auth()->user()->load('reservations', 'voitures');
        return view('profil.show', compact('user'));
    }

    // Affiche le formulaire de modification du profil
    public function edit()
    {
        $user = auth()->user();
        return view('profil.edit', compact('user'));
    }

    // Modifie le profil
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nom_utilisateur' => 'required|string|max:50',
            'prenom_utilisateur' => 'required|string|max:50',
            'email' => 'required|email|unique:Utilisateur,email,' . $user->id_utilisateur . ',id_utilisateur',
            'num_tel' => 'required|string|max:10',
        ]);

        // Vérifier le mot de passe actuel si le mot de passe change
        if ($request->has('password') && $request->password) {
            $validated_pwd = $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);
            $validated['mdp_utilisateur'] = Hash::make($validated_pwd['password']);
        }

        $user->update($validated);

        return redirect()->route('profil')
                        ->with('success', 'Profil mis à jour');
    }

    // Supprime le compte
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password, $user->mdp_utilisateur)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect']);
        }

        Auth::logout();
        $user->delete();

        return redirect()->route('accueil')
                        ->with('success', 'Compte supprimé');
    }
}
