<?php
/**
 * Script à exécuter UNE FOIS sur votre serveur MAMP pour corriger les hashes.
 * Accédez à : http://localhost:8888/site_corrige/generer_hashes.php
 * 
 * Mot de passe : Client123! pour tous les comptes clients de test (id 20 à 35)
 */

require_once 'config.php';
require_once 'modele/Database.php';

$db = Database::getConnection();
$mdp = 'Client123!';

$ids = [20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35];

$ok = 0;
foreach ($ids as $id) {
    $hash = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $db->prepare("UPDATE utilisateur SET mdp_utilisateur = :hash WHERE id_utilisateur = :id");
    if ($stmt->execute([':hash' => $hash, ':id' => $id])) {
        $ok++;
        echo "✅ Utilisateur $id mis à jour<br>";
    } else {
        echo "❌ Erreur pour l'utilisateur $id<br>";
    }
}

echo "<br><strong>$ok/" . count($ids) . " mots de passe corrigés.</strong><br>";
echo "<br>Mot de passe pour tous ces comptes : <strong>Client123!</strong><br>";
echo "<br><em>Supprimez ce fichier après utilisation.</em>";
