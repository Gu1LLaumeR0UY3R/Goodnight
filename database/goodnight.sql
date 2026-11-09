-- =============================================================================
--  GOODNIGHT - Base de données complète (MySQL 5.7+ / 8.x, MariaDB 10.4+)
-- =============================================================================
--  Ce fichier remplace l'ancien schema.sql et les 9 migrations : il crée la base
--  et toutes les tables (structure + données de référence), sans compte utilisateur.
--
--  Usage :
--      mysql -u root -p < database/goodnight.sql
--  Données de démonstration (facultatif) :
--      mysql -u root -p goodnight < database/seed_demo.sql
--
--  Remarques :
--   * Tous les noms de tables sont en minuscules (compatible Linux).
--   * La table `commune` est créée vide : chargez-y la liste des communes de
--     France (jeu "villes de France") pour la recherche et la carte.
--   * Ré-exécutable : les tables existantes ne sont pas supprimées.
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `goodnight` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `goodnight`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- Références géographiques et catalogues
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `commune` (
  `id_commune` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ville_departement` varchar(3) DEFAULT NULL,
  `ville_slug` varchar(255) DEFAULT NULL,
  `ville_nom` varchar(45) DEFAULT NULL,
  `ville_nom_simple` varchar(45) DEFAULT NULL,
  `ville_nom_reel` varchar(45) DEFAULT NULL,
  `ville_nom_soundex` varchar(20) DEFAULT NULL,
  `ville_nom_metaphone` varchar(22) DEFAULT NULL,
  `ville_code_postal` varchar(255) DEFAULT NULL,
  `ville_commune` varchar(3) DEFAULT NULL,
  `ville_code_commune` varchar(5) NOT NULL,
  `ville_arrondissement` smallint UNSIGNED DEFAULT NULL,
  `ville_canton` varchar(4) DEFAULT NULL,
  `ville_amdi` smallint UNSIGNED DEFAULT NULL,
  `ville_population_2010` mediumint UNSIGNED DEFAULT NULL,
  `ville_population_1999` mediumint UNSIGNED DEFAULT NULL,
  `ville_population_2012` mediumint UNSIGNED DEFAULT NULL,
  `ville_densite_2010` int DEFAULT NULL,
  `ville_surface` float DEFAULT NULL,
  `ville_longitude_deg` float DEFAULT NULL,
  `ville_latitude_deg` float DEFAULT NULL,
  `ville_longitude_grd` varchar(9) DEFAULT NULL,
  `ville_latitude_grd` varchar(8) DEFAULT NULL,
  `ville_longitude_dms` varchar(9) DEFAULT NULL,
  `ville_latitude_dms` varchar(8) DEFAULT NULL,
  `ville_zmin` mediumint DEFAULT NULL,
  `ville_zmax` mediumint DEFAULT NULL,
  PRIMARY KEY (`id_commune`),
  UNIQUE KEY `ville_code_commune_2` (`ville_code_commune`),
  UNIQUE KEY `ville_slug` (`ville_slug`),
  KEY `ville_departement` (`ville_departement`),
  KEY `ville_nom` (`ville_nom`),
  KEY `ville_code_postal` (`ville_code_postal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `type_bien` (
  `id_typebien` int NOT NULL AUTO_INCREMENT,
  `desc_type_bien` varchar(100) NOT NULL,
  PRIMARY KEY (`id_typebien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `prestation` (
  `id_prestation` int NOT NULL AUTO_INCREMENT,
  `lib_prestation` varchar(255) NOT NULL,
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `saison` (
  `id_saison` int NOT NULL AUTO_INCREMENT,
  `lib_saison` varchar(100) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_saison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `roles` (
  `id_roles` int NOT NULL AUTO_INCREMENT,
  `nom_roles` varchar(255) NOT NULL,
  PRIMARY KEY (`id_roles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------------------------
-- Utilisateurs
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nom_admin` varchar(50) NOT NULL,
  `prenom_admin` varchar(50) NOT NULL,
  `email_admin` varchar(100) NOT NULL,
  `mot_de_passe_admin` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email_admin` (`email_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- `locataire` = tous les utilisateurs non admin (locataires et propriétaires)
CREATE TABLE IF NOT EXISTS `locataire` (
  `id_locataire` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_locataire` varchar(100) DEFAULT NULL,
  `prenom_locataire` varchar(100) DEFAULT NULL,
  `dateNaissance_locataire` date DEFAULT NULL,
  `email_locataire` varchar(255) NOT NULL,
  `password_locataire` varchar(255) NOT NULL,
  `tel_locataire` varchar(20) DEFAULT NULL,
  `rue_locataire` varchar(255) DEFAULT NULL,
  `complement_locataire` varchar(255) DEFAULT NULL,
  `RaisonSociale` varchar(255) DEFAULT NULL,
  `Siret` varchar(14) DEFAULT NULL,
  `id_commune` mediumint UNSIGNED DEFAULT NULL,
  `pfp_loca` varchar(500) DEFAULT NULL COMMENT 'Photo de profil (ex: /pfp/user_1_xxx.png)',
  `frames_unlocked` tinyint(1) DEFAULT '0' COMMENT 'Easter egg des cadres découvert',
  `cadre_profil` varchar(255) DEFAULT NULL COMMENT 'Chemin du cadre de profil équipé',
  PRIMARY KEY (`id_locataire`),
  UNIQUE KEY `email_locataire` (`email_locataire`),
  KEY `id_commune` (`id_commune`),
  CONSTRAINT `locataire_ibfk_1` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id_commune`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `user_role` (
  `id_roles` int NOT NULL,
  `id_locataire` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_roles`,`id_locataire`),
  KEY `id_locataire` (`id_locataire`),
  CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`id_roles`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE,
  CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------------------------
-- Biens, photos, prestations, tarifs, blocages
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `biens` (
  `id_biens` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `designation_bien` varchar(255) DEFAULT NULL,
  `rue_biens` varchar(255) NOT NULL,
  `complement_biens` varchar(255) DEFAULT NULL,
  `superficie_biens` decimal(10,2) NOT NULL,
  `description_biens` text,
  `animaux_biens` tinyint(1) DEFAULT '0',
  `nb_couchage` int NOT NULL,
  `id_TypeBien` int NOT NULL,
  `id_commune` mediumint UNSIGNED NOT NULL,
  `id_locataire` int UNSIGNED DEFAULT NULL COMMENT 'Propriétaire du bien',
  `statut_validation` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
  `date_soumission` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_validation` datetime DEFAULT NULL,
  `id_admin_validateur` int DEFAULT NULL,
  `motif_refus` text,
  PRIMARY KEY (`id_biens`),
  KEY `id_TypeBien` (`id_TypeBien`),
  KEY `id_commune` (`id_commune`),
  KEY `idx_proprietaire` (`id_locataire`),
  KEY `idx_statut_validation` (`statut_validation`),
  CONSTRAINT `biens_ibfk_1` FOREIGN KEY (`id_TypeBien`) REFERENCES `type_bien` (`id_typebien`),
  CONSTRAINT `biens_ibfk_2` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id_commune`),
  CONSTRAINT `biens_ibfk_3` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE SET NULL,
  CONSTRAINT `biens_ibfk_4` FOREIGN KEY (`id_admin_validateur`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `photos` (
  `id_photo` int NOT NULL AUTO_INCREMENT,
  `nom_photo` varchar(255) DEFAULT NULL,
  `lien_photo` varchar(255) NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `id_biens` (`id_biens`),
  CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `se_compose` (
  `id_prestation` int NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  `quantite_prestation` int DEFAULT NULL,
  PRIMARY KEY (`id_prestation`,`id_biens`),
  KEY `id_biens` (`id_biens`),
  CONSTRAINT `se_compose_ibfk_1` FOREIGN KEY (`id_prestation`) REFERENCES `prestation` (`id_prestation`) ON DELETE CASCADE,
  CONSTRAINT `se_compose_ibfk_2` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tarifs` (
  `id_tarif` int NOT NULL AUTO_INCREMENT,
  `prix_semaine` decimal(10,2) NOT NULL,
  `annee` int NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  `id_saison` int NOT NULL,
  PRIMARY KEY (`id_tarif`),
  KEY `id_biens` (`id_biens`),
  KEY `id_saison` (`id_saison`),
  CONSTRAINT `tarifs_ibfk_1` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  CONSTRAINT `tarifs_ibfk_2` FOREIGN KEY (`id_saison`) REFERENCES `saison` (`id_saison`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Correction : l'ancienne table n'avait ni clé primaire ni auto-incrément
CREATE TABLE IF NOT EXISTS `blocages` (
  `id_blocage` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_biens` int UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `motif` enum('personnel','entretien','fermeture','autre') DEFAULT 'personnel',
  `commentaire` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_blocage`),
  KEY `idx_blocage_bien` (`id_biens`),
  CONSTRAINT `blocages_ibfk_1` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------------------------
-- Réservations
-- -----------------------------------------------------------------------------

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
  KEY `id_tarif` (`id_tarif`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE,
  CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`id_tarif`) REFERENCES `tarifs` (`id_tarif`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------------------------
-- Avis, likes, favoris, signalements, notifications
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `commentaires` (
  `id_commentaire` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_biens` int UNSIGNED NOT NULL,
  `id_locataire` int UNSIGNED NOT NULL,
  `note` tinyint UNSIGNED DEFAULT NULL COMMENT 'Note de 1 à 5 étoiles',
  `titre` varchar(255) DEFAULT NULL,
  `contenu` text NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `statut` enum('publie','en_attente','rejete') NOT NULL DEFAULT 'publie',
  `signale` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_commentaire`),
  UNIQUE KEY `idx_unique_commentaire` (`id_biens`,`id_locataire`),
  KEY `idx_locataire` (`id_locataire`),
  KEY `idx_statut` (`statut`),
  KEY `idx_date` (`date_creation`),
  KEY `idx_bien_note` (`id_biens`,`note`),
  CONSTRAINT `fk_commentaire_bien` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  CONSTRAINT `fk_commentaire_locataire` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `commentaire_likes` (
  `id_like` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_commentaire` int UNSIGNED NOT NULL,
  `id_locataire` int UNSIGNED NOT NULL,
  `date_like` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_like`),
  UNIQUE KEY `unique_like` (`id_commentaire`,`id_locataire`),
  KEY `idx_locataire` (`id_locataire`),
  CONSTRAINT `fk_like_commentaire` FOREIGN KEY (`id_commentaire`) REFERENCES `commentaires` (`id_commentaire`) ON DELETE CASCADE,
  CONSTRAINT `fk_like_locataire` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `favoris` (
  `id_favori` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_locataire` int UNSIGNED NOT NULL,
  `id_biens` int UNSIGNED NOT NULL,
  `date_ajout` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_favori`),
  UNIQUE KEY `unique_favori` (`id_locataire`,`id_biens`),
  KEY `idx_biens_favoris` (`id_biens`),
  CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE,
  CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `signalements` (
  `id_signalement` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_biens` int UNSIGNED NOT NULL,
  `id_locataire` int UNSIGNED DEFAULT NULL COMMENT 'Utilisateur qui signale (NULL si non connecté)',
  `email_signaleur` varchar(255) DEFAULT NULL COMMENT 'Email si non connecté',
  `motif` enum('contenu_inapproprie','fausses_informations','photos_trompeuses','arnaque','autre') NOT NULL,
  `description` text,
  `statut` enum('en_attente','traite','rejete') NOT NULL DEFAULT 'en_attente',
  `date_signalement` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_traitement` datetime DEFAULT NULL,
  `id_admin_traitant` int DEFAULT NULL,
  `commentaire_admin` text,
  PRIMARY KEY (`id_signalement`),
  KEY `idx_bien` (`id_biens`),
  KEY `idx_statut` (`statut`),
  KEY `idx_date` (`date_signalement`),
  KEY `idx_bien_statut` (`id_biens`,`statut`),
  KEY `fk_signalement_locataire` (`id_locataire`),
  KEY `fk_signalement_admin` (`id_admin_traitant`),
  CONSTRAINT `fk_signalement_bien` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  CONSTRAINT `fk_signalement_locataire` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE SET NULL,
  CONSTRAINT `fk_signalement_admin` FOREIGN KEY (`id_admin_traitant`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- `user_id` désigne un `locataire.id_locataire` (propriétaire ou locataire)
CREATE TABLE IF NOT EXISTS `notifications` (
  `id_notification` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `type` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notification`),
  KEY `idx_user_read_created` (`user_id`,`is_read`,`created_at`),
  KEY `idx_user_created` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------------------------
-- Cadres de profil (easter eggs) gérés par l'administrateur
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `cadres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `chemin_fichier` varchar(255) DEFAULT NULL,
  `description` text,
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`),
  UNIQUE KEY `chemin_fichier` (`chemin_fichier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------------------------------
-- Données de référence (INSERT IGNORE : sans effet si déjà présentes)
-- -----------------------------------------------------------------------------

INSERT IGNORE INTO `roles` (`id_roles`, `nom_roles`) VALUES
(1, 'Propriétaire'),
(2, 'Locataire');

INSERT IGNORE INTO `type_bien` (`id_typebien`, `desc_type_bien`) VALUES
(1, 'Villa'),
(2, 'Maison'),
(3, 'Appartement'),
(4, 'Cabane'),
(5, 'Terrain');

INSERT IGNORE INTO `prestation` (`id_prestation`, `lib_prestation`) VALUES
(1, 'WiFi'), (2, 'Salle de bain'), (3, 'Salle à manger'), (4, 'Cuisine équipée'),
(5, 'Chambre'), (6, 'Salon'), (7, 'Terrasse'), (8, 'Jardin'), (9, 'Piscine'),
(10, 'Parking'), (11, 'Garage'), (12, 'Climatisation'), (13, 'Chauffage'),
(14, 'Télévision'), (15, 'Lave-linge'), (16, 'Lave-vaisselle'), (17, 'Four'),
(18, 'Micro-ondes'), (19, 'Réfrigérateur'), (20, 'Congélateur'),
(21, 'Machine à café'), (22, 'Barbecue'), (23, 'Balcon'), (24, 'Cheminée'),
(25, 'Jacuzzi'), (26, 'Sauna'), (27, 'Salle de sport'), (28, 'Bureau'),
(29, 'WC séparé'), (30, 'Baignoire');

INSERT IGNORE INTO `saison` (`id_saison`, `lib_saison`, `date_debut`, `date_fin`) VALUES
(1, 'Moyenne saison printemps', '2025-03-01', '2025-05-31'),
(2, 'Haute saison été', '2025-06-01', '2025-08-31'),
(3, 'Basse saison', '2025-09-01', '2025-11-30'),
(4, 'Moyenne saison hiver', '2025-12-01', '2026-02-28'),
(5, 'Moyenne saison printemps', '2026-03-01', '2026-06-30'),
(6, 'Haute saison été', '2026-07-01', '2026-08-31'),
(10, 'Vacances de Noël 2025-2026', '2025-12-20', '2026-01-04'),
(11, 'Vacances d''hiver 2026 (zone C)', '2026-02-07', '2026-02-23'),
(12, 'Vacances d''hiver 2026 (zone A)', '2026-02-14', '2026-03-02'),
(13, 'Vacances d''hiver 2026 (zone B)', '2026-02-21', '2026-03-09'),
(14, 'Vacances de printemps 2026 (zone C)', '2026-04-04', '2026-04-20'),
(15, 'Vacances de printemps 2026 (zone A)', '2026-04-11', '2026-04-27'),
(16, 'Vacances de printemps 2026 (zone B)', '2026-04-18', '2026-05-04'),
(17, 'Vacances d''été 2026', '2026-07-04', '2026-08-31'),
(18, 'Vacances de la Toussaint 2026', '2026-10-17', '2026-11-02'),
(19, 'Vacances de Noël 2026-2027', '2026-12-19', '2027-01-04');

INSERT IGNORE INTO `cadres` (`id`, `nom`, `chemin_fichier`, `description`) VALUES
(1, 'Par défaut', NULL, 'Pas de cadre, affichage normal');
