-- Ajouter les colonnes de cadre de profil dans la table locataire

-- Colonne pour tracker si les frames sont débloquées
ALTER TABLE `locataire` ADD COLUMN `frames_unlocked` BOOLEAN DEFAULT 0;

