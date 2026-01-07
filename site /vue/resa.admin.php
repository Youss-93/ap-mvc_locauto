<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <h1>Gestion des réservations</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php if(!empty($reservations)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Client</th>
                        <th>Dates</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reservations as $reservation): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($reservation['nom_utilisateur'] . ' ' . $reservation['prenom_utilisateur']) ?>
                            </td>
                            <td>
                                Du <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?>
                                <br>
                                au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                            </td>
                            <td><?= number_format($reservation['prix_total'], 2) ?> €</td>
                            <td>
                                <span class="badge bg-<?= 
                                    $reservation['statut_reservation'] === 'confirmée' ? 'success' : 
                                    ($reservation['statut_reservation'] === 'en attente' ? 'warning' : 'danger') 
                                ?>">
                                    <?= htmlspecialchars(ucfirst($reservation['statut_reservation'])) ?>
                                </span>
                            </td>
                            <td>
    <div class="btn-group">
        <a href="index.php?controller=reservation&action=detail&id=<?= $reservation['id_reservation'] ?>" 
           class="btn btn-sm btn-info">
            <i class="fas fa-eye"></i> Détails
        </a>
        
        <?php if($reservation['statut_reservation'] === 'en attente'): ?>
    <form action="index.php?controller=reservation&action=confirmer" method="POST" style="display: inline;">
        <input type="hidden" name="id_reservation" value="<?= $reservation['id_reservation'] ?>">
        <input type="hidden" name="reference" value="<?= 'ADM_' . uniqid() ?>">
        <input type="hidden" name="montant" value="<?= $reservation['prix_total'] ?>">
        <input type="hidden" name="mode_paiement" value="validation_admin">
        
        <button type="submit" class="btn btn-sm btn-success" 
                onclick="return confirm('Voulez-vous confirmer cette réservation ?');">
            <i class="fas fa-check"></i> Confirmer
        </button>
    </form>

    <a href="index.php?controller=reservation&action=annuler&id=<?= $reservation['id_reservation'] ?>" 
       class="btn btn-sm btn-warning"
       onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
        <i class="fas fa-ban"></i> Annuler
    </a>
    <?php endif; ?>
        
        <a href="index.php?controller=reservation&action=supprimer&id=<?= $reservation['id_reservation'] ?>" 
           class="btn btn-sm btn-danger"
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette réservation ?');">
            <i class="fas fa-trash"></i> Supprimer
        </a>
                            </div>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Aucune réservation trouvée.
        </div>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>