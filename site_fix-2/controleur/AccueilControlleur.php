<?php
class AccueilControlleur {
    private $voitureModel;
    private $reservationModel;
    
    public function __construct() {
        require_once 'modele/Voiture.php';
        require_once 'modele/Reservation.php';
        $this->voitureModel = new Voiture();
        $this->reservationModel = new Reservation();
    }
    
    public function index() {
        // Récupérer les voitures disponibles uniquement
        $voitures = $this->voitureModel->getVoituresDisponibles();
        
        // Pour les utilisateurs connectés, récupérer leurs réservations actives
        $reservations_actives = [];
        if (isset($_SESSION['utilisateur'])) { // Changed from 'email' to 'utilisateur'
            $reservations_actives = $this->reservationModel->getReservationsActives($_SESSION['id_utilisateur']);
        }
        
        // Récupérer les messages
        $message = $_SESSION['message'] ?? null;
        $message_type = $_SESSION['message_type'] ?? 'info';
        unset($_SESSION['message'], $_SESSION['message_type']);
        
        // Définir le titre de la page
        $titre = "Accueil - Location de voitures";
        
        require_once 'vue/accueil.php';
    }
}