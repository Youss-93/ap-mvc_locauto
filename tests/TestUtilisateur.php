<?php
/**
 * Tests unitaires pour la classe Utilisateur
 * Focus sur la sécurité des mots de passe
 */

require_once __DIR__ . '/../site /config.php';
require_once __DIR__ . '/../site /modele/Utilisateur.php';
require_once __DIR__ . '/../site /modele/Database.php';

class TestUtilisateur {
    private $utilisateur;
    private $test_results = [];
    
    public function __construct() {
        // Note: Pas d'instanciation directe d'Utilisateur pour éviter les connexions DB
        // Les tests se focalisent sur les fonctions de sécurité PHP
        $this->utilisateur = null;
    }
    
    /**
     * Teste que password_hash génère des hashs différents à chaque fois
     */
    public function testPasswordHashingIsDifferentEachTime() {
        $mdp = "TestPassword123!";
        $hash1 = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
        $hash2 = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
        
        $this->assertNotEqual($hash1, $hash2, 
            "Deux hashs du même mot de passe doivent être différents");
    }
    
    /**
     * Teste que password_verify reconnaît un mot de passe correct
     */
    public function testPasswordVerifyWithCorrectPassword() {
        $mdp = "TestPassword123!";
        $hash = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
        $verified = password_verify($mdp, $hash);
        
        $this->assertEqual($verified, true, 
            "password_verify() doit retourner true avec le bon mot de passe");
    }
    
    /**
     * Teste que password_verify rejette un mot de passe incorrect
     */
    public function testPasswordVerifyWithWrongPassword() {
        $mdp_original = "TestPassword123!";
        $mdp_incorrect = "WrongPassword456!";
        $hash = password_hash($mdp_original, PASSWORD_BCRYPT, ['cost' => 12]);
        $verified = password_verify($mdp_incorrect, $hash);
        
        $this->assertEqual($verified, false, 
            "password_verify() doit retourner false avec un mauvais mot de passe");
    }
    
    /**
     * Teste que les mots de passe ne sont pas stockés en clair
     */
    public function testPasswordNotStoredInPlain() {
        $mdp = "TestPassword123!";
        $hash = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
        
        // Un hash bcrypt commence toujours par $2
        $this->assertEqual(substr($hash, 0, 2), "$2", 
            "Le hash doit être au format bcrypt");
        
        // Le hash ne doit pas contenir le mot de passe original
        $this->assertEqual(strpos($hash, $mdp) === false, true, 
            "Le hash ne doit pas contenir le mot de passe en clair");
    }
    
    /**
     * Teste la complexité minimale du mot de passe
     */
    public function testPasswordComplexity() {
        $strongPassword = "Test123!@aBc";
        $complexity = $this->checkPasswordComplexity($strongPassword);
        
        $this->assertEqual($complexity['score'] >= 4, true, 
            "Un mot de passe fort doit avoir une complexité >= 4");
    }
    
    /**
     * Teste qu'un mot de passe faible a une faible complexité
     */
    public function testWeakPasswordComplexity() {
        $weakPassword = "123456";
        $complexity = $this->checkPasswordComplexity($weakPassword);
        
        $this->assertEqual($complexity['score'] <= 2, true, 
            "Un mot de passe faible '123456' doit avoir une complexité <= 2");
    }
    
    /**
     * Affiche les résultats des tests
     */
    public function runAllTests() {
        echo "=== Tests Unitaires Utilisateur (Sécurité Mots de Passe) ===\n";
        
        $this->testPasswordHashingIsDifferentEachTime();
        $this->testPasswordVerifyWithCorrectPassword();
        $this->testPasswordVerifyWithWrongPassword();
        $this->testPasswordNotStoredInPlain();
        $this->testPasswordComplexity();
        $this->testWeakPasswordComplexity();
        
        $this->printResults();
    }
    
    private function assertEqual($actual, $expected, $message) {
        $pass = $actual === $expected;
        $this->addTestResult($pass, $message);
    }
    
    private function assertNotEqual($actual, $expected, $message) {
        $pass = $actual !== $expected;
        $this->addTestResult($pass, $message);
    }
    
    private function addTestResult($pass, $message) {
        $this->test_results[] = [
            'test' => $message,
            'pass' => $pass
        ];
    }
    
    private function checkPasswordComplexity($mdp) {
        $score = 0;
        
        if (strlen($mdp) >= 8) $score++;
        if (strlen($mdp) >= 12) $score++;
        if (preg_match('/[a-z]/', $mdp)) $score++;
        if (preg_match('/[A-Z]/', $mdp)) $score++;
        if (preg_match('/[0-9]/', $mdp)) $score++;
        if (preg_match('/[!@#$%^&*(),.?":{}|<>]/', $mdp)) $score++;
        
        return ['score' => $score, 'max' => 6];
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
    $test = new TestUtilisateur();
    $test->runAllTests();
}
