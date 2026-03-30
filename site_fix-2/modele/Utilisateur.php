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
        $sql = "DELETE FROM utilisateur WHERE id_utilisateur=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id'=>$id]);
    }
}
