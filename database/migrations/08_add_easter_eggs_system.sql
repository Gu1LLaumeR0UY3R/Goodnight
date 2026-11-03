-- ========================================
-- SYSTÈME EASTER EGGS - CADRES DE PROFIL
-- ========================================
-- Ce fichier SQL ajoute simplement la table des cadres et la relation avec les locataires

-- ========================================
-- 1. TABLE CADRES_PROFIL - Cadres PNG pour avatars
-- ========================================

DROP TABLE IF EXISTS `cadres_profil`;
CREATE TABLE IF NOT EXISTS `cadres_profil` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nom du cadre',
  `description` TEXT COLLATE utf8mb4_general_ci COMMENT 'Description du cadre',
  `chemin_fichier` VARCHAR(255) COLLATE utf8mb4_general_ci COMMENT 'Chemin vers le fichier PNG',
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cadres de profil pour les avatars';

-- ========================================
-- 2. Mise à jour de la table LOCATAIRE
-- ========================================

-- Ajouter colonne pour le cadre actif avec clé étrangère
ALTER TABLE `locataire` 
  ADD COLUMN `id_cadre_actif` INT UNSIGNED DEFAULT NULL COMMENT 'ID du cadre actuellement équipé',
  ADD COLUMN `frames_unlocked` BOOLEAN DEFAULT 0 COMMENT 'Easter Egg des cadres découvert';

-- Ajouter la clé étrangère vers cadres_profil
ALTER TABLE `locataire` 
  ADD CONSTRAINT `fk_locataire_cadre` 
  FOREIGN KEY (`id_cadre_actif`) 
  REFERENCES `cadres_profil`(`id`) 
  ON DELETE SET NULL;

-- ========================================
-- 3. Données initiales - Cadres de profil
-- ========================================

INSERT INTO `cadres_profil` (`nom`, `description`, `chemin_fichier`) 
VALUES
  ('Cadre Bronze', 'Un cadre simple en bronze pour commencer votre collection', '/public/cadre/frames/bronze_frame.png'),
  ('Cadre Argent', 'Un cadre élégant en argent pour les collectionneurs', '/public/cadre/frames/silver_frame.png'),
  ('Cadre Or', 'Un cadre prestigieux en or pour les plus déterminés', '/public/cadre/frames/gold_frame.png'),
  ('Cadre Arc-en-ciel', 'Un cadre magique aux couleurs de l\'arc-en-ciel', '/public/cadre/frames/rainbow_frame.png'),
  ('Cadre Néon', 'Un cadre futuriste avec effet néon', '/public/cadre/frames/neon_frame.png'),
  ('Cadre Vintage', 'Un cadre au style rétro pour les nostalgiques', '/public/cadre/frames/vintage_frame.png'),
  ('Cadre Cristal', 'Un cadre transparent comme du cristal', '/public/cadre/frames/crystal_frame.png'),
  ('Cadre Feu', 'Un cadre enflammé pour les passionnés', '/public/cadre/frames/fire_frame.png');

-- ========================================
-- FIN DU SCRIPT SQL
-- ========================================

COMMIT;
