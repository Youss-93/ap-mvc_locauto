<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h1 class="h4 mb-0">Suppression du compte</h1>
        </div>
        
        <div class="card-body">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <div class="alert alert-warning">
                <h2 class="h5">⚠️ Attention</h2>
                <p>La suppression de votre compte est définitive et entraînera :</p>
                <ul>
                    <li>La suppression de toutes vos informations personnelles</li>
                    <li>L'annulation de toutes vos réservations en cours</li>
                    <li>La perte de votre historique de location</li>
                </ul>
            </div>

            <form action="index.php?controller=utilisateur&action=supprimer" 
                  method="POST" 
                  onsubmit="return confirm('Êtes-vous vraiment sûr de vouloir supprimer votre compte ?');">
                
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="confirmation" name="confirmation" required>
                    <label class="form-check-label" for="confirmation">
                        Je confirme vouloir supprimer définitivement mon compte
                    </label>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
                    <a href="index.php?controller=utilisateur&action=profil" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>