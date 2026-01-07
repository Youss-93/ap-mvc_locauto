<?php
class UtilisateurControlleur {
    private $utilisateurModel;

    public function __construct() {
        require_once 'modele/Utilisateur.php';
        $this->utilisateurModel = new Utilisateur();
    }

    public function creer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Récupération des données du formulaire
                $nom = $_POST['nom'] ?? '';
                $prenom = $_POST['prenom'] ?? '';
                $email = $_POST['email'] ?? '';
                $mdp = $_POST['mdp'] ?? '';
                $tel = $_POST['telephone'] ?? '';
    
                // Debug des données reçues
                error_log('Données reçues: ' . print_r($_POST, true));
    
                // Création de l'utilisateur
                if ($this->utilisateurModel->creer($nom, $prenom, $email, $mdp, $tel)) {
                    $_SESSION['message'] = "Compte créé avec succès";
                    $_SESSION['message_type'] = "success";
                    header('Location: index.php?controller=utilisateur&action=login');
                    exit();
                } else {
                    throw new Exception("Erreur lors de la création du compte");
                }
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = "danger";
                header('Location: index.php?controller=utilisateur&action=register');
                exit();
            }
        }
        // Si ce n'est pas un POST, afficher le formulaire
        require_once 'vue/register.php';
    }

    

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $mdp = $_POST['mdp'] ?? ''; // Changé de 'password' à 'mdp'
            
            $utilisateur = $this->utilisateurModel->verifierLogin($email, $mdp);
            
            if ($utilisateur) {
                $_SESSION['utilisateur'] = $utilisateur['email'];
                $_SESSION['nom'] = $utilisateur['nom_utilisateur'];
                $_SESSION['prenom'] = $utilisateur['prenom_utilisateur'];
                $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
                $_SESSION['role'] = $utilisateur['role_utilisateur'];
                $_SESSION['message'] = 'Connexion réussie';
                $_SESSION['message_type'] = 'success';
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['message'] = "Email ou mot de passe incorrect";
                $_SESSION['message_type'] = "danger";
            }
        }
        require_once 'vue/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Récupération simple des données
                $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
                $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
                $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : ''; // Pas de trim() pour le mot de passe
                $tel = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
    
                // Validation des champs vides
                if (empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($tel)) {
                    throw new Exception("Tous les champs sont obligatoires");
                }
    
                // Création directe de l'utilisateur
                if ($this->utilisateurModel->creer($nom, $prenom, $email, $mdp, $tel)) {
                    $_SESSION['message'] = "Compte créé avec succès, veuillez vous connecter";
                    $_SESSION['message_type'] = "success";
                    header('Location: index.php?controller=utilisateur&action=login');
                    exit();
                } else {
                    throw new Exception("Erreur lors de la création du compte");
                }
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        }
        require_once 'vue/register.php';
    }
    

    

public function emailExists($email) {
    $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':email' => $email]);
    return $stmt->fetchColumn() > 0;
}

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit();
    }

    public function profil() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        // Récupérer les informations de l'utilisateur
        $utilisateur = $this->utilisateurModel->getUtilisateur($_SESSION['id_utilisateur']);
    
        // Récupérer les réservations de l'utilisateur
        require_once 'modele/Reservation.php';
        $reservationModel = new Reservation();
        $reservations = $reservationModel->getReservationsUtilisateur($_SESSION['id_utilisateur']);
    
        require_once 'vue/profil.php';
    }

    public function modifier() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_STRING);
    
            if ($this->utilisateurModel->modifier($_SESSION['id_utilisateur'], $nom, $prenom, $email, $tel)) {
                $_SESSION['message'] = 'Profil mis à jour avec succès';
                $_SESSION['message_type'] = 'success';
                header('Location: index.php?controller=utilisateur&action=profil');
                exit();
            } else {
                $_SESSION['message'] = 'Erreur lors de la mise à jour du profil';
                $_SESSION['message_type'] = 'error';
            }
           
        }
    
        // Récupérer les informations de l'utilisateur
        $utilisateur = $this->utilisateurModel->getUtilisateur($_SESSION['id_utilisateur']);
    
        require_once 'vue/profil.modifier.php';
    }

    public function supprimer() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_utilisateur = $_SESSION['id_utilisateur'];
            if ($this->utilisateurModel->supprimer($id_utilisateur)) {
                session_destroy();
                $_SESSION['message'] = 'Compte supprimé avec succès';
                $_SESSION['message_type'] = 'success';
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['message'] = 'Erreur lors de la suppression du compte';
                $_SESSION['message_type'] = 'error';
            }
        }
    
        require_once 'vue/profil.supprimer.php';
    }

    
}
