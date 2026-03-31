<?php require_once 'vue/header.php'; ?>


    <div class="container">
        <h1>Bienvenue sur notre site de location</h1>
        
        <?php if(isset($message)): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <section class="historique">
            <h2>Notre Histoire</h2>
            <p>Bienvenue chez LocAuto, votre partenaire de confiance en location de véhicules depuis plus de 10 ans. 
            Notre mission est de vous offrir une expérience de location simple, transparente et sans complications.</p>
            <p>Avec une flotte de véhicules modernes et bien entretenus, nous nous engageons à fournir des services 
            de qualité supérieure à des tarifs compétitifs. Notre équipe dévouée est toujours disponible pour répondre 
            à vos besoins et vous assurer une expérience de location inoubliable.</p>
            <p>Découvrez nos véhicules disponibles ci-dessous et trouvez la voiture parfaite pour votre prochain voyage.</p>
        </section>

        <h2>Véhicules Disponibles</h2>
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