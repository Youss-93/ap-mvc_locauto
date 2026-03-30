<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h1>Historique des paiements</h1>

    <?php if(isset($message)): ?>
        <div class="alert alert-<?= $message_type ?? 'info' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($paiements)): ?>
        <div class="paiements-grid">
            <?php foreach($paiements as $paiement): ?>
                <div class="paiement-card">
                    <div class="paiement-header">
                        <h3>Réservation #<?= htmlspecialchars($paiement['id_reservation']) ?></h3>
                        <span class="badge badge-<?= $paiement['statut_paiement'] === 'validé' ? 'success' : 'warning' ?>">
                            <?= htmlspecialchars(ucfirst($paiement['statut_paiement'])) ?>
                        </span>
                    </div>

                    <div class="paiement-details">
                        <p><strong>Voiture :</strong> <?= htmlspecialchars($paiement['marque'] . ' ' . $paiement['modele']) ?></p>
                        <p><strong>Période :</strong> 
                            Du <?= htmlspecialchars(date('d/m/Y', strtotime($paiement['date_debut']))) ?>
                            au <?= htmlspecialchars(date('d/m/Y', strtotime($paiement['date_fin']))) ?>
                        </p>
                        <p><strong>Montant :</strong> <?= htmlspecialchars($paiement['montant']) ?> €</p>
                        <p><strong>Mode de paiement :</strong> <?= htmlspecialchars(ucfirst($paiement['mode_paiement'])) ?></p>
                        <p><strong>Date du paiement :</strong> 
                            <?= htmlspecialchars(date('d/m/Y H:i', strtotime($paiement['date_paiement']))) ?>
                        </p>
                    </div>

                    <div class="paiement-actions">
                        <a href="index.php?controller=paiement&action=detail&id=<?= $paiement['id_paiement'] ?>" 
                           class="btn btn-primary">
                            Voir détails
                        </a>
                        <?php if($paiement['statut_paiement'] === 'en attente'): ?>
                            <a href="index.php?controller=paiement&action=annuler&id=<?= $paiement['id_paiement'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')">
                                Annuler
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-results">
            <p>Aucun paiement trouvé dans l'historique.</p>
            <a href="index.php?controller=voiture&action=liste" class="btn btn-primary">
                Louer une voiture
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>