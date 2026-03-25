<?php

class Database {
    private static ?PDO $instance = null;

    public static function getConnection():PDO {
        if (self::$instance === null) {
            try {
                // Configuration depuis config.php
                $host = DB_HOST;
                $port = DB_PORT;
                $dbname = DB_NAME;
                $username = DB_USER;
                $password = DB_PASS;

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