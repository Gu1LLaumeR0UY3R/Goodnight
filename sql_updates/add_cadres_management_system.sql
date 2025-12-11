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

-- Insérer les cadres par défaut
INSERT INTO `cadres` (`nom`, `chemin_fichier`, `description`) VALUES
('Par défaut', NULL, 'Pas de cadre, affichage normal'),
('Or Préstigieux', '/cadre/images/gold.png', 'Un élégant cadre doré avec effet de prestige'),
('Argent Raffiné', '/cadre/images/silver.png', 'Un cadre argenté à l\'éclat subtil'),
('Bronze Antique', '/cadre/images/bronze.png', 'Un cadre de bronze avec une teinte antique'),
('Arc-en-ciel', '/cadre/images/rainbow.png', 'Un cadre aux couleurs arc-en-ciel vibrant'),
('Glacier Bleu', '/cadre/images/glacier.png', 'Un cadre glacé aux tons bleus froids'),
('Rose Flamant', '/cadre/images/pink.png', 'Un cadre rose doux et élégant'),
('Émeraude', '/cadre/images/emerald.png', 'Un cadre vert émeraude profond'),
('Violet Mystique', '/cadre/images/mystique.png', 'Un cadre violet mystérieux et enchanteur');
