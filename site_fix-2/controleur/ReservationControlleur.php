<?php
class ReservationControlleur {
    private $reservationModel;
    private $voitureModel;

    public function __construct() {
        require_once 'modele/Reservation.php';
        require_once 'modele/Voiture.php';
        $this->reservationModel = new Reservation();
        $this->voitureModel = new Voiture();
    }

    public function creer() {
        if (!isset($_SESSION['utilisateur'])) {
            $_SESSION['message']      = "Vous devez être connecté pour réserver";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }

        if ($_SESSION['role'] !== 'client') {
            $_SESSION['message']      = "Seuls les clients peuvent faire une réservation";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }

        $id_voiture = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id_voiture) {
            $_SESSION['message']      = "Voiture introuvable";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }

        $voiture = $this->voitureModel->getById($id_voiture);
        if (!$voiture) {
            $_SESSION['message']      = "Voiture introuvable";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }

        // POST : soumettre le formulaire de dates → créer la réservation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $date_debut = $_POST['date_debut'] ?? '';
                $date_fin   = $_POST['date_fin']   ?? '';

                if (empty($date_debut) || empty($date_fin)) {
                    throw new Exception('Les dates de début et de fin sont obligatoires');
                }
                if ($date_fin <= $date_debut) {
                    throw new Exception('La date de fin doit être après la date de début');
                }

                if ($this->reservationModel->verifierChevauchement($id_voiture, $date_debut, $date_fin)) {
                    throw new Exception("Cette voiture n'est pas disponible pour ces dates");
                }

                $data = [
                    'date_debut'          => $date_debut,
                    'date_fin'            => $date_fin,
                    'id_client'           => $_SESSION['id_utilisateur'],
                    'voiture_resa'        => $id_voiture,
                    'statut_reservation'  => 'en attente'
                ];

                if (!$this->reservationModel->creer($data)) {
                    throw new Exception("Erreur lors de la création de la réservation");
                }

                $_SESSION['message']      = "Réservation créée ! Vous pouvez maintenant procéder au paiement.";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?controller=reservation&action=liste');
                exit();

            } catch (Exception $e) {
                $_SESSION['message']      = $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        }

        // GET : afficher le formulaire de dates
        require_once 'vue/resa.cree.php';
    }
    public function mesReservations() {
        if (!isset($_SESSION['utilisateur'])) {
            $_SESSION['message'] = "Veuillez vous connecter";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        $reservations = $this->reservationModel->getReservationsClient($_SESSION['id_utilisateur']);
        require_once 'vue/mes_reservations.php';
    }

    public function confirmer() {
        if (!isset($_SESSION['utilisateur'])) {
            $_SESSION['message'] = "Veuillez vous connecter";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }

        // L'admin confirme via un lien GET (?action=confirmer&id=...)
        if ($_SESSION['role'] === 'admin') {
            $id_reservation = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if (!$id_reservation) {
                $_SESSION['message'] = "ID de réservation invalide";
                $_SESSION['message_type'] = "danger";
                header('Location: index.php?controller=reservation&action=liste');
                exit();
            }

            if ($this->reservationModel->updateStatut($id_reservation, 'confirmée')) {
                $_SESSION['message'] = "Réservation confirmée avec succès";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de la confirmation";
                $_SESSION['message_type'] = "danger";
            }

            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }

        // Client : confirmation via POST (paiement)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id_reservation = filter_input(INPUT_POST, 'id_reservation', FILTER_VALIDATE_INT);
                $reference      = filter_input(INPUT_POST, 'reference',      FILTER_SANITIZE_STRING);
                $montant        = filter_input(INPUT_POST, 'montant',        FILTER_VALIDATE_FLOAT);
                $mode_paiement  = filter_input(INPUT_POST, 'mode_paiement', FILTER_SANITIZE_STRING);

                if (!$id_reservation || !$reference || !$montant || !$mode_paiement) {
                    throw new Exception("Données de paiement invalides");
                }

                if ($this->reservationModel->finaliserReservation($id_reservation, $reference, $montant, $mode_paiement)) {
                    unset($_SESSION['paiement_temp']);
                    $_SESSION['message']      = "Paiement confirmé et réservation finalisée";
                    $_SESSION['message_type'] = "success";
                } else {
                    throw new Exception("Erreur lors de la finalisation de la réservation");
                }

                header('Location: index.php?controller=reservation&action=liste');
                exit();

            } catch (Exception $e) {
                $_SESSION['message']      = $e->getMessage();
                $_SESSION['message_type'] = "danger";
                header('Location: index.php?controller=reservation&action=liste');
                exit();
            }
        }
    }
    


    public function annuler() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }

        $id_reservation = $_GET['id'] ?? null;
        if (!$id_reservation) {
            $_SESSION['message']      = 'ID de réservation manquant';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }

        // L'admin peut annuler n'importe quelle réservation sans vérification client
        if ($_SESSION['role'] === 'admin') {
            $success = $this->reservationModel->annulerReservation($id_reservation);
        } else {
            // Le client ne peut annuler que ses propres réservations
            $success = $this->reservationModel->annuler($id_reservation, $_SESSION['id_utilisateur']);
        }

        if ($success) {
            $_SESSION['message']      = 'Réservation annulée avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message']      = "Erreur lors de l'annulation de la réservation";
            $_SESSION['message_type'] = 'danger';
        }

        header('Location: index.php?controller=reservation&action=liste');
        exit();
    }
    
    public function supprimer() {
        if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['message'] = 'Action non autorisée';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php');
            exit();
        }
    
        $id_reservation = $_GET['id'] ?? null;
        if (!$id_reservation) {
            $_SESSION['message'] = 'ID de réservation manquant';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        if ($this->reservationModel->supprimer($id_reservation)) {
            $_SESSION['message'] = 'Réservation supprimée avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de la suppression de la réservation';
            $_SESSION['message_type'] = 'error';
        }
    
        header('Location: index.php?controller=reservation&action=liste');
        exit();
    }

    public function detail() {
        // Vérification de l'authentification
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        // Récupération et validation de l'ID
        $id_reservation = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id_reservation) {
            $_SESSION['message'] = 'ID de réservation invalide';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        // Récupération des détails de la réservation
        $reservation = $this->reservationModel->getDetailReservation($id_reservation);
        
        if (!$reservation) {
            $_SESSION['message'] = 'Réservation non trouvée';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        // Vérification des permissions
        if ($_SESSION['role'] !== 'admin' && $reservation['client_resa'] !== $_SESSION['id_utilisateur']) {
            $_SESSION['message'] = 'Accès non autorisé';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        // Affichage de la vue
        require_once 'vue/resa.detail.php';
    }

    public function liste() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        // Différent affichage selon le rôle
        if ($_SESSION['role'] === 'admin') {
            // Pour les admins : réservations des voitures dont ils sont propriétaires
            $reservations = $this->reservationModel->getReservationsAdmin($_SESSION['id_utilisateur']);
            require_once 'vue/resa.admin.php';
        } elseif ($_SESSION['role'] === 'client') {
            // Pour les clients : leurs propres réservations
            $reservations = $this->reservationModel->getReservationsClient($_SESSION['id_utilisateur']);
            require_once 'vue/resa.client.php';
        }
    }

    public function finaliser() {
        // Vérification pour client OU admin
        if (!isset($_SESSION['utilisateur']) || ($_SESSION['role'] !== 'client' && $_SESSION['role'] !== 'admin')) {
            $_SESSION['message'] = "Action non autorisée";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = "Méthode non autorisée";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    
        try {
            $id_reservation = filter_input(INPUT_POST, 'id_reservation', FILTER_VALIDATE_INT);
            $reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_STRING);
            $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
            $mode_paiement = filter_input(INPUT_POST, 'mode_paiement', FILTER_SANITIZE_STRING);
    
            if (!$id_reservation || !$reference || !$montant || !$mode_paiement) {
                throw new Exception("Données de paiement invalides");
            }
    
            if ($this->reservationModel->finaliserReservation($id_reservation, $reference, $montant, $mode_paiement)) {
                unset($_SESSION['paiement_temp']);
                $_SESSION['message'] = "Paiement confirmé et réservation finalisée";
                $_SESSION['message_type'] = "success";
            } else {
                throw new Exception("Erreur lors de la finalisation de la réservation");
            }
    
            header('Location: index.php?controller=reservation&action=liste');
            exit();
    
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=reservation&action=liste');
            exit();
        }
    }

}
