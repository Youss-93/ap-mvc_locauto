<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">Mon Profil</h1>
                    <div class="btn-group">
                        <a href="index.php?controller=utilisateur&action=modifier" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="index.php?controller=utilisateur&action=supprimer" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Supprimer mon compte
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php if(isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                    <?php endif; ?>

                    <div class="info-utilisateur mb-4">
                        <h2 class="h4">Informations personnelles</h2>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($utilisateur['nom_utilisateur']) ?></p>
                        <p><strong>Prénom :</strong> <?= htmlspecialchars($utilisateur['prenom_utilisateur']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($utilisateur['num_tel'] ?? 'Non renseigné') ?></p>
                    </div>

                    <div class="reservations mt-4">
                        <h2 class="h4">Mes réservations</h2>
                        <?php if(!empty($reservations)): ?>
                            <?php foreach($reservations as $reservation): ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?>
                                        </h5>
                                        <p class="card-text">
                                            Du <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?>
                                            au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                        </p>
                                        <p class="card-text">
                                            <strong>Prix total :</strong> 
                                            <?= htmlspecialchars($reservation['prix_total']) ?> €
                                        </p>
                                        <span class="badge bg-<?= $reservation['statut_reservation'] === 'confirmée' ? 'success' : 
                                            ($reservation['statut_reservation'] === 'en attente' ? 'warning' : 'danger') ?>">
                                            <?= htmlspecialchars(ucfirst($reservation['statut_reservation'])) ?>
                                        </span>
                                        <a href="index.php?controller=reservation&action=detail&id=<?= $reservation['id_reservation'] ?>" 
                                           class="btn btn-primary btn-sm">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Vous n'avez pas encore de réservations.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>