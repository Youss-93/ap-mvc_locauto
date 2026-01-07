<?php
Class Paiement {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // creer un nouveau paiment 
    public function creer($id_reservation, $montant, $mode_paiement){
        $sql = "INSERT INTO Paiement (reservation_paiement, montant, mode_paiement, statut_paiement, date_paiement) 
                VALUES (:reservation, :montant, :mode_paiement, 'en attente', NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':reservation' => $id_reservation,
            ':montant' => $montant,
            ':mode_paiement' => $mode_paiement
        ]);
    }
    
    // Read - Obtenir les détails d'un paiement
    public function getPaiement($id) {
        $sql = "SELECT p.*, r.date_debut, r.date_fin, v.modele, v.marque 
                FROM Paiement p 
                JOIN Reservation r ON p.paiement_resa = r.id_reservation 
                JOIN Voiture v ON r.voiture_resa = v.id_voiture 
                WHERE p.id_paiement = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Read - Liste des paiements d'un utilisateur
    public function getPaiementsUtilisateur($id_utilisateur) {
        $sql = "SELECT p.*, r.date_debut, r.date_fin, v.modele, v.marque 
                FROM Paiement p 
                JOIN Reservation r ON p.paiement_resa = r.id_reservation 
                JOIN Voiture v ON r.voiture_resa = v.id_voiture 
                WHERE r.client_resa = :id_utilisateur 
                ORDER BY p.date_paiement DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        return $stmt->fetchAll();
    }

    // Update - Mettre à jour le statut d'un paiement
    public function updateStatut($id_paiement, $nouveau_statut) {
        $sql = "UPDATE Paiement 
                SET statut_paiement = :statut 
                WHERE id_paiement = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_paiement,
            ':statut' => $nouveau_statut
        ]);
    }

    public function confirmer() {
        if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'client') {
            $_SESSION['message'] = "Action non autorisée";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_voiture = filter_input(INPUT_POST, 'id_voiture', FILTER_VALIDATE_INT);
            $date_debut = filter_input(INPUT_POST, 'date_debut', FILTER_SANITIZE_STRING);
            $date_fin = filter_input(INPUT_POST, 'date_fin', FILTER_SANITIZE_STRING);
            $mode_paiement = filter_input(INPUT_POST, 'mode_paiement', FILTER_SANITIZE_STRING);
    
            // Récupérer les informations de la voiture
            $voiture = $this->voitureModel->getById($id_voiture);
            
            // Calculer le nombre de jours et le montant total
            $debut = new DateTime($date_debut);
            $fin = new DateTime($date_fin);
            $interval = $debut->diff($fin);
            $nbJours = $interval->days + 1;
            $montantTotal = $nbJours * $voiture['prix_jour'];
    
            // Créer un tableau avec toutes les informations
            $paiement = [
                'voiture' => $voiture,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'mode_paiement' => $mode_paiement,
                'montant' => $montantTotal,
                'date_paiement' => date('Y-m-d H:i:s'),
                'reference' => uniqid('RES_')
            ];
    
            // Passer les données à la vue
            require_once 'vue/paiement.form.php';
        }
    }

     // Delete - Annuler un paiement
     public function annuler($id_paiement) {
        $sql = "UPDATE Paiement 
                SET statut_paiement = 'échoué' 
                WHERE id_paiement = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id_paiement]);
    }

    public function getPaiementParReservation($id_reservation) {
        $sql = "SELECT p.*, r.date_debut, r.date_fin, v.marque, v.modele, v.prix_jour
                FROM paiement p
                JOIN reservation r ON p.paiement_resa = r.id_reservation
                JOIN voiture v ON r.voiture_resa = v.id_voiture
                WHERE p.paiement_resa = :id_reservation";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_reservation' => $id_reservation]);
        return $stmt->fetch();
    }


    // Statistiques - Total des paiements validés
    public function getTotalPaiementsValides() {
        $sql = "SELECT SUM(montant) as total 
                FROM Paiement 
                WHERE statut_paiement = 'validée'";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['total'] ?? 0;
    }

    // Vérifier si un paiement existe pour une réservation
    public function existePourReservation($id_reservation) {
        $sql = "SELECT COUNT(*) as count 
                FROM Paiement 
                WHERE paiement_resa = :reservation";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':reservation' => $id_reservation]);
        return $stmt->fetch()['count'] > 0;
    }
}