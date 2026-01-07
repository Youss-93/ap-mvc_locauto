-- Création de la base de données
-- et des tables pour l'application de location de voitures
CREATE DATABASE ytape_aploc;
USE ytape_aploc;

-- Table Utilisateur
CREATE TABLE Utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL,
    prenom_utilisateur VARCHAR(50) NOT NULL,
    email VARCHAR(90) UNIQUE NOT NULL,
    mdp_utilisateur VARCHAR(255) NOT NULL,
    num_tel VARCHAR(10) NOT NULL,
    role_utilisateur ENUM('admin','client') NOT NULL DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Voiture
CREATE TABLE Voiture (
    id_voiture INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT,
    modele VARCHAR(50) NOT NULL,
    marque VARCHAR(50) NOT NULL,
    année INT NOT NULL,
    prix_jour DECIMAL(10,2) NOT NULL,
    caution DECIMAL(10,2) NOT NULL,
    disponibilité BOOLEAN DEFAULT TRUE,
    image_loc VARCHAR(255),
    FOREIGN KEY (id_admin) REFERENCES Utilisateur(id_utilisateur) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Reservation
CREATE TABLE Reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    client_resa INT NOT NULL,
    voiture_resa INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut_reservation ENUM('en attente','confirmée','annulée') DEFAULT 'en attente',
    FOREIGN KEY (client_resa) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (voiture_resa) REFERENCES Voiture(id_voiture) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Paiement
CREATE TABLE Paiement (
    id_paiement INT AUTO_INCREMENT PRIMARY KEY,
    paiement_resa INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    mode_paiement ENUM('par Carte','Par Virement','Paypal') NOT NULL,
    statut_paiement ENUM('en attente','validée','échoué') DEFAULT 'en attente',
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paiement_resa) REFERENCES Reservation(id_reservation) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--Insertion dans la table utilisateur 
INSERT INTO Utilisateur (nom_utilisateur, prenom_utilisateur, email, mdp_utilisateur, num_tel, role_utilisateur) VALUES
-- Les Admins
('TAPE', 'Youssouf', 'adminYT19@gmail.com', 'Oqtf246', '0769294137', 'admin'),
('YANKAYA', 'Huseyin', 'adminHY93@hotmail.fr', 'HuseyinY123', '0745123987', 'admin'),
('SIMSEK', 'Mehmet', 'MSAdmin@hotmail.com', 'MehmetS456', '0789654123', 'admin'),
('GUENDOUZ', 'Souleyman', 'adminSouleyGuen@hotmail.fr', 'SouleymaneG789', '0723456789', 'admin'),
('GUEFFAF', 'Rayan', 'admingRG21@gmail.com', 'KRG9333', '0756789123', 'admin'),

-- Les Clients
('EPHESTION', 'Boris', '313kody@hotmail.fr', 'LmNo854', '0755432267', 'client'),
('BERLY', 'Kenneth', 'kenneth.berly@gmail.com', 'AbCd567', '0712345678', 'client'),
('RANGA', 'Dumitru', 'dumitru.ranga@outlook.com', 'DfGh890', '0798765432', 'client'),
('GAKOU', 'Diana', 'dgakou@icloud.com', 'JKLVR123', '0765432198', 'client'),
('MARTINEZ', 'Carlos', 'carlos.martinez@hotmail.com', 'XyZc04', '0776543219', 'client'),
('CHEN', 'Wei', 'wei.chen@gmail.com', 'TrBn11', '0734567891', 'client'),
('KIM', 'Jisoo', 'jisoo.kim@outlook.com', 'VpWq22', '0791234567', 'client'),
('NGUYEN', 'Linh', 'linh.nguyen@icloud.com', 'AsDf33', '0743216789', 'client'),
('FISCHER', 'Hans', 'hans.fischer@hotmail.com', 'ErTy44', '0789123456', 'client'),
('ABDULLAH', 'Fatima', 'fatima.abdullah@gmail.com', 'UiOp55', '0726789123', 'client'),
('PEREIRA', 'Rafael', 'rafael.pereira@outlook.com', 'QwEr66', '0754321987', 'client'),
('ROBERTSON', 'Emma', 'emma.robertson@icloud.com', 'RtYu77', '0798765432', 'client'),
('IVANOV', 'Dmitri', 'dmitri.ivanov@hotmail.com', 'UiOp88', '0745678912', 'client'),
('LOPEZ', 'Sofia', 'sofia.lopez@gmail.com', 'AsDf99', '0763219874', 'client'),
('KAWAMURA', 'Haruto', 'haruto.kawamura@outlook.com', 'ErTy00', '0786543219', 'client'),
('GARCIA', 'Lucia', 'lucia.garcia@icloud.com', 'QwEr01', '0723456798', 'client'),
('OKAFOR', 'Emeka', 'emeka.okafor@hotmail.com', 'RtYu02', '0756784321', 'client'),
('DUPONT', 'Alice', 'alice.dupont@gmail.com', 'UiOp03', '0791237896', 'client'),
('MENDES', 'João', 'joao.mendes@outlook.com', 'AsDf04', '0747893216', 'client'),
('LAMBERT', 'Amina', 'amina.lambert@icloud.com', 'ErTy05', '0783219876', 'client'),
('ZHANG', 'Xiao', 'xiao.zhang@hotmail.com', 'QwEr06', '0726789543', 'client'),
('ALI', 'Omar', 'omar.ali@gmail.com', 'RtYu07', '0754326789', 'client'),
('EILISH', 'Luna', 'luna.eilish@icloud.com', 'Starlight98', '0798764321', 'client'),
('CASTILLO', 'Maria', 'maria.castillo@outlook.com', 'UiOp08', '0789543216', 'client'),
('HANSEN', 'Erik', 'erik.hansen@hotmail.fr', 'AsDf09', '0732654987', 'client');


--Insertion dans la table Voiture 
INSERT INTO Voiture (id_admin, modele, marque, année, prix_jour, caution, disponibilité, image_loc) VALUES
(1, 'Cupra Formentor', 'Cupra', 2022, 150.00, 1500.00, TRUE, 'assets/photos/voitures/cupra_formentor.jpg'),
(1, 'Audi RS3', 'Audi', 2023, 200.00, 2000.00, FALSE, 'assets/photos/voitures/audi_rs3.jpg'),
(2, 'Lamborghini Aventador', 'Lamborghini', 2021, 750.00, 7000.00, TRUE, 'assets/photos/voitures/lamborghini_aventador.jpg'),
(2, 'BMW M8', 'BMW', 2022, 300.00, 3000.00, FALSE, 'assets/photos/voitures/bmw_m8.jpg'),
(3, 'Mercedes C63 AMG', 'Mercedes', 2020, 280.00, 2500.00, TRUE, 'assets/photos/voitures/mercedes_c63_amg.jpg'),
(3, 'Dodge Challenger Hellcat', 'Dodge', 2019, 260.00, 2200.00, FALSE, 'assets/photos/voitures/dodge_challenger_hellcat.jpg'),
(4, 'Mercedes G63', 'Mercedes', 2023, 500.00, 5000.00, TRUE, 'assets/photos/voitures/g63.jpg'),
(4, 'Porsche Cayenne', 'Porsche', 2021, 220.00, 2000.00, TRUE, 'assets/photos/voitures/porsche_cayenne.jpg'),
(5, 'Clio 5 Alpine', 'Renault', 2022, 120.00, 1000.00, FALSE, 'assets/photos/voitures/clio_5_alpine.jpg'),
(5, 'Lamborghini Urus Mansory', 'Lamborghini', 2023, 650.00, 6000.00, TRUE, 'assets/photos/voitures/urus_mansory.jpg'),
(1, 'Porsche GT3 RS', 'Porsche', 2022, 400.00, 4000.00, FALSE, 'assets/photos/voitures/gt3rs.jpg'),
(2, 'Porsche Macan', 'Porsche', 2021, 180.00, 1800.00, TRUE, 'assets/photos/voitures/porsche_macan.jpg'),
(3, 'Audi RSQ8', 'Audi', 2023, 350.00, 3500.00, TRUE, 'assets/photos/voitures/rsq8.jpg'),
(4, 'Audi R8', 'Audi', 2020, 600.00, 5000.00, FALSE, 'assets/photos/voitures/audi_r8.jpg'),
(5, 'Golf 8R', 'Volkswagen', 2022, 180.00, 1200.00, TRUE, 'assets/photos/voitures/golf8r.jpg'),
(1, 'BMW M5', 'BMW', 2021, 320.00, 3000.00, FALSE, 'assets/photos/voitures/bmw_m5.jpg'),
(2, 'BMW M140i', 'BMW', 2018, 140.00, 1200.00, TRUE, 'assets/photos/voitures/m140i.jpg'),
(3, 'Mercedes Classe A45', 'Mercedes', 2022, 230.00, 2500.00, FALSE, 'assets/photos/voitures/classe_a45.jpg'),
(4, 'Golf 7R', 'Volkswagen', 2019, 160.00, 1100.00, TRUE, 'assets/photos/voitures/golf7r.jpg'),
(5, 'Mercedes Classe G Brabus', 'Mercedes', 2023, 700.00, 7000.00, TRUE, 'assets/photos/voitures/classe_g_brabus.jpg'),
(1, 'Lamborghini Huracán', 'Lamborghini', 2021, 800.00, 7500.00, FALSE, 'assets/photos/voitures/lamborghini_huracan.jpg'),
(2, 'Ferrari Spider', 'Ferrari', 2020, 900.00, 8000.00, TRUE, 'assets/photos/voitures/ferrari_spider.jpg'),
(3, 'Audi TT', 'Audi', 2019, 170.00, 1500.00, FALSE, 'assets/photos/voitures/audi_tt.jpg'),
(4, 'Toyota Supra', 'Toyota', 2021, 250.00, 2500.00, TRUE, 'assets/photos/voitures/toyota_supra.jpg'),
(5, 'Audi RS6', 'Audi', 2022, 400.00, 3500.00, FALSE, 'assets/photos/voitures/audi_rs6.jpg');

--Insertion dans la table Reservation 
INSERT INTO Reservation (client_resa, voiture_resa, date_debut, date_fin, statut_reservation) VALUES
(6, 1, '2024-04-10', '2024-04-15', 'confirmée'),
(7, 2, '2024-04-12', '2024-04-18', 'en attente'),
(8, 3, '2024-04-15', '2024-04-20', 'confirmée'),
(9, 4, '2024-04-18', '2024-04-22', 'annulée'),
(10, 5, '2024-04-20', '2024-04-25', 'confirmée'),
(11, 6, '2024-04-25', '2024-05-01', 'en attente'),
(12, 7, '2024-04-28', '2024-05-03', 'confirmée'),
(13, 8, '2024-05-01', '2024-05-06', 'confirmée'),
(14, 9, '2024-05-03', '2024-05-08', 'annulée'),
(15, 10, '2024-05-05', '2024-05-10', 'confirmée'),
(16, 11, '2024-05-07', '2024-05-12', 'en attente'),
(17, 12, '2024-05-10', '2024-05-15', 'confirmée'),
(18, 13, '2024-05-12', '2024-05-17', 'confirmée'),
(19, 14, '2024-05-15', '2024-05-20', 'annulée'),
(20, 15, '2024-05-18', '2024-05-23', 'confirmée'),
(21, 16, '2024-05-20', '2024-05-25', 'en attente'),
(22, 17, '2024-05-22', '2024-05-27', 'confirmée'),
(23, 18, '2024-05-25', '2024-05-30', 'confirmée'),
(24, 19, '2024-05-28', '2024-06-02', 'confirmée'),
(25, 20, '2024-05-30', '2024-06-04', 'confirmée'),
(26, 21, '2024-06-02', '2024-06-07', 'en attente'),
(27, 22, '2024-06-05', '2024-06-10', 'confirmée'),
(28, 23, '2024-06-08', '2024-06-13', 'annulée'),
(29, 24, '2024-06-10', '2024-06-15', 'confirmée'),
(30, 25, '2024-06-12', '2024-06-17', 'confirmée');

--Insertion dans la table Paiement
INSERT INTO Paiement (paiement_resa, montant, mode_paiement, statut_paiement, date_paiement) VALUES
(1, 750.00, 'par Carte', 'validée', '2024-04-10 10:00:00'),
(2, 1200.00, 'Paypal', 'en attente', '2024-04-12 14:30:00'),
(3, 3750.00, 'Par Virement', 'validée', '2024-04-15 16:00:00'),
(4, 1200.00, 'par Carte', 'échoué', '2024-04-18 18:00:00'),
(5, 1400.00, 'Paypal', 'validée', '2024-04-20 09:00:00'),
(6, 1560.00, 'par Carte', 'en attente', '2024-04-25 11:00:00'),
(7, 2500.00, 'Par Virement', 'validée', '2024-04-28 13:30:00'),
(8, 1100.00, 'Paypal', 'validée', '2024-05-01 15:45:00'),
(9, 600.00, 'par Carte', 'échoué', '2024-05-03 17:00:00'),
(10, 3250.00, 'Paypal', 'validée', '2024-05-05 10:30:00'),
(11, 2000.00, 'Par Virement', 'en attente', '2024-05-07 12:00:00'),
(12, 900.00, 'par Carte', 'validée', '2024-05-10 14:00:00'),
(13, 1750.00, 'Paypal', 'validée', '2024-05-12 16:30:00'),
(14, 3000.00, 'par Carte', 'échoué', '2024-05-15 18:00:00'),
(15, 900.00, 'Par Virement', 'validée', '2024-05-18 09:00:00'),
(16, 1600.00, 'Paypal', 'en attente', '2024-05-20 11:30:00'),
(17, 700.00, 'par Carte', 'validée', '2024-05-22 13:00:00'),
(18, 1150.00, 'Paypal', 'validée', '2024-05-25 14:00:00'),
(19, 800.00, 'Par Virement', 'validée', '2024-05-28 15:00:00'),
(20, 3500.00, 'par Carte', 'validée', '2024-05-30 10:00:00'),
(21, 4000.00, 'Paypal', 'en attente', '2024-06-02 11:00:00'),
(22, 4500.00, 'Par Virement', 'validée', '2024-06-05 12:00:00'),
(23, 850.00, 'par Carte', 'échoué', '2024-06-08 13:30:00'),
(24, 1250.00, 'Paypal', 'validée', '2024-06-10 14:00:00'),
(25, 2000.00, 'Par Virement', 'validée', '2024-06-12 15:00:00');