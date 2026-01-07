<?php require_once 'header.php'; ?>

<div class="container">
    <h2>Mes Réservations</h2>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php if (empty($reservations)): ?>
        <div class="alert alert-info">
            Vous n'avez aucune réservation en cours.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reservations as $reservation): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></h5>
                            <p class="card-text">
                                Du : <?= htmlspecialchars($reservation['date_debut']) ?><br>
                                Au : <?= htmlspecialchars($reservation['date_fin']) ?><br>
                                Statut : <?= htmlspecialchars($reservation['statut_reservation']) ?><br>
                                Prix total : <?= htmlspecialchars($reservation['prix_total']) ?> €
                            </p>
                            <?php if($reservation['statut_reservation'] === 'en attente'): ?>
                                <a href="index.php?controller=reservation&action=annuler&id=<?= $reservation['id_reservation'] ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                    Annuler
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>