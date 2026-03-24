@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div style="max-width: 400px; margin: 3rem auto;">
    <h1 style="text-align: center; margin-bottom: 2rem;">Connexion</h1>

    <form action="{{ route('login.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="{{ old('email') }}">
            @error('email')<span style="color: #e74c3c;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            @error('password')<span style="color: #e74c3c;">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Se connecter</button>
    </form>

    <p style="text-align: center; margin-top: 1rem;">
        Pas encore inscrit ? <a href="{{ route('register') }}">S'inscrire</a>
    </p>
</div>
@endsection
