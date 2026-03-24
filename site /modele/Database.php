<?php

class Database {
    private static ?PDO $instance = null;

    public static function getConnection():PDO {
        if (self::$instance === null) {
            try {
                // Charge la configuration globale si elle n'est pas deja disponible.
                if (!defined('DB_HOST')) {
                    require_once __DIR__ . '/../config.php';
                }

                // Valeurs par defaut compatibles Herd/local.
                $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
                $port = defined('DB_PORT') ? DB_PORT : 3306;
                $dbname = defined('DB_NAME') ? DB_NAME : 'ytape_aploc';
                $username = defined('DB_USER') ? DB_USER : 'root';
                $password = defined('DB_PASS') ? DB_PASS : '';

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