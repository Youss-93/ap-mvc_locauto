<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h1>Modifier une voiture</h1>

    <?php if(isset($message)): ?>
        <div class="alert alert-<?= $message_type ?? 'info' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($voiture)): ?>
        <form action="index.php?controller=voiture&action=modifier&id=<?= $voiture['id_voiture'] ?>" 
              method="POST" 
              enctype="multipart/form-data" 
              class="form-voiture">
            
            <div class="form-group">
                <label for="marque">Marque :</label>
                <input type="text" 
                       id="marque" 
                       name="marque" 
                       value="<?= htmlspecialchars($voiture['marque']) ?>" 
                       required 
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="modele">Modèle :</label>
                <input type="text" 
                       id="modele" 
                       name="modele" 
                       value="<?= htmlspecialchars($voiture['modele']) ?>" 
                       required 
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="annee">Année :</label>
                <input type="number" 
                       id="annee" 
                       name="annee" 
                       value="<?= htmlspecialchars($voiture['année']) ?>" 
                       min="1900" 
                       max="<?= date('Y') + 1 ?>" 
                       required 
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="prix_jour">Prix par jour (€) :</label>
                <input type="number" 
                       id="prix_jour" 
                       name="prix_jour" 
                       value="<?= htmlspecialchars($voiture['prix_jour']) ?>" 
                       min="0" 
                       step="0.01" 
                       required 
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="caution">Caution (€) :</label>
                <input type="number" 
                       id="caution" 
                       name="caution" 
                       value="<?= htmlspecialchars($voiture['caution']) ?>" 
                       min="0" 
                       step="0.01" 
                       required 
                       class="form-control">
            </div>

            <div class="form-group">
                <label>Image actuelle :</label>
                <?php if($voiture['image_loc']): ?>
                    <?php $imagePath = (strpos($voiture['image_loc'], 'assets/') === 0) ? $voiture['image_loc'] : 'assets/photos/voitures/' . $voiture['image_loc']; ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" 
                         alt="Image actuelle" 
                         class="img-thumbnail">
                <?php else: ?>
                    <p>Aucune image</p>
                <?php endif; ?>
                
                <label for="image_loc">Nouvelle image :</label>
                <input type="file" 
                       id="image_loc" 
                       name="image_loc" 
                       accept="image/*" 
                       class="form-control">
                <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">
            <?= !isset($voiture) ? 'Voiture non trouvée.' : 'Accès non autorisé.' ?>
        </div>
        <a href="index.php?controller=voiture&action=liste" class="btn btn-primary">Retour à la liste</a>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>