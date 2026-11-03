-- Système de likes pour les commentaires
-- Date: 11/12/2025

-- Créer la table des likes de commentaires
CREATE TABLE IF NOT EXISTS `commentaire_likes` (
  `id_like` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_commentaire` INT UNSIGNED NOT NULL,
  `id_locataire` INT UNSIGNED NOT NULL,
  `date_like` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_like`),
  UNIQUE KEY `unique_like` (`id_commentaire`, `id_locataire`),
  KEY `idx_commentaire` (`id_commentaire`),
  KEY `idx_locataire` (`id_locataire`),
  CONSTRAINT `fk_like_commentaire` FOREIGN KEY (`id_commentaire`) 
    REFERENCES `commentaires` (`id_commentaire`) ON DELETE CASCADE,
  CONSTRAINT `fk_like_locataire` FOREIGN KEY (`id_locataire`) 
    REFERENCES `locataire` (`id_locataire`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Index pour compter les likes rapidement
CREATE INDEX `idx_commentaire_count` ON `commentaire_likes`(`id_commentaire`);
