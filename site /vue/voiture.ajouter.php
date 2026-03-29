<?php 
require_once 'vue/header.php';

// Charger les catégories disponibles
$categorieModel = new Categorie();
$categories = $categorieModel->getAll();
?>

<div class="container">
    <h1>Ajouter une nouvelle voiture</h1>

    <?php if(isset($message)): ?>
        <div class="alert alert-<?= $message_type ?? 'info' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <form action="index.php?controller=voiture&action=ajouter" method="POST" enctype="multipart/form-data" class="form-voiture">
            <div class="form-group">
                <label for="marque">Marque :</label>
                <input type="text" id="marque" name="marque" required class="form-control">
            </div>

            <div class="form-group">
                <label for="modele">Modèle :</label>
                <input type="text" id="modele" name="modele" required class="form-control">
            </div>

            <div class="form-group">
                <label for="annee">Année :</label>
                <input type="number" id="annee" name="annee" min="1900" max="<?= date('Y') + 1 ?>" required class="form-control">
            </div>

            <div class="form-group">
                <label for="prix_jour">Prix par jour (€) :</label>
                <input type="number" id="prix_jour" name="prix_jour" min="0" step="0.01" required class="form-control">
            </div>

            <div class="form-group">
                <label for="caution">Caution (€) :</label>
                <input type="number" id="caution" name="caution" min="0" step="0.01" required class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="image_loc">Image de la voiture</label>
                <input type="file" class="form-control" id="image_loc" name="image_loc" accept="image/jpeg,image/png" required>
                <small class="form-text text-muted">Formats acceptés : JPG, JPEG, PNG</small>
            </div>

            <div class="form-group">
                <label for="categories">Catégories de la voiture :</label>
                <select id="categories" name="categories[]" multiple class="form-control" required>
                    <?php foreach($categories as $categorie): ?>
                        <option value="<?= $categorie['id_categorie'] ?>">
                            <?= htmlspecialchars($categorie['libelle_categorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Vous pouvez sélectionner plusieurs catégories (Ctrl+Clic ou Cmd+Clic)</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajouter la voiture</button>
                <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">
            Accès non autorisé. Vous devez être administrateur pour ajouter une voiture.
        </div>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>