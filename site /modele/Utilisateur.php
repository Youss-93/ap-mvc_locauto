<?php 
class Utilisateur{
    private $db;

    public function __construct(){
        $this->db = Database::getConnection();

    }

    public function creer($nom, $prenom, $email, $mdp, $tel, $role = 'client') {
        try {
            $sql = "INSERT INTO utilisateur (
                nom_utilisateur, 
                prenom_utilisateur, 
                email, 
                mdp_utilisateur, 
                num_tel, 
                role_utilisateur
            ) VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                $nom,
                $prenom,
                $email,
                $mdp, // Mot de passe non hashé
                $tel,
                $role
            ]);
    
            if (!$success) {
                error_log('Erreur SQL lors de la création: ' . implode(', ', $stmt->errorInfo()));
                return false;
            }
    
            return true;
    
        } catch (PDOException $e) {
            error_log('Erreur PDO lors de la création: ' . $e->getMessage());
            return false;
        }
    }
    

    public function getAllUtilisateurs() {
        $sql = "SELECT * FROM utilisateur";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllClients() {
        $sql = "SELECT * FROM utilisateur WHERE role_utilisateur = 'client'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllNouveauxClients() {
        $sql = "SELECT * FROM utilisateur WHERE role_utilisateur = 'client' AND date_inscription >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function verifierLogin($email, $mdp) {
        try {
            $sql = "SELECT * FROM utilisateur WHERE email = ? AND mdp_utilisateur = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email, $mdp]);
            
            $utilisateur = $stmt->fetch();
            
            if ($utilisateur) {
                return $utilisateur;
            }
            return false;
    
        } catch (PDOException $e) {
            error_log('Erreur lors de la vérification du login: ' . $e->getMessage());
            return false;
        }
    }

    // afin de recuperer un utilisateur par son id
    public function getUtilisateur($id){
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    // afin de recuperer un utilisateur par son email
    public function getUtilisateurByEmail($email){
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Update - Modifier les informations d'un utilisateur
    public function modifier($id, $nom, $prenom, $email, $tel) {
        $sql = "UPDATE Utilisateur 
                SET nom_utilisateur = :nom, 
                    prenom_utilisateur = :prenom, 
                    email = :email, 
                    num_tel = :tel 
                WHERE id_utilisateur = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':tel' => $tel
        ]);
    }



    // Update - Modifier le mot de passe
    public function modifierMotDePasse($id, $nouveau_mdp) {
        $sql = "UPDATE Utilisateur 
                SET mdp_utilisateur = :mdp 
                WHERE id_utilisateur = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':mdp' => password_hash($nouveau_mdp, PASSWORD_DEFAULT)
        ]);
    }

    // Delete - Supprimer un utilisateur
    public function supprimer($id) {
        $sql = "DELETE FROM Utilisateur WHERE id_utilisateur = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Vérifier si l'email existe déjà
    public function emailExiste($email) {
        $sql = "SELECT COUNT(*) as count FROM Utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch()['count'] > 0;
    }

    
}

