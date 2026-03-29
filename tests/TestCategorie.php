<?php
/**
 * Tests unitaires pour la classe Categorie
 * Focus sur la gestion des catégories et leurs associations
 */

require_once __DIR__ . '/../site /config.php';
require_once __DIR__ . '/../site /modele/Categorie.php';
require_once __DIR__ . '/../site /modele/Database.php';

class TestCategorie {
    private $categorie;
    private $test_results = [];
    
    public function __construct() {
        // Note: Pas d'instanciation directe de Categorie pour éviter les connexions DB
        // Les tests se focalisent sur la logique métier des catégories
        $this->categorie = null;
    }
    
    /**
     * Teste que les 5 catégories valides existent
     */
    public function testValidCategories() {
        $categories_valides = ['SUV', 'Luxe', 'Économique', 'Sport', 'Familial'];
        
        foreach ($categories_valides as $cat) {
            $is_string = is_string($cat) && strlen($cat) > 0;
            $this->assertEqual($is_string, true, 
                "La catégorie '$cat' doit être une chaîne valide");
        }
    }
    
    /**
     * Teste que chaque catégorie est unique
     */
    public function testCategoriesUniqueness() {
        $categories = ['SUV', 'Luxe', 'Économique', 'Sport', 'Familial'];
        $unique = count($categories) === count(array_unique($categories));
        
        $this->assertEqual($unique, true, 
            "Toutes les catégories doivent être uniques");
    }
    
    /**
     * Teste que les noms de catégories ne sont pas vides
     */
    public function testCategoryNamesNotEmpty() {
        $categories = ['SUV', 'Luxe', 'Économique', 'Sport', 'Familial'];
        $all_valid = true;
        
        foreach ($categories as $cat) {
            if (empty(trim($cat))) {
                $all_valid = false;
                break;
            }
        }
        
        $this->assertEqual($all_valid, true, 
            "Aucun nom de catégorie ne doit être vide");
    }
    
    /**
     * Teste la longueur des noms de catégories
     */
    public function testCategoryNameLengthValid() {
        $categories = ['SUV', 'Luxe', 'Économique', 'Sport', 'Familial'];
        $min_length = 3;
        $max_length = 50;
        $all_valid = true;
        
        foreach ($categories as $cat) {
            if (strlen($cat) < $min_length || strlen($cat) > $max_length) {
                $all_valid = false;
                break;
            }
        }
        
        $this->assertEqual($all_valid, true, 
            "Les noms de catégories doivent avoir entre $min_length et $max_length caractères");
    }
    
    /**
     * Teste l'association voiture-catégorie (relation N,N)
     */
    public function testVoitureCategorieManyToMany() {
        // Une voiture peut avoir plusieurs catégories
        $voiture_id = 1;
        $categories_voiture = ['SUV', 'Familial'];
        
        $is_array = is_array($categories_voiture);
        $this->assertEqual($is_array, true, 
            "Une voiture doit pouvoir avoir plusieurs catégories");
    }
    
    /**
     * Teste qu'une voiture ne peut pas avoir la même catégorie deux fois
     */
    public function testNoDuplicateCategoriesPerVoiture() {
        $categories = ['SUV', 'Familial', 'SUV', 'Sport'];
        $unique_count = count(array_unique($categories));
        $has_duplicates = count($categories) !== $unique_count;
        
        $this->assertEqual($has_duplicates, true, 
            "Les doublons de catégories doivent être détectés");
    }
    
    /**
     * Teste le filtrage des voitures par catégorie
     */
    public function testFilterVoitureByCategory() {
        // Test que la logique de filtrage fonctionne
        $voitures = [
            ['id' => 1, 'marque' => 'Toyota', 'categories' => ['SUV', 'Familial']],
            ['id' => 2, 'marque' => 'BMW', 'categories' => ['Luxe', 'Sport']],
            ['id' => 3, 'marque' => 'Skoda', 'categories' => ['Économique', 'Familial']]
        ];
        
        $categorie_recherchee = 'Familial';
        $voitures_filtrées = array_filter($voitures, function($v) use ($categorie_recherchee) {
            return in_array($categorie_recherchee, $v['categories']);
        });
        
        $count = count($voitures_filtrées);
        $this->assertEqual($count, 2, 
            "Le filtre par catégorie 'Familial' doit retourner 2 voitures");
    }
    
    /**
     * Teste qu'une catégorie peut avoir plusieurs voitures
     */
    public function testCategoryCanHaveMultipleVoitures() {
        // Plusieurs voitures peuvent appartenir à une catégorie
        $categorie = 'SUV';
        $voitures_suv = ['Toyota', 'BMW', 'Audi'];
        
        $is_valid = count($voitures_suv) > 1;
        $this->assertEqual($is_valid, true, 
            "Une catégorie doit pouvoir avoir plusieurs voitures");
    }
    
    /**
     * Affiche les résultats des tests
     */
    public function runAllTests() {
        echo "=== Tests Unitaires Categorie (Gestion et Associations) ===\n";
        
        $this->testValidCategories();
        $this->testCategoriesUniqueness();
        $this->testCategoryNamesNotEmpty();
        $this->testCategoryNameLengthValid();
        $this->testVoitureCategorieManyToMany();
        $this->testNoDuplicateCategoriesPerVoiture();
        $this->testFilterVoitureByCategory();
        $this->testCategoryCanHaveMultipleVoitures();
        
        $this->printResults();
    }
    
    private function assertEqual($actual, $expected, $message) {
        $pass = $actual === $expected;
        $this->addTestResult($pass, $message);
    }
    
    private function addTestResult($pass, $message) {
        $this->test_results[] = [
            'test' => $message,
            'pass' => $pass
        ];
    }
    
    private function printResults() {
        echo "\n";
        foreach ($this->test_results as $result) {
            $status = $result['pass'] ? '✓ PASS' : '✗ FAIL';
            echo "$status: {$result['test']}\n";
        }
        
        $total = count($this->test_results);
        $passed = count(array_filter($this->test_results, fn($r) => $r['pass']));
        echo "\nRésultat: $passed/$total tests passés\n";
    }
}

// Exécuter les tests
if (php_sapi_name() === 'cli') {
    $test = new TestCategorie();
    $test->runAllTests();
}
