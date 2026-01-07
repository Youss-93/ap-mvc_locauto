<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'location_voitures');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');

// Configuration de l'application
define('APP_NAME', 'Location de Voitures');
define('APP_URL', 'http://localhost:8888/site');
define('APP_VERSION', '1.0.0');

// Configuration des dossiers
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/assets/images/voitures/');
define('MAX_FILE_SIZE', 2097152); // 2MB en bytes

// Configuration des emails
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'votre@email.com');
define('MAIL_PASSWORD', 'votre_mot_de_passe');
define('MAIL_FROM', 'contact@location-voitures.fr');
define('MAIL_FROM_NAME', 'Location de Voitures');

// Configuration des sessions
ini_set('session.gc_maxlifetime', 3600); // 1 heure
ini_set('session.cookie_lifetime', 3600);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

// Configuration du débuggage
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Messages d'erreur personnalisés
define('ERROR_MESSAGES', [
    'db_connection' => 'Erreur de connexion à la base de données',
    'not_found' => 'Page non trouvée',
    'not_authorized' => 'Accès non autorisé',
    'invalid_input' => 'Données invalides',
]);