<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <h1>Réserver une voiture</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php if(isset($voiture)): ?>
    <div class="card mb-4">
        <div class="card-body" style="display:flex; gap:1.5rem; align-items:center; flex-wrap:wrap;">
            <?php if(!empty($voiture['image_loc'])): ?>
                <img src="assets/photos/voitures/<?= htmlspecialchars($voiture['image_loc']) ?>"
                     alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>"
                     style="width:180px; height:120px; object-fit:cover; border-radius:8px;">
            <?php endif; ?>
            <div>
                <h3 style="margin:0 0 .5rem"><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h3>
                <p style="margin:0">Année : <?= htmlspecialchars($voiture['année'] ?? $voiture['annee'] ?? '') ?></p>
                <p style="margin:0"><strong><?= htmlspecialchars($voiture['prix_jour']) ?> € / jour</strong></p>
                <p style="margin:0; color:#888">Caution : <?= htmlspecialchars($voiture['caution']) ?> €</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 style="font-size:1.2rem; margin-bottom:1.2rem;">Choisissez vos dates</h2>
            <form action="index.php?controller=reservation&action=creer&id=<?= $voiture['id_voiture'] ?>" method="POST">
                <div class="form-group" style="margin-bottom:1rem;">
                    <label for="date_debut" class="form-label"><strong>Date de début</strong></label>
                    <input type="date" id="date_debut" name="date_debut" class="form-control"
                           min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label for="date_fin" class="form-label"><strong>Date de fin</strong></label>
                    <input type="date" id="date_fin" name="date_fin" class="form-control"
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>

                <div id="prix-preview" style="display:none; background:#f0f5fb; border-radius:8px; padding:1rem; margin-bottom:1.2rem;">
                    <p style="margin:0">Durée : <strong id="nb-jours">0</strong> jour(s)</p>
                    <p style="margin:0">Montant estimé : <strong id="montant-total">0</strong> €</p>
                </div>

                <div style="display:flex; gap:.8rem; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                    <a href="index.php?controller=voiture&action=liste" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const prixJour = <?= (float)($voiture['prix_jour'] ?? 0) ?>;
const debut = document.getElementById('date_debut');
const fin   = document.getElementById('date_fin');
const preview = document.getElementById('prix-preview');

function calculer() {
    if (!debut.value || !fin.value) { preview.style.display='none'; return; }
    const d1 = new Date(debut.value), d2 = new Date(fin.value);
    const jours = Math.round((d2 - d1) / 86400000);
    if (jours <= 0) { preview.style.display='none'; return; }
    document.getElementById('nb-jours').textContent = jours;
    document.getElementById('montant-total').textContent = (jours * prixJour).toFixed(2);
    preview.style.display = 'block';
    fin.min = debut.value;
}

debut.addEventListener('change', () => { fin.min = debut.value; calculer(); });
fin.addEventListener('change', calculer);
</script>

<?php require_once 'vue/footer.php'; ?>
