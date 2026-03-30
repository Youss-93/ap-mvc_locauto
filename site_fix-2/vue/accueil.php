<?php require_once 'vue/header.php'; ?>


    <div class="container">
        <h1>Bienvenue sur notre site de location</h1>
        
        <?php if(isset($message)): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="voitures-grid">
            <?php foreach($voitures as $voiture): ?>
                <div class="voiture-card">
                    <img src="assets/photos/voitures/<?= htmlspecialchars($voiture['image_loc']) ?>" alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>">
                    <h3><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h3>
                    <p>Prix par jour : <?= htmlspecialchars($voiture['prix_jour']) ?> €</p>
                    <a href="index.php?controller=voiture&action=detail&id=<?= $voiture['id_voiture'] ?>" class="btn">Voir détails</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php require_once 'vue/footer.php'; ?>