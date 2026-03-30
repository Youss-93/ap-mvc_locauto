<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h1>Ajouter une nouvelle voiture</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <form action="index.php?controller=voiture&action=ajouter" method="POST" enctype="multipart/form-data" class="form-voiture">

        <div class="form-group">
            <label for="marque">Marque :</label>
            <input type="text" id="marque" name="marque" required class="form-control"
                   value="<?= htmlspecialchars($voiture['marque'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="modele">Modèle :</label>
            <input type="text" id="modele" name="modele" required class="form-control"
                   value="<?= htmlspecialchars($voiture['modele'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="annee">Année :</label>
            <input type="number" id="annee" name="annee" min="1900" max="<?= date('Y') + 1 ?>" required class="form-control"
                   value="<?= htmlspecialchars($voiture['annee'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="prix_jour">Prix par jour (€) :</label>
            <input type="number" id="prix_jour" name="prix_jour" min="0" step="0.01" required class="form-control"
                   value="<?= htmlspecialchars($voiture['prix_jour'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="caution">Caution (€) :</label>
            <input type="number" id="caution" name="caution" min="0" step="0.01" required class="form-control"
                   value="<?= htmlspecialchars($voiture['caution'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Catégories :</label>
            <?php if(!empty($toutesCategories)): ?>
                <div class="categories-checkboxes">
                    <?php foreach($toutesCategories as $cat): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="categories[]"
                                   id="cat_<?= $cat['id_categorie'] ?>"
                                   value="<?= $cat['id_categorie'] ?>">
                            <label class="form-check-label" for="cat_<?= $cat['id_categorie'] ?>">
                                <?= htmlspecialchars($cat['libelle_categorie']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <small class="form-text text-muted">Sélectionnez au moins une catégorie</small>
            <?php else: ?>
                <p class="text-muted">Aucune catégorie disponible.</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="image_loc">Image :</label>
            <input type="file" id="image_loc" name="image_loc" accept="image/*" class="form-control">
            <small class="form-text text-muted">Format accepté : JPG, PNG, GIF. Taille max : 2Mo</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Ajouter la voiture</button>
            <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require_once 'vue/footer.php'; ?>
