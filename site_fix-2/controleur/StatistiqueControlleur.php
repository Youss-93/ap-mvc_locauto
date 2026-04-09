<?php
class StatistiqueControlleur {
    private $categorieModel;

    public function __construct() {
        require_once 'modele/Categorie.php';
        $this->categorieModel = new Categorie();
    }

    public function index() {
        if (!isset($_SESSION['utilisateur'])) {
            $_SESSION['message'] = "Veuillez vous connecter";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            $_SESSION['message'] = "Acces reserve aux administrateurs";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php');
            exit();
        }

        $stats_categories = $this->categorieModel->getStatistiquesReservations();
        $titre = "Statistiques des categories";

        require_once 'vue/statistique.index.php';
    }
}
