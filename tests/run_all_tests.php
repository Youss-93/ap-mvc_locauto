<?php
/**
 * Test Runner Global - Exécute tous les tests du projet
 * Usage: php tests/run_all_tests.php
 */

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║           Test Suite - Application Locauto                ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Récupère tous les fichiers de test
$test_dir = __DIR__;
$test_files = array_filter(scandir($test_dir), function($file) {
    return substr($file, 0, 4) === 'Test' && substr($file, -4) === '.php';
});

$total_tests = 0;
$total_passed = 0;
$global_status = true;

foreach ($test_files as $test_file) {
    $file_path = $test_dir . '/' . $test_file;
    
    // Récupère le nom de la classe de test
    $class_name = substr($test_file, 0, -4);
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Exécution: $class_name\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Inclut le fichier de test
    $output = shell_exec("php " . escapeshellarg($file_path) . " 2>&1");
    echo $output;
    echo "\n";
    
    // Extrait le nombre de tests et de réussi
    if (preg_match('/Résultat:\s+(\d+)\/(\d+)\s+tests\s+passés/', $output, $matches)) {
        $passed = (int)$matches[1];
        $total = (int)$matches[2];
        $total_tests += $total;
        $total_passed += $passed;
        
        if ($passed < $total) {
            $global_status = false;
        }
    }
}

// Résumé global
echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    RÉSUMÉ GLOBAL                          ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";
echo "Tests Totaux: $total_tests\n";
echo "Tests Réussis: $total_passed\n";
echo "Tests Échoués: " . ($total_tests - $total_passed) . "\n";
echo "Taux de Réussite: " . ($total_tests > 0 ? round(($total_passed / $total_tests) * 100, 2) : 0) . "%\n\n";

if ($global_status) {
    echo "✓ TOUS LES TESTS SONT PASSÉS AVEC SUCCÈS!\n";
    exit(0);
} else {
    echo "✗ Certains tests ont échoué. Voir les détails ci-dessus.\n";
    exit(1);
}
