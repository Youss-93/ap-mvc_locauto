<?php require_once 'header.php'; ?>

<div class="container">
    <h1>Inscription</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <form action="index.php?controller=utilisateur&action=creer" method="POST" class="form-register">
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required class="form-control">
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone :</label>
            <input type="tel" id="telephone" name="telephone" pattern="[0-9]{10}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required class="form-control"
                   minlength="8" title="8 caractères minimum">
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <p class="text-center mt-3">
        Déjà inscrit ? <a href="index.php?controller=utilisateur&action=login">Se connecter</a>
    </p>
</div>

<?php require_once 'footer.php'; ?>