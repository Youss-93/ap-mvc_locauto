<?php
class UtilisateurControlleur {
    private $utilisateurModel;

    public function __construct() {
        require_once 'modele/Utilisateur.php';
        $this->utilisateurModel = new Utilisateur();
    }

    public function login() {
        if (isset($_SESSION['utilisateur'])) {
            header('Location: index.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $mdp = $_POST['password'] ?? '';

            $utilisateur = $this->utilisateurModel->verifierLogin($email, $mdp);
            if ($utilisateur) {
                $_SESSION['utilisateur']    = $utilisateur['email'];
                $_SESSION['nom']            = $utilisateur['nom_utilisateur'];
                $_SESSION['prenom']         = $utilisateur['prenom_utilisateur'];
                $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
                $_SESSION['role']           = $utilisateur['role_utilisateur'];
                $_SESSION['message']        = 'Connexion réussie';
                $_SESSION['message_type']   = 'success';
                header('Location: index.php');
                exit();
            } else {
                $erreur = "Email ou mot de passe incorrect";
            }
        }
        require_once 'vue/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nom    = trim($_POST['nom'] ?? '');
                $prenom = trim($_POST['prenom'] ?? '');
                $email  = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $mdp    = $_POST['password'] ?? '';
                $tel    = trim($_POST['tel'] ?? '');

                if (empty($nom) || empty($prenom) || !$email || empty($mdp) || empty($tel)) {
                    throw new Exception("Tous les champs sont obligatoires");
                }
                if (strlen($mdp) < 6) {
                    throw new Exception("Le mot de passe doit contenir au moins 6 caractères");
                }
                if (!preg_match("/^[0-9]{10}$/", $tel)) {
                    throw new Exception("Le numéro de téléphone doit contenir 10 chiffres");
                }
                if ($this->utilisateurModel->emailExists($email)) {
                    throw new Exception("Cet email est déjà utilisé");
                }
                if ($this->utilisateurModel->creer($nom, $prenom, $email, $mdp, $tel, 'client')) {
                    $_SESSION['message']      = 'Compte créé avec succès';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?controller=utilisateur&action=login');
                    exit();
                } else {
                    throw new Exception("Erreur lors de la création du compte");
                }
            } catch (Exception $e) {
                $_SESSION['message']      = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
        require_once 'vue/register.php';
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
        $utilisateur = $this->utilisateurModel->getUtilisateur($_SESSION['id_utilisateur']);
        require_once 'modele/Reservation.php';
        $reservationModel = new Reservation();
        // Les admins n'ont pas de réservations personnelles
        $reservations = $_SESSION['role'] === 'client'
            ? $reservationModel->getReservationsUtilisateur($_SESSION['id_utilisateur'])
            : [];
        require_once 'vue/profil.php';
    }

    public function modifier() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom    = filter_input(INPUT_POST, 'nom',    FILTER_SANITIZE_STRING);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
            $email  = filter_input(INPUT_POST, 'email',  FILTER_SANITIZE_EMAIL);
            $tel    = filter_input(INPUT_POST, 'tel',    FILTER_SANITIZE_STRING);

            if ($this->utilisateurModel->modifier($_SESSION['id_utilisateur'], $nom, $prenom, $email, $tel)) {
                $_SESSION['utilisateur']  = $email;
                $_SESSION['message']      = 'Profil mis à jour avec succès';
                $_SESSION['message_type'] = 'success';
                header('Location: index.php?controller=utilisateur&action=profil');
                exit();
            } else {
                $_SESSION['message']      = 'Erreur lors de la mise à jour du profil';
                $_SESSION['message_type'] = 'danger';
            }
        }
        $utilisateur = $this->utilisateurModel->getUtilisateur($_SESSION['id_utilisateur']);
        require_once 'vue/profil.modifier.php';
    }

    public function supprimer() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controller=utilisateur&action=login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->utilisateurModel->supprimer($_SESSION['id_utilisateur'])) {
                session_destroy();
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['message']      = 'Erreur lors de la suppression du compte';
                $_SESSION['message_type'] = 'danger';
            }
        }
        require_once 'vue/profil.supprimer.php';
    }
}
