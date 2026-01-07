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
                    <?php if(!empty($voiture['image_loc']) && file_exists($voiture['image_loc'])): ?>
                        <img src="<?= htmlspecialchars($voiture['image_loc']) ?>" 
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
                        <span class="badge <?= $voiture['disponibilité'] ? 'bg-success' : 'bg-danger' ?>">
                            <?= $voiture['disponibilité'] ? 'Disponible' : 'Non disponible' ?>
                        </span>
                    </p>
                </div>

                <div class="car-actions">
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <!-- Les admins peuvent toujours modifier -->
                        <a href="index.php?controller=voiture&action=modifier&id=<?= $voiture['id_voiture'] ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        
                        <?php if($voiture['disponibilité']): ?>
                            <a href="index.php?controller=voiture&action=supprimer&id=<?= $voiture['id_voiture'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?');">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        <?php endif; ?>
                    <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'client'): ?>
                        <?php if($voiture['disponibilité']): ?>
                            <a href="index.php?controller=reservation&action=creer&id=<?= $voiture['id_voiture'] ?>" 
                               class="btn btn-success">
                                <i class="fas fa-calendar-plus"></i> Réserver
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>
                                Non disponible
                            </button>
                        <?php endif; ?>
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