<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <h1>Paiement de la réservation</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <h2 style="font-size:1.1rem; margin-bottom:1rem;">Récapitulatif de la réservation</h2>
            <p><strong>Véhicule :</strong> <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></p>
            <p><strong>Du :</strong> <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?></p>
            <p><strong>Au :</strong> <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></p>
            <p><strong>Montant total :</strong> <span style="font-size:1.2rem; color:#1a56db;"><?= number_format($reservation['prix_total'], 2) ?> €</span></p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 style="font-size:1.1rem; margin-bottom:1rem;">Choisissez votre mode de paiement</h2>

            <form action="index.php?controller=reservation&action=confirmer" method="POST">
                <input type="hidden" name="id_reservation" value="<?= $reservation['id_reservation'] ?>">
                <input type="hidden" name="reference" value="PAY_<?= uniqid() ?>">
                <input type="hidden" name="montant" value="<?= $reservation['prix_total'] ?>">

                <div class="form-group" style="margin-bottom:1rem;">
                    <div class="form-check" style="margin-bottom:.5rem;">
                        <input type="radio" id="carte" name="mode_paiement" value="par Carte" class="form-check-input" required>
                        <label class="form-check-label" for="carte">Carte bancaire</label>
                    </div>
                    <div class="form-check" style="margin-bottom:.5rem;">
                        <input type="radio" id="virement" name="mode_paiement" value="Par Virement" class="form-check-input">
                        <label class="form-check-label" for="virement">Virement bancaire</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="paypal" name="mode_paiement" value="Paypal" class="form-check-input">
                        <label class="form-check-label" for="paypal">PayPal</label>
                    </div>
                </div>

                <div style="display:flex; gap:.8rem; flex-wrap:wrap; margin-top:1.5rem;">
                    <button type="submit" class="btn btn-success">Confirmer le paiement</button>
                    <a href="index.php?controller=reservation&action=liste" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>
