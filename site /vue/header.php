<!DOCTYPE html>
<html>
<head>
    <title><?= $titre ?? 'Location de voitures' ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="index.php">Location Auto</a>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="index.php?controller=voiture&action=liste">Voitures</a></li>
            
            <?php if(isset($_SESSION['utilisateur'])): ?>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <li><a href="index.php?controller=voiture&action=ajouter">Ajouter une voiture</a></li>
                <?php endif; ?>
                <li><a href="index.php?controller=reservation&action=liste">Mes réservations</a></li>
                <li><a href="index.php?controller=utilisateur&action=profil">Mon Profil</a></li>
                <li><a href="index.php?controller=utilisateur&action=logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="index.php?controller=utilisateur&action=login">Connexion</a></li>
                <li><a href="index.php?controller=utilisateur&action=register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>