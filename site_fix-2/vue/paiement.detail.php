<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h1>Détails du paiement</h1>

    <?php if(isset($message)): ?>
        <div class="alert alert-<?= $message_type ?? 'info' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="payment-details card">
        <div class="card-header">
            <h2>Réservation #<?= htmlspecialchars($reservation['id_reservation']) ?></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h3>Détails du véhicule</h3>
                    <p><strong>Véhicule :</strong> <?= htmlspecialchars($paiement['marque'] . ' ' . $paiement['modele']) ?></p>
                    <p><strong>Du :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($paiement['date_debut']))) ?></p>
                    <p><strong>Au :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($paiement['date_fin']))) ?></p>
                </div>
                <div class="col-md-6">
                    <h3>Informations de paiement</h3>
                    <p><strong>Montant :</strong> <?= htmlspecialchars($paiement['montant']) ?> €</p>
                    <p><strong>Mode de paiement :</strong> <?= htmlspecialchars($paiement['mode_paiement']) ?></p>
                    <p><strong>Date du paiement :</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($paiement['date_paiement']))) ?></p>
                    <p><strong>Statut :</strong> 
                        <span class="badge badge-<?= $paiement['statut_paiement'] === 'validé' ? 'success' : 
                            ($paiement['statut_paiement'] === 'en attente' ? 'warning' : 'danger') ?>">
                            <?= htmlspecialchars(ucfirst($paiement['statut_paiement'])) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="index.php?controller=reservation&action=liste" class="btn btn-secondary">
            Retour à mes réservations
        </a>
        <?php if($paiement['statut_paiement'] === 'en attente'): ?>
            <a href="index.php?controller=paiement&action=annuler&id=<?= $paiement['id_paiement'] ?>" 
               class="btn btn-danger"
               onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')">
                Annuler le paiement
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>
