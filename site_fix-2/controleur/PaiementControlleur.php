<?php
class PaiementControlleur {
    private $reservationModel;
    private $paiementModel;

    public function __construct() {
        require_once 'modele/Reservation.php';
        require_once 'modele/Paiement.php';
        $this->reservationModel = new Reservation();
        $this->paiementModel = new Paiement();
    }
// Effectuer le paiement
    public function effectuer() {
        if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'client') {
            $_SESSION['message']      = "Seuls les clients peuvent effectuer des paiements.";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }

        $id_reservation = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id_reservation) {
            $_SESSION['message']      = "ID de réservation invalide";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }

        $reservation = $this->reservationModel->getDetailReservation($id_reservation);

        if (!$reservation) {
            $_SESSION['message']      = "Réservation non trouvée";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }

        // Vérifier que la réservation appartient au client connecté
        if ($reservation['client_resa'] != $_SESSION['id_utilisateur']) {
            $_SESSION['message']      = "Accès non autorisé";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }

        // Afficher le formulaire de paiement avec les détails de la réservation
        require_once 'vue/paiement.form.php';
    }



// Effectuer le paiement
    public function confirmation() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
// Vérifier si le paiement a été effectué
        $id_paiement = $_GET['id'] ?? null;
        $paiement = $this->paiementModel->getPaiement($id_paiement);
        
        if (!$paiement) {
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
// Vérifier que le paiement appartient à l'utilisateur
        $reservation = $this->reservationModel->getReservation($paiement['paiement_resa']);
        require_once 'vue/paiement.confirmation.php';
    }

    public function historique() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }

        $paiements = $this->paiementModel->getPaiementsUtilisateur($_SESSION['id_utilisateur']);
        require_once 'vue/paiement.historique.php';
    }

    public function annuler() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }

        $id_paiement = $_GET['id'] ?? null;
        if ($id_paiement) {
            $paiement = $this->paiementModel->getPaiement($id_paiement);
            if ($paiement) {
                $this->paiementModel->updateStatut($id_paiement, 'annulé');
                $this->paiementModel->updateStatut($paiement['paiement_resa'], 'annulée');
                $_SESSION['message'] = 'Paiement annulé avec succès';
                $_SESSION['message_type'] = 'success';
            }
        }

        header('Location: index.php?controller=paiement&action=historique');
        exit();
    }

    private function verifierAdmin() {
        if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php');
            exit();
        }
    }
    public function liste() {
        $this->verifierAdmin();
        $paiements = $this->paiementModel->getTousLesPaiements();
        require_once 'vue/paiement.liste.php';
    }

    public function voir() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        $id_reservation = $_GET['id'] ?? null;
        if (!$id_reservation) {
            $_SESSION['message'] = 'Réservation non trouvée';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        // Récupérer les détails du paiement
        $paiement = $this->paiementModel->getPaiementParReservation($id_reservation);
        if (!$paiement) {
            $_SESSION['message'] = 'Paiement non trouvé';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        // Récupérer les détails de la réservation
        $reservation = $this->reservationModel->getReservation($id_reservation);
        if (!$reservation || $reservation['client_resa'] != $_SESSION['id_utilisateur']) {
            $_SESSION['message'] = 'Accès non autorisé';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        // Afficher la vue
        require_once 'vue/paiement.detail.php';
    }
}