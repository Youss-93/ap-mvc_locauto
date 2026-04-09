<?php

class Categorie {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère toutes les catégories
     */
    public function getAll() {
        $sql = "SELECT * FROM categorie ORDER BY libelle_categorie ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une catégorie par ID
     */
    public function getById($id_categorie) {
        $sql = "SELECT * FROM categorie WHERE id_categorie = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id_categorie]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle catégorie
     */
    public function creer($libelle, $description = '') {
        try {
            $sql = "INSERT INTO categorie (libelle_categorie, description) 
                    VALUES (:libelle, :description)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'libelle' => $libelle,
                'description' => $description
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les catégories d'une voiture
     */
    public function getCategoriesVoiture($id_voiture) {
        $sql = "SELECT c.* FROM categorie c
                JOIN voiture_categorie vc ON c.id_categorie = vc.id_categorie
                WHERE vc.id_voiture = :id_voiture
                ORDER BY c.libelle_categorie ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_voiture' => $id_voiture]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une catégorie à une voiture
     */
    public function ajouterAVoiture($id_voiture, $id_categorie) {
        try {
            $sql = "INSERT IGNORE INTO voiture_categorie (id_voiture, id_categorie) 
                    VALUES (:id_voiture, :id_categorie)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_voiture' => $id_voiture,
                'id_categorie' => $id_categorie
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une catégorie d'une voiture
     */
    public function supprimerDeVoiture($id_voiture, $id_categorie) {
        try {
            $sql = "DELETE FROM voiture_categorie 
                    WHERE id_voiture = :id_voiture AND id_categorie = :id_categorie";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_voiture' => $id_voiture,
                'id_categorie' => $id_categorie
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les voitures d'une catégorie
     */
    public function getVoituresCategorie($id_categorie) {
        $sql = "SELECT v.* FROM voiture v
                JOIN voiture_categorie vc ON v.id_voiture = vc.id_voiture
                WHERE vc.id_categorie = :id_categorie
                ORDER BY v.marque ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_categorie' => $id_categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques de réservations par catégorie
     */
    public function getStatistiquesReservations() {
        $sql = "SELECT id_categorie, libelle_categorie, nb_reservations
                FROM stats_categories
                ORDER BY nb_reservations DESC, libelle_categorie ASC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Fallback si la vue SQL n'existe pas encore dans la base.
            $sqlFallback = "SELECT
                                c.id_categorie,
                                c.libelle_categorie,
                                COUNT(r.id_reservation) AS nb_reservations
                            FROM categorie c
                            LEFT JOIN voiture_categorie vc ON vc.id_categorie = c.id_categorie
                            LEFT JOIN voiture v ON v.id_voiture = vc.id_voiture
                            LEFT JOIN reservation r ON r.voiture_resa = v.id_voiture
                            GROUP BY c.id_categorie, c.libelle_categorie
                            ORDER BY nb_reservations DESC, c.libelle_categorie ASC";

            try {
                $stmt = $this->db->prepare($sqlFallback);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e2) {
                error_log($e2->getMessage());
                return [];
            }
        }
    }
}
