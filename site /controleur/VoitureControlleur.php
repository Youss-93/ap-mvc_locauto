<?php
class VoitureControlleur {
    private $voitureModel;

    public function __construct() {
        require_once 'modele/Voiture.php';
        $this->voitureModel = new Voiture();
    }

    public function liste() {
        $voitures = $this->voitureModel->getListe();
        require_once 'vue/voiture.liste.php';
    }

    public function detail() {
        // Vérifier si l'id existe
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['message'] = 'Identifiant de voiture manquant';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }

        // Récupérer la voiture
        $voiture = $this->voitureModel->getVoiture($id);
        
        // Vérifier si la voiture existe
        if (!$voiture) {
            $_SESSION['message'] = 'Voiture non trouvée';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }

        // Afficher la vue
        require_once 'vue/voiture.detail.php';
    }

    public function supprimer() {
        if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['message'] = "Action non autorisée";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
    
        $id_voiture = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_voiture) {
            $_SESSION['message'] = "ID de voiture invalide";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
    
        try {
            if ($this->voitureModel->supprimer($id_voiture)) {
                $_SESSION['message'] = "Voiture supprimée avec succès";
                $_SESSION['message_type'] = "success";
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    
        header('Location: index.php?controller=voiture&action=liste');
        exit();
    }
    // Vérifie si l'utilisateur est un administrateur

    private function verifierAdmin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['message'] = 'Accès non autorisé';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php');
            exit();
        }
    }

    public function modifier() {
        if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['message'] = "Action non autorisée";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
    
        $id_voiture = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_voiture) {
            $_SESSION['message'] = "ID de voiture invalide";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
    
        // Initialisation des données
        $data = [];
        
        // Récupération de la voiture existante
        $voiture = $this->voitureModel->getById($id_voiture);
        if (!$voiture) {
            $_SESSION['message'] = "Voiture non trouvée";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Gérer la disponibilité séparément
                $disponibilite = isset($_POST['disponibilite']) ? 1 : 0;
    
                $data = [
                    'id_voiture' => $id_voiture,
                    'marque' => filter_input(INPUT_POST, 'marque', FILTER_SANITIZE_SPECIAL_CHARS),
                    'modele' => filter_input(INPUT_POST, 'modele', FILTER_SANITIZE_SPECIAL_CHARS),
                    'annee' => filter_input(INPUT_POST, 'annee', FILTER_VALIDATE_INT),
                    'prix_jour' => filter_input(INPUT_POST, 'prix_jour', FILTER_VALIDATE_FLOAT),
                    'caution' => filter_input(INPUT_POST, 'caution', FILTER_VALIDATE_FLOAT),
                    'disponibilite' => $disponibilite,
                    'image_loc' => $voiture['image_loc']
                ];
    
                // Image handling if provided
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploaded_image = $this->uploadImage($_FILES['image']);
                    if ($uploaded_image) {
                        $data['image_loc'] = $uploaded_image;
                    }
                }
    
                if ($this->voitureModel->modifier($id_voiture, $data)) {
                    $_SESSION['message'] = "Voiture modifiée avec succès";
                    $_SESSION['message_type'] = "success";
                    header('Location: index.php?controller=voiture&action=liste');
                    exit();
                } else {
                    throw new Exception("Erreur lors de la modification");
                }
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = "danger";
                if (!empty($data)) {
                    $voiture = array_merge($voiture, $data);
                }
            }
        }
    
        require_once 'vue/voiture.modifier.php';
    }
         


    public function ajouter() {
        $this->verifierAdmin();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Récupérer et valider les données du formulaire
                $marque = htmlspecialchars($_POST['marque'] ?? '', ENT_QUOTES, 'UTF-8');
                $modele = htmlspecialchars($_POST['modele'] ?? '', ENT_QUOTES, 'UTF-8');
                $annee = filter_input(INPUT_POST, 'annee', FILTER_VALIDATE_INT);
                $prix_jour = filter_input(INPUT_POST, 'prix_jour', FILTER_VALIDATE_FLOAT);
                $caution = filter_input(INPUT_POST, 'caution', FILTER_VALIDATE_FLOAT);
    
                // Valider les champs requis
                if (empty($marque) || empty($modele) || !$annee || !$prix_jour || !$caution) {
                    throw new Exception('Tous les champs sont obligatoires');
                }
    
                
                $image_loc = '';
                if (isset($_FILES['image_loc']) && $_FILES['image_loc']['error'] === 0) {
                    $image_loc = $this->uploadImage($_FILES['image_loc']);
                    if (!$image_loc) {
                        throw new Exception('Erreur lors du téléchargement de l\'image');
                    }
                }
    
                // Créer la voiture
                $success = $this->voitureModel->ajouter([
                    'marque' => $marque,
                    'modele' => $modele,
                    'annee' => $annee,          // Change 'année'
                    'prix_jour' => $prix_jour,
                    'caution' => $caution,
                    'disponibilite' => true,     // Change 'disponibilité'
                    'image_loc' => $image_loc
                ]);
    
                if (!$success) {
                    throw new Exception('Erreur lors de l\'ajout dans la base de données');
                }
    
                $_SESSION['message'] = 'Voiture ajoutée avec succès';
                $_SESSION['message_type'] = 'success';
                header('Location: index.php?controller=voiture&action=liste');
                exit();
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                $voiture = [
                    'marque' => $marque ?? '',
                    'modele' => $modele ?? '',
                    'annee' => $annee ?? '',     // Changed 'année'
                    'prix_jour' => $prix_jour ?? '',
                    'caution' => $caution ?? ''
                ];
                require_once 'vue/voiture.ajouter.php';
                return;
            }
        }
    
        require_once 'vue/voiture.ajouter.php';
    }
    
    
    public function rechercher() {
        $marque = $_GET['marque'] ?? '';
        $modele = $_GET['modele'] ?? '';
        $annee = $_GET['année'] ?? '';
        $prix_jour = $_GET['prix_jour'] ?? '';

        $voitures = $this->voitureModel->rechercher($marque, $modele, $annee, $prix_jour);
        require_once 'vue/voiture.liste.php';
    }
    public function reserver() {
        $this->verifierUtilisateur();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_voiture = $_POST['id_voiture'] ?? null;
            $date_debut = $_POST['date_debut'] ?? null;
            $date_fin = $_POST['date_fin'] ?? null;
            $prix_total = $_POST['prix_total'] ?? null;
            $id_client = $_SESSION['id_utilisateur'] ?? null;
            $statut = 'en attente'; 
            $id_reservation = $this->reservationModel->creer($id_client, $id_voiture, $date_debut, $date_fin, $prix_total, $statut);
            if ($id_reservation) {
                $_SESSION['message'] = 'Réservation créée avec succès';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Erreur lors de la création de la réservation';
                $_SESSION['message_type'] = 'danger';
            }
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['message'] = 'Identifiant de voiture manquant';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
        $voiture = $this->voitureModel->getVoiture($id);
        if (!$voiture) {
            $_SESSION['message'] = 'Voiture non trouvée';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?controller=voiture&action=liste');
            exit();
        }
        require_once 'vue/voiture.reserver.php';
    }
  
public function uploadImage($file) {
    try {
        // Définir le chemin de destination
        $target_dir = "assets/photos/";
        
        // Vérifier si le dossier existe, sinon le créer
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Extraire l'extension du fichier d'origine
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Créer un nom unique pour l'image
        $newFileName = uniqid('voiture_') . '.' . $extension;
        $target_file = $target_dir . $newFileName;

        // Copier le fichier vers le dossier de destination
        if (copy($file['tmp_name'], $target_file)) {
            return $target_file;
        }

        throw new Exception("Erreur lors de la copie du fichier.");
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

public function ajouterAvecImage($data, $image_path) {
    try {
        // Copier l'image depuis le dossier source
        $target_dir = "assets/photos/";
        $filename = basename($image_path);
        $new_path = $target_dir . $filename;
        
        // Créer le dossier si nécessaire
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Copier le fichier
        if (copy($image_path, $new_path)) {
            $data['image_loc'] = $new_path;
            return $this->ajouter($data);
        }
        
        return false;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}


}