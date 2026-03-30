<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h1>Inscription</h1>

    <?php if(isset($erreur)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?controller=utilisateur&action=register" method="POST" class="form-register">
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
            <label for="tel">Téléphone :</label>
            <input type="tel" id="tel" name="tel" pattern="[0-9]{10}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required class="form-control"
                   minlength="8" title="8 caractères minimum">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <p class="text-center mt-3">
        Déjà inscrit ? <a href="index.php?controller=utilisateur&action=login">Se connecter</a>
    </p>
</div>

<?php require_once 'vue/footer.php'; ?>