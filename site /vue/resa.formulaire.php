<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h2>Réserver une voiture</h2>

    <?php if(isset($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <div class="voiture-details">
        <h3><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h3>
        <p>Prix par jour : <?= htmlspecialchars($voiture['prix_jour']) ?> €</p>
        <p>Caution : <?= htmlspecialchars($voiture['caution']) ?> €</p>
    </div>

    <form action="index.php?controller=reservation&action=creer" method="POST" class="reservation-form">
        <input type="hidden" name="voiture_id" value="<?= htmlspecialchars($voiture['id_voiture']) ?>">
        
        <div class="form-group">
            <label for="date_debut">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" required 
                   min="<?= date('Y-m-d') ?>" class="form-control">
        </div>

        <div class="form-group">
            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" required 
                   min="<?= date('Y-m-d') ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Réserver</button>
        <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once 'vue/footer.php'; ?>