<?php require_once 'header.php'; ?>

<div class="container">
    <h1>Connexion</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <form action="index.php?controller=utilisateur&action=login" method="POST" class="form-login">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>