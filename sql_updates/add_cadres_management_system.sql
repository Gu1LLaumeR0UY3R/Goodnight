-- Table pour gérer les cadres disponibles
-- Permet l'ajout, la modification et la suppression de cadres
-- Avec option de modération pour les cadres inappropriés

CREATE TABLE IF NOT EXISTS `cadres` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL UNIQUE,
    `chemin_fichier` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT,
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `date_modification` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;