<?php require_once 'vue/header.php'; ?>

<div class="container">
    <div class="confirmation-paiement">
        <div class="confirmation-header">
            <h1>Confirmation de paiement</h1>
            
            <?php if($paiement['statut_paiement'] === 'validé'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Votre paiement a été validé avec succès !
                </div>
            <?php endif; ?>
        </div>

        <div class="details-reservation">
            <h2>Détails de votre réservation</h2>
            <div class="reservation-info">
                <p><strong>Voiture :</strong> <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></p>
                <p><strong>Période de location :</strong></p>
                <ul>
                    <li>Du : <?= htmlspecialchars(date('d/m/Y', strtotime($reservation['date_debut']))) ?></li>
                    <li>Au : <?= htmlspecialchars(date('d/m/Y', strtotime($reservation['date_fin']))) ?></li>
                </ul>
            </div>
        </div>

        <div class="details-paiement">
            <h2>Récapitulatif du paiement</h2>
            <div class="paiement-info">
                <p><strong>Montant payé :</strong> <?= htmlspecialchars($paiement['montant']) ?> €</p>
                <p><strong>Mode de paiement :</strong> <?= htmlspecialchars($paiement['mode_paiement']) ?></p>
                <p><strong>Date du paiement :</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($paiement['date_paiement']))) ?></p>
                <p><strong>Référence transaction :</strong> #<?= htmlspecialchars($paiement['id_paiement']) ?></p>
            </div>
        </div>

        <div class="actions">
            <a href="index.php?controller=reservation&action=liste" class="btn btn-primary">
                Voir mes réservations
            </a>
            <a href="index.php" class="btn btn-secondary">
                Retour à l'accueil
            </a>
        </div>

        <div class="confirmation-footer">
            <p>Un email de confirmation vous a été envoyé à <?= htmlspecialchars($_SESSION['utilisateur']) ?></p>
            <p class="contact-support">
                Pour toute question, contactez notre support au 01 23 45 67 89
            </p>
        </div>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>