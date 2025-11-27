-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 04:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goodnight`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nom_admin` varchar(50) NOT NULL,
  `prenom_admin` varchar(50) NOT NULL,
  `email_admin` varchar(100) NOT NULL,
  `mot_de_passe_admin` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biens`
--

CREATE TABLE `biens` (
  `id_biens` int(10) UNSIGNED NOT NULL,
  `designation_bien` varchar(255) DEFAULT NULL,
  `rue_biens` varchar(255) NOT NULL,
  `complement_biens` varchar(255) DEFAULT NULL,
  `superficie_biens` decimal(10,2) NOT NULL,
  `description_biens` text DEFAULT NULL,
  `animaux_biens` tinyint(1) DEFAULT 0,
  `nb_couchage` int(11) NOT NULL,
  `id_TypeBien` int(11) NOT NULL,
  `id_commune` mediumint(8) UNSIGNED NOT NULL,
  `id_locataire` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blocages`
--

CREATE TABLE `blocages` (
  `id_blocage` int(10) UNSIGNED NOT NULL,
  `id_biens` int(10) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `motif` enum('personnel','entretien','fermeture','autre') DEFAULT 'personnel',
  `commentaire` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commune`
--

CREATE TABLE `commune` (
  `id_commune` mediumint(8) UNSIGNED NOT NULL,
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
  `ville_arrondissement` smallint(5) UNSIGNED DEFAULT NULL,
  `ville_canton` varchar(4) DEFAULT NULL,
  `ville_amdi` smallint(5) UNSIGNED DEFAULT NULL,
  `ville_population_2010` mediumint(8) UNSIGNED DEFAULT NULL,
  `ville_population_1999` mediumint(8) UNSIGNED DEFAULT NULL,
  `ville_population_2012` mediumint(8) UNSIGNED DEFAULT NULL,
  `ville_densite_2010` int(11) DEFAULT NULL,
  `ville_surface` float DEFAULT NULL,
  `ville_longitude_deg` float DEFAULT NULL,
  `ville_latitude_deg` float DEFAULT NULL,
  `ville_longitude_grd` varchar(9) DEFAULT NULL,
  `ville_latitude_grd` varchar(8) DEFAULT NULL,
  `ville_longitude_dms` varchar(9) DEFAULT NULL,
  `ville_latitude_dms` varchar(8) DEFAULT NULL,
  `ville_zmin` mediumint(9) DEFAULT NULL,
  `ville_zmax` mediumint(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locataire`
--

CREATE TABLE `locataire` (
  `id_locataire` int(10) UNSIGNED NOT NULL,
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
  `id_commune` mediumint(8) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id_photo` int(11) NOT NULL,
  `nom_photo` varchar(255) DEFAULT NULL,
  `lien_photo` varchar(255) NOT NULL,
  `id_biens` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestataire`
--

CREATE TABLE `prestataire` (
  `id_prestataire` int(11) NOT NULL,
  `lib_prestataire` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_locataire` int(10) UNSIGNED NOT NULL,
  `id_biens` int(10) UNSIGNED NOT NULL,
  `id_tarif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_roles` int(11) NOT NULL,
  `nom_roles` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saison`
--

CREATE TABLE `saison` (
  `id_saison` int(11) NOT NULL,
  `lib_saison` varchar(100) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `se_compose`
--

CREATE TABLE `se_compose` (
  `id_prestataire` int(11) NOT NULL,
  `id_biens` int(10) UNSIGNED NOT NULL,
  `quantite_prestataire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tarifs`
--

CREATE TABLE `tarifs` (
  `id_tarif` int(11) NOT NULL,
  `prix_semaine` decimal(10,2) NOT NULL,
  `annee` int(11) NOT NULL,
  `id_biens` int(10) UNSIGNED NOT NULL,
  `id_saison` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_bien`
--

CREATE TABLE `type_bien` (
  `id_typebien` int(11) NOT NULL,
  `desc_type_bien` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id_roles` int(11) NOT NULL,
  `id_locataire` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `biens`
--
ALTER TABLE `biens`
  ADD PRIMARY KEY (`id_biens`),
  ADD KEY `id_TypeBien` (`id_TypeBien`),
  ADD KEY `id_commune` (`id_commune`),
  ADD KEY `idx_proprietaire` (`id_locataire`);

--
-- Indexes for table `blocages`
--
ALTER TABLE `blocages`
  ADD PRIMARY KEY (`id_blocage`),
  ADD KEY `idx_biens_dates` (`id_biens`,`date_debut`,`date_fin`);

--
-- Indexes for table `commune`
--
ALTER TABLE `commune`
  ADD PRIMARY KEY (`id_commune`),
  ADD UNIQUE KEY `ville_code_commune_2` (`ville_code_commune`),
  ADD UNIQUE KEY `ville_slug` (`ville_slug`),
  ADD KEY `ville_departement` (`ville_departement`),
  ADD KEY `ville_nom` (`ville_nom`),
  ADD KEY `ville_code_postal` (`ville_code_postal`);

--
-- Indexes for table `locataire`
--
ALTER TABLE `locataire`
  ADD PRIMARY KEY (`id_locataire`),
  ADD UNIQUE KEY `email_locataire` (`email_locataire`),
  ADD KEY `id_commune` (`id_commune`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id_photo`),
  ADD KEY `id_biens` (`id_biens`);

--
-- Indexes for table `prestataire`
--
ALTER TABLE `prestataire`
  ADD PRIMARY KEY (`id_prestataire`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_locataire` (`id_locataire`),
  ADD KEY `id_biens` (`id_biens`),
  ADD KEY `id_tarif` (`id_tarif`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_roles`);

--
-- Indexes for table `saison`
--
ALTER TABLE `saison`
  ADD PRIMARY KEY (`id_saison`);

--
-- Indexes for table `se_compose`
--
ALTER TABLE `se_compose`
  ADD PRIMARY KEY (`id_prestataire`,`id_biens`),
  ADD KEY `id_biens` (`id_biens`);

--
-- Indexes for table `tarifs`
--
ALTER TABLE `tarifs`
  ADD PRIMARY KEY (`id_tarif`),
  ADD KEY `id_biens` (`id_biens`),
  ADD KEY `id_saison` (`id_saison`);

--
-- Indexes for table `type_bien`
--
ALTER TABLE `type_bien`
  ADD PRIMARY KEY (`id_typebien`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id_roles`,`id_locataire`),
  ADD KEY `id_locataire` (`id_locataire`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biens`
--
ALTER TABLE `biens`
  MODIFY `id_biens` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blocages`
--
ALTER TABLE `blocages`
  MODIFY `id_blocage` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commune`
--
ALTER TABLE `commune`
  MODIFY `id_commune` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locataire`
--
ALTER TABLE `locataire`
  MODIFY `id_locataire` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestataire`
--
ALTER TABLE `prestataire`
  MODIFY `id_prestataire` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_roles` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saison`
--
ALTER TABLE `saison`
  MODIFY `id_saison` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tarifs`
--
ALTER TABLE `tarifs`
  MODIFY `id_tarif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type_bien`
--
ALTER TABLE `type_bien`
  MODIFY `id_typebien` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biens`
--
ALTER TABLE `biens`
  ADD CONSTRAINT `biens_ibfk_1` FOREIGN KEY (`id_TypeBien`) REFERENCES `type_bien` (`id_typebien`),
  ADD CONSTRAINT `biens_ibfk_2` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id_commune`),
  ADD CONSTRAINT `biens_ibfk_3` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE SET NULL;

--
-- Constraints for table `blocages`
--
ALTER TABLE `blocages`
  ADD CONSTRAINT `fk_blocage_bien` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE;

--
-- Constraints for table `locataire`
--
ALTER TABLE `locataire`
  ADD CONSTRAINT `locataire_ibfk_1` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id_commune`);

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`id_tarif`) REFERENCES `tarifs` (`id_tarif`);

--
-- Constraints for table `se_compose`
--
ALTER TABLE `se_compose`
  ADD CONSTRAINT `se_compose_ibfk_1` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`) ON DELETE CASCADE,
  ADD CONSTRAINT `se_compose_ibfk_2` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE;

--
-- Constraints for table `tarifs`
--
ALTER TABLE `tarifs`
  ADD CONSTRAINT `tarifs_ibfk_1` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  ADD CONSTRAINT `tarifs_ibfk_2` FOREIGN KEY (`id_saison`) REFERENCES `saison` (`id_saison`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`id_roles`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
