<?php
require_once '../modele/Database.php';
require_once '../modele/Voiture.php';

$voiture = new Voiture();

// Dossier source contenant vos images
$source_dir = "./assets/photos/voitures/";
// Vérifier si le dossier source existe
if (!is_dir($source_dir)) {
    die("Le dossier source n'existe pas.");
}
// Vérifier si le dossier source est accessible
if (!is_readable($source_dir)) {
    die("Le dossier source n'est pas accessible.");
}
$images = glob($source_dir . "*.{jpg,jpeg,png}", GLOB_BRACE);

foreach ($images as $image) {
    // Créer les données de la voiture
    $data = [
        'marque' => 'À définir',
        'modele' => basename($image, '.jpg'), // Utilise le nom du fichier comme modèle
        'annee' => date('Y'),
        'prix_jour' => 0,
        'caution' => 0,
        'disponibilite' => true
    ];
    
    // Ajouter la voiture avec son image
    if ($voiture->ajouterAvecImage($data, $image)) {
        echo "Image importée avec succès : " . basename($image) . "\n";
    } else {
        echo "Erreur lors de l'import de : " . basename($image) . "\n";
    }
}