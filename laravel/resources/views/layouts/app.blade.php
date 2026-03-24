<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Location Auto') - Location de Voitures</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        header { background: #2c3e50; color: white; padding: 1rem; position: sticky; top: 0; z-index: 1000; }
        header a { color: white; text-decoration: none; }
        nav { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
        nav .logo { font-size: 1.5rem; font-weight: bold; }
        nav ul { list-style: none; display: flex; gap: 2rem; }
        nav a:hover { color: #3498db; }
        main { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; }
        footer { background: #2c3e50; color: white; text-align: center; padding: 2rem; margin-top: 3rem; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; transition: 0.3s; }
        .btn-primary { background: #3498db; color: white; }
        .btn-primary:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-danger:hover { background: #c0392b; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        table th, table td { padding: 1rem; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; }
        .card { border: 1px solid #ddd; border-radius: 4px; padding: 1.5rem; }
        .card-title { font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; }
        .user-menu { position: relative; }
        .user-menu-items { display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ddd; min-width: 200px; }
        .user-menu:hover .user-menu-items { display: block; }
        .user-menu-items a { display: block; padding: 0.75rem 1rem; color: #333; text-decoration: none; border-bottom: 1px solid #eee; }
        .user-menu-items a:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('accueil') }}" class="logo">🏎️ LocAuto</a>
            <ul>
                <li><a href="{{ route('voitures.index') }}">Véhicules</a></li>
                @auth
                    <li><a href="{{ route('reservations.mes') }}">Mes réservations</a></li>
                    <li><a href="{{ route('paiements.index') }}">Mes paiements</a></li>
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('voitures.mes') }}">Mes véhicules</a></li>
                        <li><a href="{{ route('reservations.admin') }}">Réservations (Admin)</a></li>
                    @endif
                    <li class="user-menu">
                        <a href="#">{{ auth()->user()->full_name }}</a>
                        <div class="user-menu-items">
                            <a href="{{ route('profil') }}">Mon profil</a>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; padding: 0.75rem 1rem; border: none; background: none; cursor: pointer; color: #e74c3c;">Déconnexion</button>
                            </form>
                        </div>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                    <li><a href="{{ route('register') }}">Inscription</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; 2024 LocAuto. Tous droits réservés.</p>
    </footer>
</body>
</html>
