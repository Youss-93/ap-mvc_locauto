<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <?php if(!isset($reservation)): ?>
        <div class="alert alert-danger">
            Réservation non trouvée
        </div>
        <a href="index.php?controller=reservation&action=liste" class="btn btn-primary">
            Retour à la liste
        </a>
    <?php else: ?>
        <h1>Détails de la réservation</h1>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?>
                </h5>
                
                <div class="reservation-info mt-3">
                    <p><strong>Dates :</strong></p>
                    <ul>
                        <li>Début : <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?></li>
                        <li>Fin : <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></li>
                    </ul>
                    <p><strong>Prix total :</strong> <?= htmlspecialchars($reservation['prix_total']) ?> €</p>
                    <p>
                        <strong>Statut :</strong>
                        <span class="badge bg-<?= $reservation['statut_reservation'] === 'confirmée' ? 'success' : 
                            ($reservation['statut_reservation'] === 'en attente' ? 'warning' : 'danger') ?>">
                            <?= htmlspecialchars(ucfirst($reservation['statut_reservation'])) ?>
                        </span>
                    </p>
                </div>

                <div class="actions mt-4">
                    <a href="index.php?controller=reservation&action=liste" class="btn btn-secondary">
                        Retour à la liste
                    </a>
                    
                    <?php if($reservation['statut_reservation'] === 'en attente'): ?>
                        <a href="index.php?controller=paiement&action=effectuer&id=<?= $reservation['id_reservation'] ?>" 
                           class="btn btn-primary">
                            Payer maintenant
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>