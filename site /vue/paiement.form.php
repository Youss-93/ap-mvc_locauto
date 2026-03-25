<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h2 class="mb-4">Détails de votre réservation</h2>
    
    <?php if (isset($_SESSION['paiement_temp']) && is_array($_SESSION['paiement_temp'])): 
        $paiement = $_SESSION['paiement_temp'];
    ?>
    <div class="card mb-4">
        <div class="card-body">
            <h3>Voiture : <?= isset($paiement['voiture']) ? htmlspecialchars($paiement['voiture']['marque'] . ' ' . $paiement['voiture']['modele']) : 'Non spécifiée' ?></h3>
            
            <h4>Période de location :</h4>
            <p>Du : <?= isset($paiement['date_debut']) ? date('d/m/Y', strtotime($paiement['date_debut'])) : 'Non spécifiée' ?></p>
            <p>Au : <?= isset($paiement['date_fin']) ? date('d/m/Y', strtotime($paiement['date_fin'])) : 'Non spécifiée' ?></p>

            <h4>Récapitulatif du paiement</h4>
            <p>Montant à payer : <?= isset($paiement['montant']) ? htmlspecialchars($paiement['montant']) : '0' ?>€</p>
            <p>Mode de paiement : <?= isset($paiement['mode_paiement']) ? htmlspecialchars($paiement['mode_paiement']) : 'Non spécifié' ?></p>
            <p>Référence transaction : #<?= isset($paiement['reference']) ? htmlspecialchars($paiement['reference']) : 'Non générée' ?></p>

            <form action="index.php?controller=reservation&action=finaliser" method="POST">
                <input type="hidden" name="id_reservation" value="<?= htmlspecialchars($paiement['id_reservation']) ?>">
                <input type="hidden" name="reference" value="<?= htmlspecialchars($paiement['reference']) ?>">
                <input type="hidden" name="montant" value="<?= htmlspecialchars($paiement['montant']) ?>">
                
                <div class="form-group mb-3">
                    <label for="mode_paiement">Mode de paiement</label>
                    <select class="form-control" id="mode_paiement" name="mode_paiement" required>
                        <option value="">Choisissez un mode de paiement</option>
                        <option value="par Carte">Carte bancaire</option>
                        <option value="Par Virement">Virement bancaire</option>
                        <option value="Paypal">PayPal</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
                <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">
        Aucune information de paiement disponible.
        <br>
        <a href="index.php?controller=voiture&action=liste" class="btn btn-primary mt-3">Retourner à la liste des voitures</a>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>