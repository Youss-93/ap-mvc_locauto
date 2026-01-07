<!DOCTYPE html>
<?php require_once 'vue/header.php'; ?>

<html>
<head>
    <title>Location de voitures</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


    <div class="container">
        <h1>Bienvenue sur notre site de location</h1>
        
        <?php if(isset($message)): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <div class="intro">
            <p>Nous proposons une large gamme de voitures de luxe et de prestige à louer.</p>
            <p>Explorez notre sélection et trouvez la voiture parfaite pour votre prochain voyage.</p>
            <p>Pour plus d'informations, n'hésitez pas à nous contacter.</p>
    </div>
    </div>
</body>
</html>
<?php require_once 'vue/footer.php'; ?>