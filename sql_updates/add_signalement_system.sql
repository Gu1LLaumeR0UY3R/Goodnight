-- Système de signalement des biens
-- Date: 08/12/2025

-- Créer la table des signalements
CREATE TABLE IF NOT EXISTS `signalements` (
  `id_signalement` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_biens` INT UNSIGNED NOT NULL,
  `id_locataire` INT UNSIGNED NULL COMMENT 'Utilisateur qui signale (NULL si non connecté)',
  `email_signaleur` VARCHAR(255) NULL COMMENT 'Email si non connecté',
  `motif` ENUM('contenu_inapproprie', 'fausses_informations', 'photos_trompeuses', 'arnaque', 'autre') NOT NULL,
  `description` TEXT NULL,
  `statut` ENUM('en_attente', 'traite', 'rejete') NOT NULL DEFAULT 'en_attente',
  `date_signalement` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `date_traitement` DATETIME NULL,
  `id_admin_traitant` INT NULL,
  `commentaire_admin` TEXT NULL,
  PRIMARY KEY (`id_signalement`),
  KEY `idx_bien` (`id_biens`),
  KEY `idx_statut` (`statut`),
  KEY `idx_date` (`date_signalement`),
  CONSTRAINT `fk_signalement_bien` FOREIGN KEY (`id_biens`) REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  CONSTRAINT `fk_signalement_locataire` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id_locataire`) ON DELETE SET NULL,
  CONSTRAINT `fk_signalement_admin` FOREIGN KEY (`id_admin_traitant`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Ajouter un index pour compter les signalements rapidement
ALTER TABLE `signalements` ADD INDEX `idx_bien_statut` (`id_biens`, `statut`);
