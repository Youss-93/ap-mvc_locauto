<?php require_once 'vue/header.php'; ?>
    <div class="container">
        <h1>Connexion</h1>
        
        <?php if(isset($erreur)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form action="index.php?controller=utilisateur&action=login" method="POST">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <p>Pas encore inscrit ? <a href="index.php?controller=utilisateur&action=register">S'inscrire</a></p>
    </div>
<?php require_once 'vue/footer.php'; ?>