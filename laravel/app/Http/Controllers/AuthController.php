<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Affiche le formulaire de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traite la connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = Utilisateur::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->mdp_utilisateur)) {
            Auth::login($user);
            return redirect()->route('accueil')->with('success', 'Connexion réussie !');
        }

        return back()->withErrors(['email' => 'Identifiants invalides']);
    }

    // Affiche le formulaire de register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traite l'inscription
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom_utilisateur' => 'required|string|max:50',
            'prenom_utilisateur' => 'required|string|max:50',
            'email' => 'required|email|unique:Utilisateur,email',
            'password' => 'required|min:6|confirmed',
            'num_tel' => 'required|string|max:10',
        ]);

        $validated['mdp_utilisateur'] = Hash::make($validated['password']);
        $validated['role_utilisateur'] = 'client';
        unset($validated['password'], $validated['password_confirmation']);

        $user = Utilisateur::create($validated);
        Auth::login($user);

        return redirect()->route('accueil')->with('success', 'Inscription réussie !');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('accueil')->with('success', 'Déconnexion réussie');
    }
}
