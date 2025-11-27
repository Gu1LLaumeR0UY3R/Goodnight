-- Migration: Ajout du champ pfp_loca pour les photos de profil
-- Date: 2025-11-27

ALTER TABLE `locataire` 
ADD COLUMN `pfp_loca` VARCHAR(255) NULL DEFAULT NULL AFTER `id_commune`;

-- Commentaire: Ce champ stockera le chemin relatif vers la photo de profil
-- Exemple: /pfp/user_123_1701091234.jpg
