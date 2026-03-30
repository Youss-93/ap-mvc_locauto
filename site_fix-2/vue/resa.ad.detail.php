<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <h1>Détails de la réservation</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <h2>Véhicule réservé</h2>
            <p><strong>Modèle :</strong> <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></p>
            <p><strong>Prix journalier :</strong> <?= number_format($reservation['prix_jour'], 2) ?> €</p>
            <p><strong>Caution :</strong> <?= number_format($reservation['caution'], 2) ?> €</p>

            <h2 class="mt-4">Informations client</h2>
            <p><strong>Nom :</strong> <?= htmlspecialchars($reservation['nom_utilisateur'] . ' ' . $reservation['prenom_utilisateur']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($reservation['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($reservation['num_tel']) ?></p>

            <h2 class="mt-4">Détails réservation</h2>
            <p><strong>Période :</strong> Du <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> 
               au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></p>
            <p><strong>Prix total :</strong> <?= number_format($reservation['prix_total'], 2) ?> €</p>
            <p><strong>Statut :</strong> 
                <span class="badge bg-<?= $reservation['statut_reservation'] === 'confirmée' ? 'success' : 
                    ($reservation['statut_reservation'] === 'en attente' ? 'warning' : 'danger') ?>">
                    <?= htmlspecialchars(ucfirst($reservation['statut_reservation'])) ?>
                </span>
            </p>

            <div class="mt-4">
                <a href="index.php?controller=reservation&action=liste" class="btn btn-secondary">Retour</a>
                <?php if($_SESSION['role'] === 'admin' && $reservation['statut_reservation'] === 'en attente'): ?>
                    <a href="index.php?controller=reservation&action=confirmer&id=<?= $reservation['id_reservation'] ?>" 
                       class="btn btn-success">
                        <i class="fas fa-check"></i> Confirmer la réservation
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>