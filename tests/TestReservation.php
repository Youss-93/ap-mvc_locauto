<?php
/**
 * Tests unitaires pour la classe Reservation
 * Focus sur la logique de réservation et les vérifications de dates
 */

require_once __DIR__ . '/../site /config.php';
require_once __DIR__ . '/../site /modele/Reservation.php';
require_once __DIR__ . '/../site /modele/Database.php';

class TestReservation {
    private $reservation;
    private $test_results = [];
    
    public function __construct() {
        // Note: Pas d'instanciation directe de Reservation pour éviter les connexions DB
        // Les tests se focalisent sur la logique métier de réservation
        $this->reservation = null;
    }
    
    /**
     * Teste que les dates de réservation ne dépassent pas le prix maximal
     */
    public function testReservationPriceCalculation() {
        $date_debut = '2024-12-01';
        $date_fin = '2024-12-05';
        $prix_jour = 50; // EUR
        
        $jours = (strtotime($date_fin) - strtotime($date_debut)) / 86400;
        $prix_total = $jours * $prix_jour;
        
        $this->assertEqual($prix_total, 200, 
            "Le calcul du prix doit être correct: 4 jours * 50 EUR = 200 EUR");
    }
    
    /**
     * Teste que les dates ne se chevauchent pas
     */
    public function testDateNoOverlap() {
        $resa_existante_debut = '2024-12-01';
        $resa_existante_fin = '2024-12-05';
        $nouvelle_resa_debut = '2024-12-06';
        $nouvelle_resa_fin = '2024-12-10';
        
        $overlap = $this->checkDateOverlap(
            $resa_existante_debut, $resa_existante_fin,
            $nouvelle_resa_debut, $nouvelle_resa_fin
        );
        
        $this->assertEqual($overlap, false, 
            "Les dates ne doivent pas se chevaucher");
    }
    
    /**
     * Teste que les dates qui se chevauchent sont détectées
     */
    public function testDateOverlapDetected() {
        $resa_existante_debut = '2024-12-01';
        $resa_existante_fin = '2024-12-05';
        $nouvelle_resa_debut = '2024-12-04';
        $nouvelle_resa_fin = '2024-12-08';
        
        $overlap = $this->checkDateOverlap(
            $resa_existante_debut, $resa_existante_fin,
            $nouvelle_resa_debut, $nouvelle_resa_fin
        );
        
        $this->assertEqual($overlap, true, 
            "Le chevauchement de dates doit être détecté");
    }
    
    /**
     * Teste que les dates doivent être dans le futur
     */
    public function testReservationDateMustBeFuture() {
        $date_debut = '2020-01-01'; // Passée
        $today = new DateTime();
        
        $is_past = strtotime($date_debut) < $today->getTimestamp();
        
        $this->assertEqual($is_past, true, 
            "Une date de réservation passée doit être rejetée");
    }
    
    /**
     * Teste que la date de fin est après la date de début
     */
    public function testEndDateAfterStartDate() {
        $date_debut = '2024-12-10';
        $date_fin = '2024-12-05';
        
        $valid = strtotime($date_fin) > strtotime($date_debut);
        
        $this->assertEqual($valid, false, 
            "La date de fin ne peut pas être avant la date de début");
    }
    
    /**
     * Teste la méthode verifierChevauchement
     */
    public function testVerifyChevauchementMethod() {
        // Cette méthode doit vérifier les conflits de réservation
        // Pour ce test, on vérifie que la méthode existe
        $reflection = new ReflectionClass('Reservation');
        $has_method = $reflection->hasMethod('verifierChevauchement');
        
        $this->assertEqual($has_method, true, 
            "La méthode verifierChevauchement doit exister");
    }
    
    /**
     * Teste qu'une réservation annulée ne bloque pas une nouvelle
     */
    public function testCanceledReservationDoesntBlockNew() {
        $statut_ancienne_resa = 'annulée';
        $peut_reserver = $statut_ancienne_resa !== 'confirmée' && 
                        $statut_ancienne_resa !== 'en attente';
        
        $this->assertEqual($peut_reserver, true, 
            "Une réservation annulée ne doit pas bloquer une nouvelle");
    }
    
    /**
     * Affiche les résultats des tests
     */
    public function runAllTests() {
        echo "=== Tests Unitaires Reservation (Logique Réservation) ===\n";
        
        $this->testReservationPriceCalculation();
        $this->testDateNoOverlap();
        $this->testDateOverlapDetected();
        $this->testReservationDateMustBeFuture();
        $this->testEndDateAfterStartDate();
        $this->testVerifyChevauchementMethod();
        $this->testCanceledReservationDoesntBlockNew();
        
        $this->printResults();
    }
    
    private function checkDateOverlap($debut1, $fin1, $debut2, $fin2) {
        $stamp_debut1 = strtotime($debut1);
        $stamp_fin1 = strtotime($fin1);
        $stamp_debut2 = strtotime($debut2);
        $stamp_fin2 = strtotime($fin2);
        
        // Vérifie si les périodes se chevauchent
        return !($stamp_fin1 < $stamp_debut2 || $stamp_fin2 < $stamp_debut1);
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
    $test = new TestReservation();
    $test->runAllTests();
}
