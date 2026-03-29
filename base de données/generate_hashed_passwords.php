<?php
/**
 * Génère les mots de passe hachés en bcrypt pour les utilisateurs
 * Usage: php generate_hashed_passwords.php
 * 
 * À exécuter une fois pour générer le fichier SQL avec les mots de passe sécurisés
 */

// Données des utilisateurs avec leurs mots de passe en plaintext
$utilisateurs = [
    ['id' => 1, 'email' => 'adminYT19@gmail.com', 'mdp' => 'Oqtf246'],
    ['id' => 2, 'email' => 'adminHY93@hotmail.fr', 'mdp' => 'HuseyinY123'],
    ['id' => 3, 'email' => 'MSAdmin@hotmail.com', 'mdp' => 'MehmetS456'],
    ['id' => 4, 'email' => 'adminSouleyGuen@hotmail.fr', 'mdp' => 'SouleymaneG789'],
    ['id' => 5, 'email' => 'admingRG21@gmail.com', 'mdp' => 'KRG9333'],
    ['id' => 11, 'email' => '313kody@hotmail.fr', 'mdp' => 'LmNo854'],
    ['id' => 12, 'email' => 'kenneth.berly@gmail.com', 'mdp' => 'AbCd567'],
    ['id' => 13, 'email' => 'dumitru.ranga@outlook.com', 'mdp' => 'DfGh890'],
    ['id' => 14, 'email' => 'dgakou@icloud.com', 'mdp' => 'JKLVR123'],
    ['id' => 15, 'email' => 'carlos.martinez@hotmail.com', 'mdp' => 'XyZc04'],
    ['id' => 16, 'email' => 'wei.chen@gmail.com', 'mdp' => 'TrBn11'],
    ['id' => 17, 'email' => 'jisoo.kim@outlook.com', 'mdp' => 'VpWq22'],
    ['id' => 18, 'email' => 'linh.nguyen@icloud.com', 'mdp' => 'AsDf33'],
    ['id' => 19, 'email' => 'hans.fischer@hotmail.com', 'mdp' => 'ErTy44'],
    ['id' => 20, 'email' => 'fatima.abdullah@gmail.com', 'mdp' => 'UiOp55'],
    ['id' => 21, 'email' => 'rafael.pereira@outlook.com', 'mdp' => 'QwEr66'],
    ['id' => 22, 'email' => 'emma.robertson@icloud.com', 'mdp' => 'RtYu77'],
    ['id' => 23, 'email' => 'dmitri.ivanov@hotmail.com', 'mdp' => 'UiOp88'],
    ['id' => 24, 'email' => 'sofia.lopez@gmail.com', 'mdp' => 'AsDf99'],
    ['id' => 25, 'email' => 'haruto.kawamura@outlook.com', 'mdp' => 'ErTy00'],
    ['id' => 26, 'email' => 'lucia.garcia@icloud.com', 'mdp' => 'QwEr01'],
    ['id' => 27, 'email' => 'emeka.okafor@hotmail.com', 'mdp' => 'RtYu02'],
    ['id' => 28, 'email' => 'alice.dupont@gmail.com', 'mdp' => 'UiOp03'],
    ['id' => 29, 'email' => 'joao.mendes@outlook.com', 'mdp' => 'AsDf04'],
    ['id' => 30, 'email' => 'amina.lambert@icloud.com', 'mdp' => 'ErTy05'],
    ['id' => 31, 'email' => 'xiao.zhang@hotmail.com', 'mdp' => 'QwEr06'],
    ['id' => 32, 'email' => 'omar.ali@gmail.com', 'mdp' => 'RtYu07'],
    ['id' => 33, 'email' => 'luna.eilish@icloud.com', 'mdp' => 'Starlight98'],
    ['id' => 34, 'email' => 'maria.castillo@outlook.com', 'mdp' => 'UiOp08'],
    ['id' => 35, 'email' => 'erik.hansen@hotmail.fr', 'mdp' => 'AsDf09'],
];

echo "=== Génération des mots de passe hachés en bcrypt ===\n\n";

$sql_inserts = [];
foreach ($utilisateurs as $user) {
    $hash = password_hash($user['mdp'], PASSWORD_BCRYPT, ['cost' => 12]);
    $sql_inserts[] = "({$user['id']}, '{$hash}')";
    echo "ID {$user['id']}: {$user['email']}\n";
    echo "  Original: {$user['mdp']}\n";
    echo "  Hachage:  {$hash}\n\n";
}

// Créer la requête INSERT
$insert_query = "INSERT INTO `Utilisateur` (`id_utilisateur`, `mdp_utilisateur`) VALUES\n";
$insert_query .= implode(",\n", $sql_inserts) . ";";

// Sauvegarder dans un fichier
file_put_contents(__DIR__ . '/update_passwords_bcrypt.sql', $insert_query);

echo "\n✓ Fichier 'update_passwords_bcrypt.sql' généré avec succès!\n";
echo "Instruction: Exécutez ce fichier SQL dans phpMyAdmin pour mettre à jour les mots de passe.\n";
echo "Ou lancez: mysql -u root ytape_aploc < update_passwords_bcrypt.sql\n";
