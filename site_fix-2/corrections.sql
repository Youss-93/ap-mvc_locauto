-- ============================================================
-- CORRECTIONS SQL – Site location de voiture
-- À importer dans phpMyAdmin après ytape_aploc.sql
-- ============================================================

-- 1. Table Categorie (manquante dans le SQL original)
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `libelle_categorie` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT '',
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table de liaison Voiture ↔ Categorie (association 1,N → 1,N)
CREATE TABLE IF NOT EXISTS `voiture_categorie` (
  `id_voiture` int NOT NULL,
  `id_categorie` int NOT NULL,
  PRIMARY KEY (`id_voiture`, `id_categorie`),
  FOREIGN KEY (`id_voiture`) REFERENCES `Voiture`(`id_voiture`) ON DELETE CASCADE,
  FOREIGN KEY (`id_categorie`) REFERENCES `categorie`(`id_categorie`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Données de test pour les catégories
INSERT INTO `categorie` (`libelle_categorie`, `description`) VALUES
('Citadine', 'Petite voiture facile à garer en ville'),
('Berline', 'Voiture familiale confortable'),
('SUV', 'Véhicule utilitaire sport, haut de gamme'),
('Sportive', 'Voiture haute performance'),
('Luxe', 'Véhicule haut de gamme premium'),
('Utilitaire', 'Véhicule de transport utilitaire');

-- 4. Association voitures ↔ catégories (données de test)
INSERT INTO `voiture_categorie` (`id_voiture`, `id_categorie`) VALUES
(101, 3), -- Cupra Formentor → SUV
(101, 4), -- Cupra Formentor → Sportive
(102, 4), -- Audi RS3 → Sportive
(103, 4), -- Lamborghini Aventador → Sportive
(103, 5), -- Lamborghini Aventador → Luxe
(104, 4), -- BMW M8 → Sportive
(104, 5), -- BMW M8 → Luxe
(105, 4), -- Mercedes C63 AMG → Sportive
(106, 4), -- Dodge Challenger → Sportive
(107, 3), -- Mercedes G63 → SUV
(107, 5), -- Mercedes G63 → Luxe
(108, 3), -- Porsche Cayenne → SUV
(109, 1), -- Clio 5 Alpine → Citadine
(110, 3), -- Urus Mansory → SUV
(110, 5), -- Urus Mansory → Luxe
(111, 4), -- Porsche GT3 RS → Sportive
(112, 3), -- Porsche Macan → SUV
(113, 3), -- Audi RSQ8 → SUV
(114, 4), -- Audi R8 → Sportive
(114, 5), -- Audi R8 → Luxe
(115, 1), -- Golf 8R → Citadine
(116, 4), -- BMW M5 → Sportive
(117, 2), -- BMW M140i → Berline
(118, 4), -- Mercedes A45 → Sportive
(119, 1), -- Golf 7R → Citadine
(120, 5), -- Mercedes G Brabus → Luxe
(121, 4), -- Lamborghini Huracán → Sportive
(121, 5), -- Lamborghini Huracán → Luxe
(122, 4), -- Ferrari Spider → Sportive
(122, 5), -- Ferrari Spider → Luxe
(123, 4), -- Audi TT → Sportive
(124, 4), -- Toyota Supra → Sportive
(125, 4), -- Audi RS6 → Sportive
(125, 2); -- Audi RS6 → Berline

-- ============================================================
-- 5. Correction des mots de passe (étaient en clair, doivent
--    être hashés en bcrypt pour que password_verify() fonctionne)
--    Nouveaux mots de passe : Admin123! pour les admins
--                             Client123! pour les clients
-- ============================================================
UPDATE `Utilisateur` SET `mdp_utilisateur` = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE `role_utilisateur` = 'admin';
-- Hash bcrypt de 'Admin123!'

UPDATE `Utilisateur` SET `mdp_utilisateur` = '$2y$12$iNzVqPGJmMJasLXmj/y7oONnflDxFJ0Ns7yXINGiSP31v5V5KFPCK'
WHERE `role_utilisateur` = 'client';
-- Hash bcrypt de 'Client123!'
