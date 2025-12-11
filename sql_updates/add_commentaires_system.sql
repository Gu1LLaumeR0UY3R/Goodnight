-- Système de commentaires et d'avis sur les biens
-- Date: 11/12/2025

-- Créer la table des commentaires
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id_commentaire` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_biens` INT UNSIGNED NOT NULL,
  `id_locataire` INT UNSIGNED NOT NULL,
  `note` TINYINT UNSIGNED NULL COMMENT 'Note de 1 à 5 étoiles',
  `titre` VARCHAR(255) NULL,
  `contenu` TEXT NOT NULL,
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `date_modification` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  `statut` ENUM('publie', 'en_attente', 'rejete') NOT NULL DEFAULT 'publie',
  `signale` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id_commentaire`),
  KEY `idx_bien` (`id_biens`),
  KEY `idx_locataire` (`id_locataire`),
  KEY `idx_statut` (`statut`),
  KEY `idx_date` (`date_creation`),
  CONSTRAINT `fk_commentaire_bien` FOREIGN KEY (`id_biens`) 
    REFERENCES `biens` (`id_biens`) ON DELETE CASCADE,
  CONSTRAINT `fk_commentaire_locataire` FOREIGN KEY (`id_locataire`) 
    REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Index pour calculer la note moyenne rapidement
CREATE INDEX `idx_bien_note` ON `commentaires`(`id_biens`, `note`);

-- Index composé pour éviter les doublons (un commentaire par utilisateur par bien)
CREATE UNIQUE INDEX `idx_unique_commentaire` ON `commentaires`(`id_biens`, `id_locataire`);
