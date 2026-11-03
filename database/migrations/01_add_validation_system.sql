-- Ajout du système de validation des biens
-- Date: 08/12/2025

-- Ajouter les colonnes de validation à la table biens
ALTER TABLE `biens` 
ADD COLUMN `statut_validation` ENUM('en_attente', 'valide', 'refuse') NOT NULL DEFAULT 'en_attente' AFTER `id_locataire`,
ADD COLUMN `date_soumission` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `statut_validation`,
ADD COLUMN `date_validation` DATETIME NULL AFTER `date_soumission`,
ADD COLUMN `id_admin_validateur` INT NULL AFTER `date_validation`,
ADD COLUMN `motif_refus` TEXT NULL AFTER `id_admin_validateur`;

-- Mettre tous les biens existants comme validés (pour ne pas casser l'existant)
UPDATE `biens` SET `statut_validation` = 'valide', `date_validation` = NOW() WHERE `statut_validation` = 'en_attente';

-- Ajouter un index pour améliorer les performances des requêtes de filtrage
ALTER TABLE `biens` ADD INDEX `idx_statut_validation` (`statut_validation`);
