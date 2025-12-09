-- Créer la table pour les favoris
CREATE TABLE IF NOT EXISTS favoris (
    id_favori INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_locataire INT UNSIGNED NOT NULL,
    id_biens INT UNSIGNED NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_locataire) REFERENCES locataire(id_locataire) ON DELETE CASCADE,
    FOREIGN KEY (id_biens) REFERENCES biens(id_biens) ON DELETE CASCADE,
    
    -- Unique constraint pour éviter les doublons
    UNIQUE KEY unique_favori (id_locataire, id_biens)
);

-- Créer un index pour les recherches rapides
CREATE INDEX idx_locataire_favoris ON favoris(id_locataire);
CREATE INDEX idx_biens_favoris ON favoris(id_biens);
