<?php
session_start();

require_once 'config.php';
require_once 'modele/Database.php';

$controller = $_GET['controller'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

$controller = ucfirst(strtolower($controller)) . 'Controlleur';
$controllerFile = "controleur/$controller.php";

try {
    if (!file_exists($controllerFile)) {
        throw new Exception("Le contrôleur demandé n'existe pas");
    }

    require_once $controllerFile;

    if (!class_exists($controller)) {
        throw new Exception("La classe du contrôleur n'existe pas");
    }

    $controllerInstance = new $controller();

    if (!method_exists($controllerInstance, $action)) {
        throw new Exception("L'action demandée n'existe pas");
    }

    $controllerInstance->$action();

} catch (Exception $e) {
    $_SESSION['message'] = $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit();
}
