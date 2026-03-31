<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4"><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h1>

            <?php if(isset($message)): ?>
                <div class="alert alert-<?= $message_type ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <?php if($voiture['image_loc']): ?>
                            <?php $imagePath = (strpos($voiture['image_loc'], 'assets/') === 0) ? $voiture['image_loc'] : 'assets/photos/voitures/' . $voiture['image_loc']; ?>
                            <img src="<?= htmlspecialchars($imagePath) ?>" 
                                 class="card-img" 
                                 alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>">
                        <?php else: ?>
                            <div class="text-center p-4">
                                <em>Pas d'image disponible</em>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Caractéristiques</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Année :</strong> <?= htmlspecialchars($voiture['année']) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Prix journalier :</strong> <?= htmlspecialchars($voiture['prix_jour']) ?> €
                                </li>
                                <li class="list-group-item">
                                    <strong>Caution :</strong> <?= htmlspecialchars($voiture['caution']) ?> €
                                </li>
                                <li class="list-group-item">
                                    <strong>Disponibilité :</strong>
                                    <span class="badge <?= $voiture['disponibilité'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $voiture['disponibilité'] ? 'Disponible' : 'Non disponible' ?>
                                    </span>
                                </li>
                            </ul>

                            <div class="mt-4">
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'client' && $voiture['disponibilité']): ?>
                                    <a href="index.php?controller=reservation&action=creer&id=<?= $voiture['id_voiture'] ?>" 
                                       class="btn btn-primary">
                                        Réserver ce véhicule
                                    </a>
                                <?php endif; ?>

                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <a href="index.php?controller=voiture&action=modifier&id=<?= $voiture['id_voiture'] ?>" 
                                       class="btn btn-warning">
                                        Modifier
                                    </a>
                                    <?php if($voiture['disponibilité']): ?>
                                        <a href="index.php?controller=voiture&action=changerDisponibilite&id=<?= $voiture['id_voiture'] ?>&etat=0"
                                           class="btn btn-danger btn-indisponible"
                                           onclick="return confirm('Marquer ce véhicule comme non disponible ?');">
                                            Rendre indisponible
                                        </a>
                                    <?php else: ?>
                                        <a href="index.php?controller=voiture&action=changerDisponibilite&id=<?= $voiture['id_voiture'] ?>&etat=1"
                                           class="btn btn-success btn-disponible"
                                           onclick="return confirm('Rendre ce véhicule disponible ?');">
                                            Rendre disponible
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <a href="index.php?controller=voiture&action=liste" 
                                   class="btn btn-secondary">
                                    Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>