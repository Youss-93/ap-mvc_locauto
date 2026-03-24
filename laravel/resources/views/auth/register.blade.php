@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div style="max-width: 400px; margin: 3rem auto;">
    <h1 style="text-align: center; margin-bottom: 2rem;">Inscription</h1>

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="prenom_utilisateur">Prénom</label>
            <input type="text" id="prenom_utilisateur" name="prenom_utilisateur" required value="{{ old('prenom_utilisateur') }}">
            @error('prenom_utilisateur')<span style="color: #e74c3c;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="nom_utilisateur">Nom</label>
            <input type="text" id="nom_utilisateur" name="nom_utilisateur" required value="{{ old('nom_utilisateur') }}">
            @error('nom_utilisateur')<span style="color: #e74c3c;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="{{ old('email') }}">
            @error('email')<span style="color: #e74c3c;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="num_tel">Téléphone</label>
            <input type="text" id="num_tel" name="num_tel" required value="{{ old('num_tel') }}">
            @error('num_tel')<span style="color: #e74c3c;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            @error('password')<span style="color: #e74c3c;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">S'inscrire</button>
    </form>

    <p style="text-align: center; margin-top: 1rem;">
        Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
    </p>
</div>
@endsection
