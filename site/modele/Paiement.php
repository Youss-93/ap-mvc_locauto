<?php
Class Paiement {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // creer un nouveau paiment 
    public function creer($id_reservation, $montant, $mode_paiement){
        $sql = "INSERT INTO Paiement (paiement_resa, montant, mode_paiement, statut_paiement, date_paiement) 
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTousLesPaiements() {
        $sql = "SELECT p.*, r.date_debut, r.date_fin, v.modele, v.marque 
                FROM Paiement p 
                JOIN Reservation r ON p.paiement_resa = r.id_reservation 
                JOIN Voiture v ON r.voiture_resa = v.id_voiture 
                ORDER BY p.date_paiement DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Statistiques - Total des paiements validés
    public function getTotalPaiementsValides() {
        $sql = "SELECT SUM(montant) as total 
                FROM Paiement 
                WHERE statut_paiement = 'validée'";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    // Vérifier si un paiement existe pour une réservation
    public function existePourReservation($id_reservation) {
        $sql = "SELECT COUNT(*) as count 
                FROM Paiement 
                WHERE paiement_resa = :reservation";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':reservation' => $id_reservation]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }
}