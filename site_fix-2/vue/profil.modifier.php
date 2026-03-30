<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <h1>Modifier mon profil</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="index.php?controller=utilisateur&action=modifier" method="POST" class="needs-validation">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?= htmlspecialchars($utilisateur['nom_utilisateur']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" 
                           value="<?= htmlspecialchars($utilisateur['prenom_utilisateur']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="tel" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" id="tel" name="tel" 
                           value="<?= htmlspecialchars($utilisateur['num_tel'] ?? '') ?>">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="index.php?controller=utilisateur&action=profil" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>