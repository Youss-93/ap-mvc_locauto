<?php 
class Voiture {
    private $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }

    public function ajouter($data) {
        try {
            // Assurer que le chemin de l'image est correct
            if (!empty($data['image_loc'])) {
                if (strpos($data['image_loc'], 'assets/photos/voitures/') === false) {
                    $data['image_loc'] = 'assets/photos/voitures/' . basename($data['image_loc']);
                }
            }

            $sql = "INSERT INTO voiture (id_admin, modele, marque, année, prix_jour, caution, disponibilité, image_loc) 
                    VALUES (:id_admin, :modele, :marque, :annee, :prix_jour, :caution, :disponibilite, :image_loc)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id_admin' => $data['id_admin'],
                ':modele' => $data['modele'],
                ':marque' => $data['marque'],
                ':annee' => $data['annee'],
                ':prix_jour' => $data['prix_jour'],
                ':caution' => $data['caution'],
                ':disponibilite' => $data['disponibilite'] ?? true,
                ':image_loc' => $data['image_loc']
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT * FROM voiture WHERE id_voiture = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function getListe () {
        $sql = "SELECT * FROM voiture ORDER BY id_voiture ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getListeDispo () {
        $sql = "SELECT * FROM voiture WHERE disponibilité = TRUE";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getListeNonDispo () {
        $sql = "SELECT * FROM voiture WHERE disponibilité = FALSE";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getVoiture($id) {
        $sql = "SELECT * FROM Voiture WHERE id_voiture = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getVoituresDisponibles() {
        $sql = "SELECT * FROM Voiture WHERE disponibilité = TRUE ORDER BY id_voiture ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

     
    
    
    public function modifier($id_voiture, $data) {
        try {
            $sql = "UPDATE voiture SET 
                    marque = :marque,
                    modele = :modele,
                    année = :annee,
                    prix_jour = :prix_jour,
                    caution = :caution,
                    disponibilité = :disponibilite,
                    image_loc = :image_loc
                    WHERE id_voiture = :id_voiture";
    
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':marque' => $data['marque'],
                ':modele' => $data['modele'],
                ':annee' => $data['annee'],
                ':prix_jour' => $data['prix_jour'],
                ':caution' => $data['caution'],
                ':disponibilite' => $data['disponibilite'],
                ':image_loc' => $data['image_loc'],
                ':id_voiture' => $id_voiture
            ]);
            
        } catch (PDOException $e) {
            error_log("Erreur modification voiture : " . $e->getMessage());
            return false;
        }
    }

    public function modifierDisponibilite($id, $disponibilite) {
        $sql = "UPDATE Voiture SET disponibilité = :disponibilité WHERE id_voiture = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':disponibilite' => $disponibilite
        ]);
    }
    
    public function supprimer($id) {
        try {
            $this->db->beginTransaction();
    
            // 1. Vérifier si la voiture existe
            $voiture = $this->getById($id);
            if (!$voiture) {
                throw new Exception("Voiture non trouvée");
            }
    
            // 2. Vérifier si la voiture a des réservations en cours
            $sql = "SELECT COUNT(*) FROM reservation WHERE voiture_resa = :id AND statut_reservation != 'annulée'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $hasReservations = $stmt->fetchColumn() > 0;
    
            if ($hasReservations) {
                throw new Exception("Impossible de supprimer une voiture avec des réservations en cours");
            }
    
            // 3. Supprimer l'image associée si elle existe
            if (!empty($voiture['image_loc']) && file_exists($voiture['image_loc'])) {
                unlink($voiture['image_loc']);
            }
    
            // 4. Supprimer la voiture
            $sql = "DELETE FROM voiture WHERE id_voiture = :id";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([':id' => $id]);
    
            if ($success) {
                $this->db->commit();
                return true;
            }
    
            $this->db->rollBack();
            return false;
    
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur suppression voiture : " . $e->getMessage());
            throw $e;
        }
    }

    public function getImage($id) {
        $sql = "SELECT image_loc FROM voiture WHERE id_voiture = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $image = $stmt->fetchColumn();
        
        // Vérifier si l'image existe
        if ($image && file_exists("assets/photos/voitures/" . $image)) {
            return "assets/photos/voitures/" . $image;
        }
        return "assets/photos/voitures/default.jpg";
    }

   


}