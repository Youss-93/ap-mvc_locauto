<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>À propos</h3>
                <p>Location de voitures de luxe et de prestige</p>
            </div>
            
            <div class="footer-section">
                <h3>Navigation</h3>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="index.php?controller=voiture&action=liste">Nos voitures</a></li>
                    <?php if(!isset($_SESSION['utilisateur'])): ?>
                        <li><a href="index.php?controller=utilisateur&action=login">Connexion</a></li>
                        <li><a href="index.php?controller=utilisateur&action=register">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email : contact@location-auto.fr</p>
                <p>Tél : 01 23 45 67 89</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Location Auto. Tous droits réservés.</p>
        </div>
    </div>
</footer>
</body>
</html>