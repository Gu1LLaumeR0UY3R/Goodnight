-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 01 déc. 2025 à 16:11
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `goodnight`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nom_admin` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom_admin` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email_admin` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe_admin` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_admin` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id_admin`, `nom_admin`, `prenom_admin`, `email_admin`, `mot_de_passe_admin`, `is_admin`) VALUES
(1, 'admin', 'test', 'admin@example.com', '$2y$10$qagrgyFpGbj9Wr1.6fwIfOVwgQvs6v8NcBd6okrMkFN.z1mHkn1Ue', 1); -- password123

-- --------------------------------------------------------

--
-- Structure de la table `biens`
--

DROP TABLE IF EXISTS `biens`;
CREATE TABLE IF NOT EXISTS `biens` (
  `id_biens` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `designation_bien` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rue_biens` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `complement_biens` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `superficie_biens` decimal(10,2) NOT NULL,
  `description_biens` text COLLATE utf8mb4_general_ci,
  `animaux_biens` tinyint(1) DEFAULT '0',
  `nb_couchage` int NOT NULL,
  `id_TypeBien` int NOT NULL,
  `id_commune` mediumint UNSIGNED NOT NULL,
  `id_locataire` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_biens`),
  KEY `id_TypeBien` (`id_TypeBien`),
  KEY `id_commune` (`id_commune`),
  KEY `idx_proprietaire` (`id_locataire`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `blocages`
--

DROP TABLE IF EXISTS `blocages`;
CREATE TABLE IF NOT EXISTS `blocages` (
  `id_blocage` int UNSIGNED NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `motif` enum('personnel','entretien','fermeture','autre') COLLATE utf8mb4_general_ci DEFAULT 'personnel',
  `commentaire` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commune`
--

DROP TABLE IF EXISTS `commune`;
CREATE TABLE IF NOT EXISTS `commune` (
  `id_commune` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ville_departement` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_nom` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_nom_simple` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_nom_reel` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_nom_soundex` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_nom_metaphone` varchar(22) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_code_postal` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_commune` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_code_commune` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `ville_arrondissement` smallint UNSIGNED DEFAULT NULL,
  `ville_canton` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_amdi` smallint UNSIGNED DEFAULT NULL,
  `ville_population_2010` mediumint UNSIGNED DEFAULT NULL,
  `ville_population_1999` mediumint UNSIGNED DEFAULT NULL,
  `ville_population_2012` mediumint UNSIGNED DEFAULT NULL,
  `ville_densite_2010` int DEFAULT NULL,
  `ville_surface` float DEFAULT NULL,
  `ville_longitude_deg` float DEFAULT NULL,
  `ville_latitude_deg` float DEFAULT NULL,
  `ville_longitude_grd` varchar(9) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_latitude_grd` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_longitude_dms` varchar(9) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_latitude_dms` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville_zmin` mediumint DEFAULT NULL,
  `ville_zmax` mediumint DEFAULT NULL,
  PRIMARY KEY (`id_commune`),
  UNIQUE KEY `ville_code_commune_2` (`ville_code_commune`),
  UNIQUE KEY `ville_slug` (`ville_slug`),
  KEY `ville_departement` (`ville_departement`),
  KEY `ville_nom` (`ville_nom`),
  KEY `ville_code_postal` (`ville_code_postal`)
) ENGINE=InnoDB AUTO_INCREMENT=36831 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `locataire`
--

DROP TABLE IF EXISTS `locataire`;
CREATE TABLE IF NOT EXISTS `locataire` (
  `id_locataire` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_locataire` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prenom_locataire` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateNaissance_locataire` date DEFAULT NULL,
  `email_locataire` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_locataire` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tel_locataire` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rue_locataire` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `complement_locataire` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `RaisonSociale` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Siret` varchar(14) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_commune` mediumint UNSIGNED DEFAULT NULL,
  `pfp_loca` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_locataire`),
  UNIQUE KEY `email_locataire` (`email_locataire`),
  KEY `id_commune` (`id_commune`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id_photo` int NOT NULL AUTO_INCREMENT,
  `nom_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lien_photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `id_biens` (`id_biens`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prestation`
--

DROP TABLE IF EXISTS `prestation`;
CREATE TABLE IF NOT EXISTS `prestation` (
  `id_prestation` int NOT NULL AUTO_INCREMENT,
  `lib_prestation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prestation`
--

INSERT INTO `prestation` (`id_prestation`, `lib_prestation`) VALUES
(1, 'WiFi'),
(2, 'Salle de bain'),
(3, 'Salle à manger'),
(4, 'Cuisine équipée'),
(5, 'Chambre'),
(6, 'Salon'),
(7, 'Terrasse'),
(8, 'Jardin'),
(9, 'Piscine'),
(10, 'Parking'),
(11, 'Garage'),
(12, 'Climatisation'),
(13, 'Chauffage'),
(14, 'Télévision'),
(15, 'Lave-linge'),
(16, 'Lave-vaisselle'),
(17, 'Four'),
(18, 'Micro-ondes'),
(19, 'Réfrigérateur'),
(20, 'Congélateur'),
(21, 'Machine à café'),
(22, 'Barbecue'),
(23, 'Balcon'),
(24, 'Cheminée'),
(25, 'Jacuzzi'),
(26, 'Sauna'),
(27, 'Salle de sport'),
(28, 'Bureau'),
(29, 'WC séparé'),
(30, 'Baignoire');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id_reservation` int NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_locataire` int UNSIGNED NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  `id_tarif` int NOT NULL,
  PRIMARY KEY (`id_reservation`),
  KEY `id_locataire` (`id_locataire`),
  KEY `id_biens` (`id_biens`),
  KEY `id_tarif` (`id_tarif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id_roles` int NOT NULL AUTO_INCREMENT,
  `nom_roles` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_roles`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id_roles`, `nom_roles`) VALUES
(1, 'Propriétaire'),
(2, 'Locataire');

-- --------------------------------------------------------

--
-- Structure de la table `saison`
--

DROP TABLE IF EXISTS `saison`;
CREATE TABLE IF NOT EXISTS `saison` (
  `id_saison` int NOT NULL AUTO_INCREMENT,
  `lib_saison` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_saison`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `saison`
--

INSERT INTO `saison` (`id_saison`, `lib_saison`, `date_debut`, `date_fin`) VALUES
(1, 'Moyenne saison printemps', '2025-03-01', '2025-05-31'),
(2, 'Haute saison été', '2025-06-01', '2025-08-31'),
(3, 'Basse saison', '2025-09-01', '2025-11-30'),
(4, 'Moyenne saison hiver', '2025-12-01', '2026-02-28'),
(5, 'Moyenne saison printemps', '2026-03-01', '2026-06-30'),
(6, 'Haute saison été', '2026-07-01', '2026-08-31'),
(10, 'Vacances de Noël 2025-2026', '2025-12-20', '2026-01-04'),
(11, 'Vacances d\'hiver 2026 (zone C)', '2026-02-07', '2026-02-23'),
(12, 'Vacances d\'hiver 2026 (zone A)', '2026-02-14', '2026-03-02'),
(13, 'Vacances d\'hiver 2026 (zone B)', '2026-02-21', '2026-03-09'),
(14, 'Vacances de printemps 2026 (zone C)', '2026-04-04', '2026-04-20'),
(15, 'Vacances de printemps 2026 (zone A)', '2026-04-11', '2026-04-27'),
(16, 'Vacances de printemps 2026 (zone B)', '2026-04-18', '2026-05-04'),
(17, 'Vacances d\'été 2026', '2026-07-04', '2026-08-31'),
(18, 'Vacances de la Toussaint 2026', '2026-10-17', '2026-11-02'),
(19, 'Vacances de Noël 2026-2027', '2026-12-19', '2027-01-04');

-- --------------------------------------------------------

--
-- Structure de la table `se_compose`
--

DROP TABLE IF EXISTS `se_compose`;
CREATE TABLE IF NOT EXISTS `se_compose` (
  `id_prestation` int NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  `quantite_prestation` int DEFAULT NULL,
  PRIMARY KEY (`id_prestation`,`id_biens`),
  KEY `id_biens` (`id_biens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--

DROP TABLE IF EXISTS `tarifs`;
CREATE TABLE IF NOT EXISTS `tarifs` (
  `id_tarif` int NOT NULL AUTO_INCREMENT,
  `prix_semaine` decimal(10,2) NOT NULL,
  `annee` int NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  `id_saison` int NOT NULL,
  PRIMARY KEY (`id_tarif`),
  KEY `id_biens` (`id_biens`),
  KEY `id_saison` (`id_saison`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_bien`
--

DROP TABLE IF EXISTS `type_bien`;
CREATE TABLE IF NOT EXISTS `type_bien` (
  `id_typebien` int NOT NULL AUTO_INCREMENT,
  `desc_type_bien` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_typebien`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_bien`
--

INSERT INTO `type_bien` (`id_typebien`, `desc_type_bien`) VALUES
(1, 'Villa'),
(2, 'Maison'),
(3, 'Appartement'),
(4, 'Cabane'),
(5, 'Terrain');

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `id_roles` int NOT NULL,
  `id_locataire` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_roles`,`id_locataire`),
  KEY `id_locataire` (`id_locataire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `biens`
--
ALTER TABLE `biens`
  ADD CONSTRAINT `biens_ibfk_1` FOREIGN KEY (`id_TypeBien`) REFERENCES `type_bien` (`id_typebien`),
  ADD CONSTRAINT `biens_ibfk_2` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id_commune`),
  ADD CONSTRAINT `biens_ibfk_3` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE SET NULL;

--
-- Contraintes pour la table `locataire`
--
ALTER TABLE `locataire`
  ADD CONSTRAINT `locataire_ibfk_1` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id_commune`);

--
-- Contraintes pour la table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`id_tarif`) REFERENCES `tarifs` (`id_tarif`) ON DELETE CASCADE;

--
-- Contraintes pour la table `se_compose`
--
ALTER TABLE `se_compose`
  ADD CONSTRAINT `se_compose_ibfk_1` FOREIGN KEY (`id_prestation`) REFERENCES `prestation` (`id_prestation`) ON DELETE CASCADE,
  ADD CONSTRAINT `se_compose_ibfk_2` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD CONSTRAINT `tarifs_ibfk_1` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  ADD CONSTRAINT `tarifs_ibfk_2` FOREIGN KEY (`id_saison`) REFERENCES `saison` (`id_saison`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`id_roles`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
