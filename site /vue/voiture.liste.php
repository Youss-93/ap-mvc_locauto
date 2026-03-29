<?php require_once 'header.php'; ?>

<div class="container">
    <h2 class="my-4">Liste des voitures</h2>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="cars-grid">
        <?php foreach($voitures as $voiture): ?>
            <div class="car-card">
                <div class="car-image">
                    <?php 
                        $imagePath = 'assets/photos/voitures/' . ($voiture['image_loc'] ?? 'default.jpg');
                        $fullPath = dirname(__DIR__) . '/' . $imagePath;
                    ?>
                    <?php if(!empty($voiture['image_loc']) && file_exists($fullPath)): ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" 
                             alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>">
                    <?php else: ?>
                        <img src="assets/photos/voitures/default.jpg" 
                             alt="Image par défaut">
                    <?php endif; ?>
                </div>
                
                <div class="car-info">
                    <h3><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h3>
                    <p>Année : <?= htmlspecialchars($voiture['année']) ?></p>
                    <p>Prix/jour : <?= htmlspecialchars($voiture['prix_jour']) ?>€</p>
                    <p>Caution : <?= htmlspecialchars($voiture['caution']) ?>€</p>
                    <p class="status">
                        <span class="badge bg-success">
                            Vérification à la réservation
                        </span>
                    </p>
                    <?php if(!empty($voiture['categories'])): ?>
                        <div class="categories mt-2">
                            <strong>Catégories :</strong><br>
                            <?php foreach($voiture['categories'] as $categorie): ?>
                                <span class="badge bg-info">
                                    <?= htmlspecialchars($categorie['libelle_categorie']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="car-actions">
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <!-- Les admins peuvent toujours modifier -->
                        <a href="index.php?controller=voiture&action=modifier&id=<?= $voiture['id_voiture'] ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        
                        <a href="index.php?controller=voiture&action=supprimer&id=<?= $voiture['id_voiture'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?');">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'client'): ?>
                        <a href="index.php?controller=reservation&action=creer&id=<?= $voiture['id_voiture'] ?>" 
                           class="btn btn-success">
                            <i class="fas fa-calendar-plus"></i> Réserver
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="mt-4">
            <a href="index.php?controller=voiture&action=ajouter" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une voiture
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>