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
        return $stmt->fetchAll();
    }

    /**
     * Récupère une catégorie par ID
     */
    public function getById($id_categorie) {
        $sql = "SELECT * FROM categorie WHERE id_categorie = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id_categorie]);
        return $stmt->fetch();
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
        return $stmt->fetchAll();
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
        return $stmt->fetchAll();
    }
}
