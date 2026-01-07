<?php
class Reservation {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

   
   public function creer($data) {
    try {
        $sql = "INSERT INTO reservation (date_debut, date_fin, id_client, voiture_resa, )
                VALUES (:date_debut, :date_fin, :id_client, :voiture_resa, )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':id_client' => $data['id_client'],
            ':voiture_resa' => $data['voiture_resa'],
            ':statut_reservation' => $data['statut_reservation']
        ]);
    } catch (PDOException $e) {
        error_log("Erreur création réservation : " . $e->getMessage());
        return false;
    }
}

 
// Read
    public function getById($id) {
        $sql = "SELECT * FROM Reservation WHERE id_reservation = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function getAll() {
        $sql = "SELECT * FROM Reservation";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getReservation($id) {
        $sql = "SELECT r.*, v.marque, v.modele, v.prix_jour,
                DATEDIFF(r.date_fin, r.date_debut) * v.prix_jour as prix_total
                FROM reservation r
                JOIN voiture v ON r.voiture_resa = v.id_voiture
                WHERE r.id_reservation = :id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getReservationsUtilisateur($id_utilisateur) {
        $sql = "SELECT r.*, v.marque, v.modele, v.prix_jour,
                DATEDIFF(r.date_fin, r.date_debut) * v.prix_jour as prix_total
                FROM reservation r
                JOIN voiture v ON r.voiture_resa = v.id_voiture
                WHERE r.client_resa = :id_utilisateur
                ORDER BY r.date_debut DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        return $stmt->fetchAll();
    }

    public function getReservationsAdmin($id_admin) {
        $sql = "SELECT r.*, v.marque, v.modele, v.prix_jour, v.disponibilité,
                u.nom_utilisateur, u.prenom_utilisateur,
                DATEDIFF(r.date_fin, r.date_debut) * v.prix_jour as prix_total
                FROM reservation r
                JOIN voiture v ON r.voiture_resa = v.id_voiture
                JOIN utilisateur u ON r.client_resa = u.id_utilisateur
                WHERE v.id_admin = :id_admin
                ORDER BY r.date_debut DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_admin' => $id_admin]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getReservationsClient($id_client) {
        $sql = "SELECT r.*, v.marque, v.modele, v.prix_jour, v.image_loc,
                DATEDIFF(r.date_fin, r.date_debut) * v.prix_jour as prix_total
                FROM reservation r
                JOIN voiture v ON r.voiture_resa = v.id_voiture
                WHERE r.client_resa = :id_client
                ORDER BY r.date_debut DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_client' => $id_client]);
        return $stmt->fetchAll();
    }

    public function getDetailReservation($id_reservation) {
        try {
            $sql = "SELECT r.*, 
                    v.marque, v.modele, v.prix_jour, v.caution, v.image_loc,
                    u.nom_utilisateur, u.prenom_utilisateur, u.email, u.num_tel,
                    DATEDIFF(r.date_fin, r.date_debut) * v.prix_jour as prix_total
                    FROM reservation r
                    JOIN voiture v ON r.voiture_resa = v.id_voiture
                    JOIN utilisateur u ON r.client_resa = u.id_utilisateur
                    WHERE r.id_reservation = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id_reservation]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    public function getReservationsActives($id_utilisateur) {
        $sql = "SELECT r.*, v.marque, v.modele 
                FROM reservation r 
                JOIN voiture v ON r.voiture_resa = v.id_voiture 
                WHERE r.client_resa = :id_utilisateur 
                AND r.statut_reservation = 'confirmée'
                AND r.date_fin >= CURRENT_DATE()
                ORDER BY r.date_debut ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        return $stmt->fetchAll();
    }

    public function getReservationsFutures($id_utilisateur) {
        $sql = "SELECT r.*, v.marque, v.modele 
                FROM reservation r 
                JOIN voiture v ON r.voiture_resa = v.id_voiture 
                WHERE r.client_resa = :id_utilisateur 
                AND r.statut_reservation = 'confirmée'
                AND r.date_debut > CURRENT_DATE()
                ORDER BY r.date_debut ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        return $stmt->fetchAll();
    }

    public function getReservationsAnnulees($id_utilisateur) {
        $sql = "SELECT r.*, v.marque, v.modele 
                FROM reservation r 
                JOIN voiture v ON r.voiture_resa = v.id_voiture 
                WHERE r.client_resa = :id_utilisateur 
                AND r.statut_reservation = 'annulée'
                ORDER BY r.date_debut DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        return $stmt->fetchAll();
    }
    public function getReservationsPassées($id_utilisateur) {
        $sql = "SELECT r.*, v.marque, v.modele 
                FROM reservation r 
                JOIN voiture v ON r.voiture_resa = v.id_voiture 
                WHERE r.client_resa = :id_utilisateur 
                AND r.statut_reservation = 'confirmée'
                AND r.date_fin < CURRENT_DATE()
                ORDER BY r.date_fin DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        return $stmt->fetchAll();
    }
    public function getReservationsVoiture($id_voiture) {
        $sql = "SELECT r.*, u.nom_utilisateur, u.prenom_utilisateur 
                FROM reservation r 
                JOIN utilisateur u ON r.client_resa = u.id_utilisateur 
                WHERE r.voiture_resa = :id_voiture 
                ORDER BY r.date_debut DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_voiture' => $id_voiture]);
        return $stmt->fetchAll();
    }   

    public function getReservationsActivesVoiture($id_voiture) {
        $sql = "SELECT r.*, u.nom_utilisateur, u.prenom_utilisateur 
                FROM reservation r 
                JOIN utilisateur u ON r.client_resa = u.id_utilisateur 
                WHERE r.voiture_resa = :id_voiture 
                AND r.statut_reservation = 'confirmée'
                AND r.date_fin >= CURRENT_DATE()
                ORDER BY r.date_debut ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_voiture' => $id_voiture]);
        return $stmt->fetchAll();
    }

    public function getReservationsFuturesVoiture($id_voiture) {
        $sql = "SELECT r.*, u.nom_utilisateur, u.prenom_utilisateur 
                FROM reservation r 
                JOIN utilisateur u ON r.client_resa = u.id_utilisateur 
                WHERE r.voiture_resa = :id_voiture 
                AND r.statut_reservation = 'confirmée'
                AND r.date_debut > CURRENT_DATE()
                ORDER BY r.date_debut ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_voiture' => $id_voiture]);
        return $stmt->fetchAll();
    }

    public function getReservationsAnnuleesVoiture($id_voiture) {
        $sql = "SELECT r.*, u.nom_utilisateur, u.prenom_utilisateur 
                FROM reservation r 
                JOIN utilisateur u ON r.client_resa = u.id_utilisateur 
                WHERE r.voiture_resa = :id_voiture 
                AND r.statut_reservation = 'annulée'
                ORDER BY r.date_debut DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_voiture' => $id_voiture]);
        return $stmt->fetchAll();
    }
    public function getReservationsPasseesVoiture($id_voiture) {
        $sql = "SELECT r.*, u.nom_utilisateur, u.prenom_utilisateur 
                FROM reservation r 
                JOIN utilisateur u ON r.client_resa = u.id_utilisateur 
                WHERE r.voiture_resa = :id_voiture 
                AND r.statut_reservation = 'confirmée'
                AND r.date_fin < CURRENT_DATE()
                ORDER BY r.date_fin DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_voiture' => $id_voiture]);
        return $stmt->fetchAll();
    }

    public function confirmerReservation($id_reservation, $reference, $montant) {
        try {
            // Mise à jour simple du statut
            $sql = "UPDATE reservation 
                    SET statut_reservation = 'confirmée' 
                    WHERE id_reservation = :id_reservation";
            
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([':id_reservation' => $id_reservation])) {
                return true;
            }
            return false;
    
        } catch (PDOException $e) {
            error_log("Erreur lors de la confirmation : " . $e->getMessage());
            return false;
        }
    }
            
            

    public function annulerReservation($id_reservation) {
        try {
            $sql = "UPDATE reservation 
                    SET statut_reservation = 'annulée'
                    WHERE id_reservation = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id_reservation]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function supprimer($id_reservation) {
        $sql = "DELETE FROM Reservation 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id_reservation]);
    }
    public function supprimerReservation($id_reservation) {
        $sql = "DELETE FROM Reservation 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id_reservation]);
    }
    public function supprimerReservationParVoiture($id_voiture) {
        $sql = "DELETE FROM Reservation 
                WHERE voiture_resa = :voiture";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':voiture' => $id_voiture]);
    }
    public function supprimerReservationParClient($id_client) {
        $sql = "DELETE FROM Reservation 
                WHERE client_resa = :client";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':client' => $id_client]);
    }

    public function finaliserReservation($id_reservation, $reference, $montant) {
        try {
            $this->db->beginTransaction();
    
            // Mise à jour du statut de la réservation
            $sql_reservation = "UPDATE reservation 
                              SET statut_reservation = 'confirmée'
                              WHERE id_reservation = :id_reservation";
            
            $stmt_reservation = $this->db->prepare($sql_reservation);
            $stmt_reservation->execute([':id_reservation' => $id_reservation]);
    
            // Création de l'enregistrement de paiement
            $sql_paiement = "INSERT INTO paiement 
                            (reference, montant, date_paiement, reservation_id) 
                            VALUES 
                            (:reference, :montant, NOW(), :reservation_id)";
            
            $stmt_paiement = $this->db->prepare($sql_paiement);
            $stmt_paiement->execute([
                ':reference' => $reference,
                ':montant' => $montant,
                ':reservation_id' => $id_reservation
            ]);
    
            // Mise à jour de la disponibilité de la voiture
            $sql_update_voiture = "UPDATE voiture v 
                                 INNER JOIN reservation r ON v.id_voiture = r.voiture_resa 
                                 SET v.disponibilité = 0 
                                 WHERE r.id_reservation = :id_reservation";
            
            $stmt_voiture = $this->db->prepare($sql_update_voiture);
            $stmt_voiture->execute([':id_reservation' => $id_reservation]);
    
            // Si tout s'est bien passé, on valide la transaction
            $this->db->commit();
            return true;
    
        } catch (PDOException $e) {
            // En cas d'erreur, on annule la transaction
            $this->db->rollBack();
            error_log("Erreur lors de la finalisation de la réservation : " . $e->getMessage());
            return false;
        }
    }

    public function mesReservations() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        // Vérifier si l'utilisateur est un admin
        if ($_SESSION['role'] === 'admin') {
            $_SESSION['message'] = "Les administrateurs ne peuvent pas effectuer de réservations.";
            $_SESSION['message_type'] = 'error';
            header('Location: index.php');
            exit();
        }
    
        // Récupérer les réservations de l'utilisateur
        $reservations = $this->reservationModel->getReservationsUtilisateur($_SESSION['user_id']);
        
        // Passer les données à la vue
        require_once 'vue/resa.liste.php';
    }

    // Update
    public function modifier($id_reservation, $date_debut, $date_fin) {
        $sql = "UPDATE Reservation 
                SET date_debut = :debut, date_fin = :fin 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':debut' => $date_debut,
            ':fin' => $date_fin
        ]);
    }
    public function modifierStatut($id_reservation, $statut) {
        $sql = "UPDATE Reservation 
                SET statut_reservation = :statut 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':statut' => $statut
        ]);
    }

    public function modifierVoiture($id_reservation, $id_voiture) {
        $sql = "UPDATE Reservation 
                SET voiture_resa = :voiture 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':voiture' => $id_voiture
        ]);
    }

    public function modifierClient($id_reservation, $id_client) {
        $sql = "UPDATE Reservation 
                SET client_resa = :client 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':client' => $id_client
        ]);
    }
    public function modifierDateDebut($id_reservation, $date_debut) {
        $sql = "UPDATE Reservation 
                SET date_debut = :debut 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':debut' => $date_debut
        ]);
    }
    public function modifierDateFin($id_reservation, $date_fin) {
        $sql = "UPDATE Reservation 
                SET date_fin = :fin 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':fin' => $date_fin
        ]);
    }

    // Update - Mettre à jour le statut d'une réservation

 
    public function updateStatut($id_reservation, $nouveau_statut) {
        $sql = "UPDATE Reservation 
                SET statut_reservation = :statut 
                WHERE id_reservation = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':statut' => $nouveau_statut
        ]);
    }

   
    public function annuler($id_reservation, $id_client) {
        $sql = "UPDATE Reservation 
                SET statut_reservation = 'annulée' 
                WHERE id_reservation = :id 
                AND client_resa = :client";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id_reservation,
            ':client' => $id_client
        ]);
    }

   
    public function verifierDisponibilite($id_voiture, $date_debut, $date_fin) {
        $sql = "SELECT COUNT(*) as count 
                FROM Reservation 
                WHERE voiture_resa = :voiture 
                AND statut_reservation != 'annulée'
                AND (
                    (date_debut BETWEEN :debut AND :fin)
                    OR (date_fin BETWEEN :debut AND :fin)
                    OR (:debut BETWEEN date_debut AND date_fin)
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':voiture' => $id_voiture,
            ':debut' => $date_debut,
            ':fin' => $date_fin
        ]);
        
        return $stmt->fetch()['count'] == 0;
    }

    public function getDernierID() {
        return $this->db->lastInsertId();
    }
}