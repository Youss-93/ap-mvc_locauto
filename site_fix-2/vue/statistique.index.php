<?php require_once 'vue/header.php'; ?>

<div class="container">
    <h1>Statistiques des categories</h1>

    <p style="margin-bottom: 1.2rem; color: var(--text-muted);">
        Nombre de reservations par categorie (voitures reservees, tous statuts confondus).
    </p>

    <?php if(!empty($stats_categories)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Categorie</th>
                        <th>Nombre de reservations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rang = 1; ?>
                    <?php foreach($stats_categories as $stat): ?>
                        <tr>
                            <td><strong><?= $rang++ ?></strong></td>
                            <td><?= htmlspecialchars($stat['libelle_categorie']) ?></td>
                            <td><?= (int) $stat['nb_reservations'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Aucune statistique disponible pour le moment.</div>
    <?php endif; ?>
</div>

<?php require_once 'vue/footer.php'; ?>
