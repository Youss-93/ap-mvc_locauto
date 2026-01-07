-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 29 avr. 2025 à 13:18
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ytape_aploc`
--

-- --------------------------------------------------------

--
-- Structure de la table `Paiement`
--

CREATE TABLE `Paiement` (
  `id_paiement` int NOT NULL,
  `paiement_resa` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `mode_paiement` enum('par Carte','Par Virement','Paypal') NOT NULL,
  `statut_paiement` enum('en attente','validée','échoué') DEFAULT 'en attente',
  `date_paiement` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Paiement`
--

INSERT INTO `Paiement` (`id_paiement`, `paiement_resa`, `montant`, `mode_paiement`, `statut_paiement`, `date_paiement`) VALUES
(10001, 1001, 750.00, 'par Carte', 'validée', '2024-04-10 08:00:00'),
(10002, 1002, 1200.00, 'Paypal', 'en attente', '2024-04-12 12:30:00'),
(10003, 1003, 3750.00, 'Par Virement', 'validée', '2024-04-15 14:00:00'),
(10004, 1004, 1200.00, 'par Carte', 'échoué', '2024-04-18 16:00:00'),
(10005, 1005, 1400.00, 'Paypal', 'validée', '2024-04-20 07:00:00'),
(10006, 1006, 1560.00, 'par Carte', 'en attente', '2024-04-25 09:00:00'),
(10007, 1007, 2500.00, 'Par Virement', 'validée', '2024-04-28 11:30:00'),
(10008, 1008, 1100.00, 'Paypal', 'validée', '2024-05-01 13:45:00'),
(10009, 1009, 600.00, 'par Carte', 'échoué', '2024-05-03 15:00:00'),
(10010, 1010, 3250.00, 'Paypal', 'validée', '2024-05-05 08:30:00'),
(10011, 1011, 2000.00, 'Par Virement', 'en attente', '2024-05-07 10:00:00'),
(10012, 1012, 900.00, 'par Carte', 'validée', '2024-05-10 12:00:00'),
(10013, 1013, 1750.00, 'Paypal', 'validée', '2024-05-12 14:30:00'),
(10014, 1014, 3000.00, 'par Carte', 'échoué', '2024-05-15 16:00:00'),
(10015, 1015, 900.00, 'Par Virement', 'validée', '2024-05-18 07:00:00'),
(10016, 1016, 1600.00, 'Paypal', 'en attente', '2024-05-20 09:30:00'),
(10017, 1017, 700.00, 'par Carte', 'validée', '2024-05-22 11:00:00'),
(10018, 1018, 1150.00, 'Paypal', 'validée', '2024-05-25 12:00:00'),
(10019, 1019, 800.00, 'Par Virement', 'validée', '2024-05-28 13:00:00'),
(10020, 1020, 3500.00, 'par Carte', 'validée', '2024-05-30 08:00:00'),
(10021, 1021, 4000.00, 'Paypal', 'en attente', '2024-06-02 09:00:00'),
(10022, 1022, 4500.00, 'Par Virement', 'validée', '2024-06-05 10:00:00'),
(10023, 1023, 850.00, 'par Carte', 'échoué', '2024-06-08 11:30:00'),
(10024, 1024, 1250.00, 'Paypal', 'validée', '2024-06-10 12:00:00'),
(10025, 1025, 2000.00, 'Par Virement', 'validée', '2024-06-12 13:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `Reservation`
--

CREATE TABLE `Reservation` (
  `id_reservation` int NOT NULL,
  `client_resa` int NOT NULL,
  `voiture_resa` int NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut_reservation` enum('en attente','confirmée','annulée') DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Reservation`
--

INSERT INTO `Reservation` (`id_reservation`, `client_resa`, `voiture_resa`, `date_debut`, `date_fin`, `statut_reservation`) VALUES
(1001, 11, 101, '2024-04-10', '2024-04-15', 'confirmée'),
(1002, 12, 102, '2024-04-12', '2024-04-18', 'en attente'),
(1003, 13, 103, '2024-04-15', '2024-04-20', 'confirmée'),
(1004, 14, 104, '2024-04-18', '2024-04-22', 'annulée'),
(1005, 15, 105, '2024-04-20', '2024-04-25', 'confirmée'),
(1006, 16, 106, '2024-04-25', '2024-05-01', 'en attente'),
(1007, 17, 107, '2024-04-28', '2024-05-03', 'confirmée'),
(1008, 18, 108, '2024-05-01', '2024-05-06', 'confirmée'),
(1009, 19, 109, '2024-05-03', '2024-05-08', 'annulée'),
(1010, 20, 110, '2024-05-05', '2024-05-10', 'confirmée'),
(1011, 21, 111, '2024-05-07', '2024-05-12', 'en attente'),
(1012, 22, 112, '2024-05-10', '2024-05-15', 'confirmée'),
(1013, 23, 113, '2024-05-12', '2024-05-17', 'confirmée'),
(1014, 24, 114, '2024-05-15', '2024-05-20', 'annulée'),
(1015, 25, 115, '2024-05-18', '2024-05-23', 'confirmée'),
(1016, 26, 116, '2024-05-20', '2024-05-25', 'en attente'),
(1017, 27, 117, '2024-05-22', '2024-05-27', 'confirmée'),
(1018, 28, 118, '2024-05-25', '2024-05-30', 'confirmée'),
(1019, 29, 119, '2024-05-28', '2024-06-02', 'confirmée'),
(1020, 30, 120, '2024-05-30', '2024-06-04', 'confirmée'),
(1021, 31, 121, '2024-06-02', '2024-06-07', 'en attente'),
(1022, 32, 122, '2024-06-05', '2024-06-10', 'confirmée'),
(1023, 33, 123, '2024-06-08', '2024-06-13', 'annulée'),
(1024, 34, 124, '2024-06-10', '2024-06-15', 'confirmée'),
(1025, 35, 125, '2024-06-12', '2024-06-17', 'confirmée');

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `id_utilisateur` int NOT NULL,
  `nom_utilisateur` varchar(50) NOT NULL,
  `prenom_utilisateur` varchar(50) NOT NULL,
  `email` varchar(90) NOT NULL,
  `mdp_utilisateur` varchar(255) NOT NULL,
  `num_tel` varchar(10) NOT NULL,
  `role_utilisateur` enum('admin','client') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`id_utilisateur`, `nom_utilisateur`, `prenom_utilisateur`, `email`, `mdp_utilisateur`, `num_tel`, `role_utilisateur`) VALUES
(1, 'TAPE', 'Youssouf', 'adminYT19@gmail.com', 'Oqtf246', '0769294137', 'admin'),
(2, 'YANKAYA', 'Huseyin', 'adminHY93@hotmail.fr', 'HuseyinY123', '0745123987', 'admin'),
(3, 'SIMSEK', 'Mehmet', 'MSAdmin@hotmail.com', 'MehmetS456', '0789654123', 'admin'),
(4, 'GUENDOUZ', 'Souleyman', 'adminSouleyGuen@hotmail.fr', 'SouleymaneG789', '0723456789', 'admin'),
(5, 'GUEFFAF', 'Rayan', 'admingRG21@gmail.com', 'KRG9333', '0756789123', 'admin'),
(11, 'EPHESTION', 'Boris', '313kody@hotmail.fr', 'LmNo854', '0755432267', 'client'),
(12, 'BERLY', 'Kenneth', 'kenneth.berly@gmail.com', 'AbCd567', '0712345678', 'client'),
(13, 'RANGA', 'Dumitru', 'dumitru.ranga@outlook.com', 'DfGh890', '0798765432', 'client'),
(14, 'GAKOU', 'Diana', 'dgakou@icloud.com', 'JKLVR123', '0765432198', 'client'),
(15, 'MARTINEZ', 'Carlos', 'carlos.martinez@hotmail.com', 'XyZc04', '0776543219', 'client'),
(16, 'CHEN', 'Wei', 'wei.chen@gmail.com', 'TrBn11', '0734567891', 'client'),
(17, 'KIM', 'Jisoo', 'jisoo.kim@outlook.com', 'VpWq22', '0791234567', 'client'),
(18, 'NGUYEN', 'Linh', 'linh.nguyen@icloud.com', 'AsDf33', '0743216789', 'client'),
(19, 'FISCHER', 'Hans', 'hans.fischer@hotmail.com', 'ErTy44', '0789123456', 'client'),
(20, 'ABDULLAH', 'Fatima', 'fatima.abdullah@gmail.com', 'UiOp55', '0726789123', 'client'),
(21, 'PEREIRA', 'Rafael', 'rafael.pereira@outlook.com', 'QwEr66', '0754321987', 'client'),
(22, 'ROBERTSON', 'Emma', 'emma.robertson@icloud.com', 'RtYu77', '0798765432', 'client'),
(23, 'IVANOV', 'Dmitri', 'dmitri.ivanov@hotmail.com', 'UiOp88', '0745678912', 'client'),
(24, 'LOPEZ', 'Sofia', 'sofia.lopez@gmail.com', 'AsDf99', '0763219874', 'client'),
(25, 'KAWAMURA', 'Haruto', 'haruto.kawamura@outlook.com', 'ErTy00', '0786543219', 'client'),
(26, 'GARCIA', 'Lucia', 'lucia.garcia@icloud.com', 'QwEr01', '0723456798', 'client'),
(27, 'OKAFOR', 'Emeka', 'emeka.okafor@hotmail.com', 'RtYu02', '0756784321', 'client'),
(28, 'DUPONT', 'Alice', 'alice.dupont@gmail.com', 'UiOp03', '0791237896', 'client'),
(29, 'MENDES', 'João', 'joao.mendes@outlook.com', 'AsDf04', '0747893216', 'client'),
(30, 'LAMBERT', 'Amina', 'amina.lambert@icloud.com', 'ErTy05', '0783219876', 'client'),
(31, 'ZHANG', 'Xiao', 'xiao.zhang@hotmail.com', 'QwEr06', '0726789543', 'client'),
(32, 'ALI', 'Omar', 'omar.ali@gmail.com', 'RtYu07', '0754326789', 'client'),
(33, 'EILISH', 'Luna', 'luna.eilish@icloud.com', 'Starlight98', '0798764321', 'client'),
(34, 'CASTILLO', 'Maria', 'maria.castillo@outlook.com', 'UiOp08', '0789543216', 'client'),
(35, 'HANSEN', 'Erik', 'erik.hansen@hotmail.fr', 'AsDf09', '0732654987', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `Voiture`
--

CREATE TABLE `Voiture` (
  `id_voiture` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `modele` varchar(50) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `année` int NOT NULL,
  `prix_jour` decimal(10,2) NOT NULL,
  `caution` decimal(10,2) NOT NULL,
  `disponibilité` tinyint(1) DEFAULT '1',
  `image_loc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Voiture`
--

INSERT INTO `Voiture` (`id_voiture`, `id_admin`, `modele`, `marque`, `année`, `prix_jour`, `caution`, `disponibilité`, `image_loc`) VALUES
(101, 1, 'Cupra Formentor', 'Cupra', 2022, 150.00, 1500.00, 1, 'cupra_formentor.jpg'),
(102, 1, 'Audi RS3', 'Audi', 2023, 200.00, 2000.00, 0, 'audi_rs3.jpg'),
(103, 2, 'Lamborghini Aventador', 'Lamborghini', 2021, 750.00, 7000.00, 1, 'lamborghini_aventador.jpg'),
(104, 2, 'BMW M8', 'BMW', 2022, 300.00, 3000.00, 0, 'bmw_m8.jpg'),
(105, 3, 'Mercedes C63 AMG', 'Mercedes', 2020, 280.00, 2500.00, 1, 'mercedes_c63_amg.jpg'),
(106, 3, 'Dodge Challenger Hellcat', 'Dodge', 2019, 260.00, 2200.00, 0, 'dodge_challenger_hellcat.jpg'),
(107, 4, 'Mercedes G63', 'Mercedes', 2023, 500.00, 5000.00, 1, 'g63.jpg'),
(108, 4, 'Porsche Cayenne', 'Porsche', 2021, 220.00, 2000.00, 1, 'porsche_cayenne.jpg'),
(109, 5, 'Clio 5 Alpine', 'Renault', 2022, 120.00, 1000.00, 0, 'clio_5_alpine.jpg'),
(110, 5, 'Lamborghini Urus Mansory', 'Lamborghini', 2023, 650.00, 6000.00, 1, 'urus_mansory.jpg'),
(111, 1, 'Porsche GT3 RS', 'Porsche', 2022, 400.00, 4000.00, 0, 'gt3rs.jpg'),
(112, 2, 'Porsche Macan', 'Porsche', 2021, 180.00, 1800.00, 1, 'porsche_macan.jpg'),
(113, 3, 'Audi RSQ8', 'Audi', 2023, 350.00, 3500.00, 1, 'rsq8.jpg'),
(114, 4, 'Audi R8', 'Audi', 2020, 600.00, 5000.00, 0, 'audi_r8.jpg'),
(115, 5, 'Golf 8R', 'Volkswagen', 2022, 180.00, 1200.00, 1, 'golf8r.jpg'),
(116, 1, 'BMW M5', 'BMW', 2021, 320.00, 3000.00, 0, 'bmw_m5.jpg'),
(117, 2, 'BMW M140i', 'BMW', 2018, 140.00, 1200.00, 1, 'm140i.jpg'),
(118, 3, 'Mercedes Classe A45', 'Mercedes', 2022, 230.00, 2500.00, 0, 'classe_a45.jpg'),
(119, 4, 'Golf 7R', 'Volkswagen', 2019, 160.00, 1100.00, 1, 'golf7r.jpg'),
(120, 5, 'Mercedes Classe G Brabus', 'Mercedes', 2023, 700.00, 7000.00, 1, 'classe_g_brabus.jpg'),
(121, 1, 'Lamborghini Huracán', 'Lamborghini', 2021, 800.00, 7500.00, 0, 'lamborghini_huracan.jpg'),
(122, 2, 'Ferrari Spider', 'Ferrari', 2020, 900.00, 8000.00, 1, 'ferrari_spider.jpg'),
(123, 3, 'Audi TT', 'Audi', 2019, 170.00, 1500.00, 0, 'audi_tt.jpg'),
(124, 4, 'Toyota Supra', 'Toyota', 2021, 250.00, 2500.00, 1, 'toyota_supra.jpg'),
(125, 5, 'Audi RS6', 'Audi', 2022, 400.00, 3500.00, 0, 'audi_rs6.jpg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Paiement`
--
ALTER TABLE `Paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `paiement_resa` (`paiement_resa`);

--
-- Index pour la table `Reservation`
--
ALTER TABLE `Reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `client_resa` (`client_resa`),
  ADD KEY `voiture_resa` (`voiture_resa`);

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `Voiture`
--
ALTER TABLE `Voiture`
  ADD PRIMARY KEY (`id_voiture`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Paiement`
--
ALTER TABLE `Paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`paiement_resa`) REFERENCES `Reservation` (`id_reservation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Reservation`
--
ALTER TABLE `Reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`client_resa`) REFERENCES `Utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`voiture_resa`) REFERENCES `Voiture` (`id_voiture`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Voiture`
--
ALTER TABLE `Voiture`
  ADD CONSTRAINT `voiture_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `Utilisateur` (`id_utilisateur`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
