<?php require_once 'vue/header.php'; ?>

<div class="container">
    <div style="max-width: 1000px; margin: 0 auto;\">
        <h2 style=\"margin-bottom: 2rem; color: #1e293b;\">
            <i class=\"fas fa-list\"></i> Mes réservations
        </h2>

        <?php if(isset($message)): ?>
            <div class=\"alert alert-<?= $message_type ?? 'info' ?>\">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Réservations en cours -->
        <section style=\"margin-bottom: 3rem;\">
            <h3 style=\"color: #1e293b; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;\">
                <i class=\"fas fa-hourglass-half\" style=\"color: #ea580c;\"></i> Réservations en cours
                <?php if(!empty($reservations_actives)): ?>
                    <span class=\"badge bg-warning\" style=\"font-size: 0.85rem;\"><?= count($reservations_actives) ?></span>
                <?php endif; ?>
            </h3>
            
            <?php if(!empty($reservations_actives)): ?>
                <div style=\"display: grid; gap: 1.5rem;\">
                    <?php foreach($reservations_actives as $reservation): ?>
                        <div class=\"reservation-card\">
                            <div class=\"reservation-card-header\">
                                <h3>
                                    <i class=\"fas fa-car\"></i> <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?>
                                </h3>
                                <span class=\"badge bg-warning\" style=\"font-size: 0.9rem; padding: 0.5rem 1rem;\">
                                    <i class=\"fas fa-clock\"></i> En cours
                                </span>
                            </div>
                            
                            <div class=\"reservation-card-body\">
                                <p>
                                    <strong><i class=\"fas fa-calendar-alt\"></i> Dates :</strong>
                                    Du <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> 
                                    au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                </p>
                                <p>
                                    <strong><i class=\"fas fa-euro-sign\"></i> Prix total :</strong>
                                    <span style=\"color: #16a34a; font-weight: 700; font-size: 1.1rem;\">
                                        <?= htmlspecialchars($reservation['prix_total']) ?> €
                                    </span>
                                </p>
                            </div>

                            <div class=\"reservation-card-footer\">
                                <span style=\"color: #64748b; font-size: 0.9rem;\">
                                    Réf: #<?= htmlspecialchars($reservation['id_reservation']) ?>
                                </span>
                                <a href=\"index.php?controller=reservation&action=detail&id=<?= $reservation['id_reservation'] ?>\" 
                                   class=\"btn btn-primary btn-sm\">
                                    <i class=\"fas fa-eye\"></i> Détails
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class=\"alert alert-info\" style=\"text-align: center; padding: 1.5rem;\">
                    <i class=\"fas fa-check-circle\" style=\"color: #0891b2; margin-right: 0.5rem;\"></i>
                    Aucune réservation en cours
                </div>
            <?php endif; ?>
        </section>

        <!-- Réservations à venir -->
        <section style=\"margin-bottom: 3rem;\">
            <h3 style=\"color: #1e293b; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;\">
                <i class=\"fas fa-calendar-plus\" style=\"color: #2563eb;\"></i> Réservations à venir
                <?php if(!empty($reservations_futures)): ?>
                    <span class=\"badge bg-info\" style=\"font-size: 0.85rem;\"><?= count($reservations_futures) ?></span>
                <?php endif; ?>
            </h3>
            
            <?php if(!empty($reservations_futures)): ?>
                <div style=\"display: grid; gap: 1.5rem;\">
                    <?php foreach($reservations_futures as $reservation): ?>
                        <div class=\"reservation-card\">
                            <div class=\"reservation-card-header\">
                                <h3>
                                    <i class=\"fas fa-car\"></i> <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?>
                                </h3>
                                <span class=\"badge bg-info\" style=\"font-size: 0.9rem; padding: 0.5rem 1rem;\">
                                    <i class=\"fas fa-calendar\"></i> À venir
                                </span>
                            </div>
                            
                            <div class=\"reservation-card-body\">
                                <p>
                                    <strong><i class=\"fas fa-calendar-alt\"></i> Dates :</strong>
                                    Du <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> 
                                    au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                </p>
                                <p>
                                    <strong><i class=\"fas fa-euro-sign\"></i> Prix total :</strong>
                                    <span style=\"color: #16a34a; font-weight: 700; font-size: 1.1rem;\">
                                        <?= htmlspecialchars($reservation['prix_total']) ?> €
                                    </span>
                                </p>
                            </div>

                            <div class=\"reservation-card-footer\">
                                <span style=\"color: #64748b; font-size: 0.9rem;\">
                                    Réf: #<?= htmlspecialchars($reservation['id_reservation']) ?>
                                </span>
                                <a href=\"index.php?controller=reservation&action=detail&id=<?= $reservation['id_reservation'] ?>\" 
                                   class=\"btn btn-primary btn-sm\">
                                    <i class=\"fas fa-eye\"></i> Détails
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class=\"alert alert-info\" style=\"text-align: center; padding: 1.5rem;\">
                    <i class=\"fas fa-inbox\" style=\"color: #0891b2; margin-right: 0.5rem;\"></i>
                    Aucune réservation à venir
                </div>
            <?php endif; ?>
        </section>

        <!-- Réservations passées -->
        <section>
            <h3 style=\"color: #1e293b; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;\">
                <i class=\"fas fa-history\" style=\"color: #64748b;\"></i> Historique
                <?php if(!empty($reservations_passees)): ?>
                    <span class=\"badge bg-secondary\" style=\"font-size: 0.85rem;\"><?= count($reservations_passees) ?></span>
                <?php endif; ?>
            </h3>
            
            <?php if(!empty($reservations_passees)): ?>
                <div style=\"display: grid; gap: 1.5rem;\">
                    <?php foreach($reservations_passees as $reservation): ?>
                        <div class=\"reservation-card\" style=\"opacity: 0.85;\">
                            <div class=\"reservation-card-header\" style=\"background: linear-gradient(135deg, #64748b, #475569);\">
                                <h3>
                                    <i class=\"fas fa-car\"></i> <?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?>
                                </h3>
                                <span class=\"badge bg-secondary\" style=\"font-size: 0.9rem; padding: 0.5rem 1rem;\">
                                    <i class=\"fas fa-check\"></i> Passée
                                </span>
                            </div>
                            
                            <div class=\"reservation-card-body\">
                                <p>
                                    <strong><i class=\"fas fa-calendar-alt\"></i> Dates :</strong>
                                    Du <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> 
                                    au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                </p>
                                <p>
                                    <strong><i class=\"fas fa-euro-sign\"></i> Prix total :</strong>
                                    <span style=\"color: #16a34a; font-weight: 700; font-size: 1.1rem;\">
                                        <?= htmlspecialchars($reservation['prix_total']) ?> €
                                    </span>
                                </p>
                            </div>

                            <div class=\"reservation-card-footer\">
                                <span style=\"color: #64748b; font-size: 0.9rem;\">
                                    Réf: #<?= htmlspecialchars($reservation['id_reservation']) ?>
                                </span>
                                <a href=\"index.php?controller=reservation&action=detail&id=<?= $reservation['id_reservation'] ?>\" 
                                   class=\"btn btn-secondary btn-sm\">
                                    <i class=\"fas fa-eye\"></i> Détails
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class=\"alert alert-info\" style=\"text-align: center; padding: 1.5rem;\">
                    <i class=\"fas fa-archive\" style=\"color: #64748b; margin-right: 0.5rem;\"></i>
                    Aucune réservation passée
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require_once 'vue/footer.php'; ?>