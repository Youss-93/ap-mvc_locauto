<?php
session_start();

// Configuration

require_once 'modele/Database.php';

// Récupération du contrôleur et de l'action
$controller = $_GET['controller'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

// Formatage du nom du contrôleur
$controller = ucfirst(strtolower($controller)) . 'Controlleur';
$controllerFile = "controleur/$controller.php";
// Vérification de l'existence du contrôleur
$controllers = [
    'voiture' => ['liste', 'detail', 'ajouter', 'modifier', 'supprimer'],
    'utilisateur' => ['login', 'register', 'logout'],
    'reservation' => ['creer', 'confirmer', 'annuler', 'liste', 'detail', 'mesReservations', 'supprimer']
];


try {
    // Vérification de l'existence du fichier contrôleur
    if (!file_exists($controllerFile)) {
        throw new Exception("Le contrôleur demandé n'existe pas");
    }

    // Inclusion du fichier contrôleur
    require_once $controllerFile;

    $routes = [
        'utilisateur' => [
            'login' => 'login',
            'logout' => 'logout',
            'register' => 'register',
            'profil' => 'profil',
            'modifier' => 'modifier',  // Ajoutez cette ligne si elle n'existe pas
            'supprimer' => 'supprimer'
        ],
    ];

    // Vérification de l'existence de la classe
    if (!class_exists($controller)) {
        throw new Exception("La classe du contrôleur n'existe pas");
    }

    // Instanciation du contrôleur
    $controllerInstance = new $controller();

    // Vérification de l'existence de la méthode
    if (!method_exists($controllerInstance, $action)) {
        throw new Exception("L'action demandée n'existe pas");
    }

    // Exécution de l'action
    $controllerInstance->$action();

} catch (Exception $e) {
    // En cas d'erreur, redirection vers la page d'accueil avec message d'erreur
    $_SESSION['message'] = $e->getMessage();
    $_SESSION['message_type'] = 'error';
    
    header('Location: index.php');
    exit();
}