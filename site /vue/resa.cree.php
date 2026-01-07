<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h2 class="mb-4">Réserver <?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h2>

    <div class="car-preview mb-4">
        <img src="<?= htmlspecialchars($voiture['image_loc']) ?>" 
             alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>" 
             class="img-fluid">
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Détails de la voiture</h5>
            <p>Prix par jour : <?= htmlspecialchars($voiture['prix_jour']) ?>€</p>
            <p>Caution : <?= htmlspecialchars($voiture['caution']) ?>€</p>
        </div>
    </div>

    <form action="index.php?controller=reservation&action=creer&id=<?= $voiture['id_voiture'] ?>" method="POST">
        <input type="hidden" name="id_voiture" value="<?= $voiture['id_voiture'] ?>">
        
        <div class="form-group mb-3">
            <label for="date_debut">Date de début</label>
            <input type="date" class="form-control" id="date_debut" name="date_debut" 
                   min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="date_fin">Date de fin</label>
            <input type="date" class="form-control" id="date_fin" name="date_fin" 
                   min="<?= date('Y-m-d') ?>" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="mode_paiement">Mode de paiement</label>
            <select class="form-control" id="mode_paiement" name="mode_paiement" required>
                <option value="">Choisissez un mode de paiement</option>
                <option value="par Carte">Carte bancaire</option>
                <option value="Par Virement">Virement bancaire</option>
                <option value="Paypal">PayPal</option>
            </select>
        </div>

        <button type="submit"  class="btn btn-primary">Confirmer la réservation</button>
        <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once 'vue/footer.php'; ?>