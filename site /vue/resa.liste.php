<?php require_once 'vue/header.php'; ?>

<div class="container mt-4">
    <h1>Mes réservations</h1>

    <?php if(isset($message)): ?>
        <div class="alert alert-<?= $message_type ?? 'info' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Réservations actives -->
    <section class="mb-5">
        <h2>Réservations en cours</h2>
        <?php if(!empty($reservations_actives)): ?>
            <?php foreach($reservations_actives as $reservation): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></h5>
                        <p class="card-text">
                            Du <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?>
                            au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                        </p>
                        <span class="badge bg-success">En cours</span>
                        <a href="index.php?controller=reservation&action=detail&id=<?= $reservation['id_reservation'] ?>" 
                           class="btn btn-primary btn-sm">
                            Voir détails
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune réservation en cours</p>
        <?php endif; ?>
    </section>

    <!-- Réservations futures -->
    <section class="mb-5">
        <h2>Réservations à venir</h2>
        <?php if(!empty($reservations_futures)): ?>
            <!-- Similar structure as above -->
        <?php else: ?>
            <p>Aucune réservation à venir</p>
        <?php endif; ?>
    </section>

    <!-- Add sections for past and cancelled reservations -->
</div>

<?php require_once 'vue/footer.php'; ?>