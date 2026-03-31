<?php 
class Utilisateur {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function creer($nom, $prenom, $email, $mdp, $tel, $role = 'client') {
        try {
            if ($this->emailExists($email)) return false;
            $mdp_hash = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
            $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email, mdp_utilisateur, num_tel, role_utilisateur)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nom, $prenom, $email, $mdp_hash, $tel, $role]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function verifierLogin($email, $mdp) {
        try {
            $sql = "SELECT * FROM utilisateur WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($utilisateur && password_verify($mdp, $utilisateur['mdp_utilisateur'])) {
                return $utilisateur;
            }
            return null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getUtilisateur($id) {
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUtilisateurByEmail($email) {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUtilisateurs() {
        $sql = "SELECT * FROM utilisateur";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllClients() {
        $sql = "SELECT * FROM utilisateur WHERE role_utilisateur = 'client'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifier($id, $nom, $prenom, $email, $tel) {
        $sql = "UPDATE utilisateur SET nom_utilisateur=:nom, prenom_utilisateur=:prenom, email=:email, num_tel=:tel WHERE id_utilisateur=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id'=>$id, ':nom'=>$nom, ':prenom'=>$prenom, ':email'=>$email, ':tel'=>$tel]);
    }

    public function modifierMotDePasse($id, $nouveau_mdp) {
        $sql = "UPDATE utilisateur SET mdp_utilisateur=:mdp WHERE id_utilisateur=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id'=>$id, ':mdp'=>password_hash($nouveau_mdp, PASSWORD_BCRYPT, ['cost'=>12])]);
    }

    public function supprimer($id) {
        try {
            $this->db->beginTransaction();

            // Conserver les voitures impactées avant suppression (les réservations client seront supprimées en cascade)
            $sqlVoituresImpactees = "SELECT DISTINCT voiture_resa
                                    FROM reservation
                                    WHERE client_resa = :id
                                    AND statut_reservation IN ('en attente', 'confirmée')";
            $stmtVoitures = $this->db->prepare($sqlVoituresImpactees);
            $stmtVoitures->execute([':id' => $id]);
            $voituresImpactees = $stmtVoitures->fetchAll(PDO::FETCH_COLUMN);

            $sql = "DELETE FROM utilisateur WHERE id_utilisateur = :id";
            $stmt = $this->db->prepare($sql);
            $deleted = $stmt->execute([':id' => $id]);

            if (!$deleted) {
                $this->db->rollBack();
                return false;
            }

            if (!empty($voituresImpactees)) {
                $ids = array_map('intval', $voituresImpactees);
                $placeholders = implode(',', array_fill(0, count($ids), '?'));

                // Recalculer la disponibilité réelle selon les réservations restantes
                $sqlDisponibilite = "UPDATE voiture v
                                    SET v.disponibilité = CASE
                                        WHEN EXISTS (
                                            SELECT 1
                                            FROM reservation r
                                            WHERE r.voiture_resa = v.id_voiture
                                            AND r.statut_reservation IN ('en attente', 'confirmée')
                                            AND r.date_fin >= CURRENT_DATE()
                                        ) THEN 0
                                        ELSE 1
                                    END
                                    WHERE v.id_voiture IN ($placeholders)";
                $stmtDisponibilite = $this->db->prepare($sqlDisponibilite);
                $stmtDisponibilite->execute($ids);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log($e->getMessage());
            return false;
        }
    }
}
