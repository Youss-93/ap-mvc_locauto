<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h2 class="mb-4">Mes réservations</h2>
    
    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= $_SESSION['message'] ?>
        </div>
    <?php endif; ?>

    <?php if(empty($reservations)): ?>
        <div class="alert alert-info">
            Vous n'avez pas encore de réservations.
        </div>
    <?php else: ?>
        <div class="reservations-grid">
            <?php foreach($reservations as $reservation): ?>
                <div class="reservation-card">
                    <div class="car-image">
                        <img src="<?= htmlspecialchars($reservation['image_loc']) ?>" 
                             alt="<?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?>">
                    </div>
                    <div class="reservation-info">
                        <h3><?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></h3>
                        <p>Du : <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?></p>
                        <p>Au : <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></p>
                        <p>Prix total : <?= htmlspecialchars($reservation['prix_total']) ?>€</p>
                        <p>Statut : <span class="badge bg-<?= $reservation['statut_reservation'] === 'confirmée' ? 'success' : 
                            ($reservation['statut_reservation'] === 'en attente' ? 'warning' : 'danger') ?>">
                            <?= htmlspecialchars($reservation['statut_reservation']) ?></span>
                        </p>
                    </div>
                    <div class="reservation-actions">
                        <a href="index.php?controller=reservation&action=detail&id=<?= $reservation['id_reservation'] ?>" 
                           class="btn btn-primary">Détails</a>
                        <?php if($reservation['statut_reservation'] === 'en attente'): ?>
                            <a href="index.php?controller=reservation&action=annuler&id=<?= $reservation['id_reservation'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                Annuler
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>