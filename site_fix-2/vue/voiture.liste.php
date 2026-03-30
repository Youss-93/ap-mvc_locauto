<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h1>Nos Voitures Disponibles</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="admin-actions" style="margin-bottom:1.5rem;">
            <a href="index.php?controller=voiture&action=ajouter" class="btn btn-primary">
                + Ajouter une voiture
            </a>
        </div>
    <?php endif; ?>

    <div class="voitures-grid">
        <?php foreach($voitures as $voiture): ?>
            <div class="voiture-card">

                <!-- Image uniforme -->
                <div class="voiture-image-wrap">
                    <?php if(!empty($voiture['image_loc'])): ?>
                        <?php $imagePath = (strpos($voiture['image_loc'], 'assets/') === 0) ? $voiture['image_loc'] : 'assets/photos/voitures/' . $voiture['image_loc']; ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>"
                             alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>"
                             class="voiture-img">
                    <?php else: ?>
                        <div class="voiture-img-placeholder">Pas d'image</div>
                    <?php endif; ?>
                </div>

                <div class="voiture-info">
                    <h3><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h3>
                    <p class="annee">Année : <?= htmlspecialchars($voiture['année']) ?></p>
                    <p class="prix">Prix/jour : <strong><?= htmlspecialchars($voiture['prix_jour']) ?> €</strong></p>
                    <p class="caution">Caution : <?= htmlspecialchars($voiture['caution']) ?> €</p>

                    <!-- Catégories -->
                    <?php if(!empty($voiture['categories'])): ?>
                        <div class="voiture-categories">
                            <?php foreach($voiture['categories'] as $cat): ?>
                                <span class="badge-categorie"><?= htmlspecialchars($cat['libelle_categorie']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="voiture-actions">
                        <a href="index.php?controller=voiture&action=detail&id=<?= $voiture['id_voiture'] ?>"
                           class="btn btn-secondary btn-sm">Voir détails</a>

                        <?php if(isset($_SESSION['utilisateur']) && $_SESSION['role'] === 'client'): ?>
                            <?php if($voiture['disponibilité']): ?>
                                <a href="index.php?controller=reservation&action=creer&id=<?= $voiture['id_voiture'] ?>"
                                   class="btn btn-success btn-sm">Réserver</a>
                            <?php else: ?>
                                <span class="badge badge-warning">Non disponible</span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="index.php?controller=voiture&action=modifier&id=<?= $voiture['id_voiture'] ?>"
                               class="btn btn-warning btn-sm">Modifier</a>
                            <a href="index.php?controller=voiture&action=supprimer&id=<?= $voiture['id_voiture'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer cette voiture ?')">Supprimer</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>
