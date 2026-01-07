<?php

class Database {
    private static ?PDO $instance = null;

    public static function getConnection():PDO {
        if (self::$instance === null) {
            try {
                // Configuration pour MAMP
                $host = 'localhost';
                $port = 8889; // Port par défaut de MAMP
                $dbname = 'ytape_aploc';
                $username = 'root';
                $password = 'root'; // Mot de passe par défaut de MAMP

                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ];
                
                self::$instance = new PDO($dsn, $username, $password, $options);
                
                
            } catch (PDOException $e) {
                die("<p style='color: red;'>Erreur de connexion : " . $e->getMessage() . "</p>");
            }
        }
        return self::$instance;
    }
}