<?php require_once 'header.php'; ?>

<div class="container">
    <h2>Modifier une voiture</h2>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <form action="index.php?controller=voiture&action=modifier&id=<?= $voiture['id_voiture'] ?>" 
          method="POST" 
          enctype="multipart/form-data" 
          class="needs-validation" 
          novalidate>
        
        <div class="mb-3">
            <label for="marque" class="form-label">Marque</label>
            <input type="text" class="form-control" id="marque" name="marque" 
                   value="<?= htmlspecialchars($voiture['marque']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="modele" class="form-label">Modèle</label>
            <input type="text" class="form-control" id="modele" name="modele" 
                   value="<?= htmlspecialchars($voiture['modele']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="annee" class="form-label">Année</label>
            <input type="number" class="form-control" id="annee" name="annee" 
                   value="<?= htmlspecialchars($voiture['année']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="prix_jour" class="form-label">Prix par jour</label>
            <input type="number" class="form-control" id="prix_jour" name="prix_jour" 
                   value="<?= htmlspecialchars($voiture['prix_jour']) ?>" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="caution" class="form-label">Caution</label>
            <input type="number" class="form-control" id="caution" name="caution" 
                   value="<?= htmlspecialchars($voiture['caution']) ?>" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <?php if(!empty($voiture['image_loc'])): ?>
                <img src="<?= htmlspecialchars($voiture['image_loc']) ?>" 
                     alt="Image actuelle" 
                     class="mt-2" 
                     style="max-width: 200px;">
            <?php endif; ?>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="disponibilite" 
                   name="disponibilite" <?= $voiture['disponibilité'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="disponibilite">Disponible</label>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
    </form>
</div>


<?php require_once 'footer.php'; ?>