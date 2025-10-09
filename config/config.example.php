<?php

/**
 * Configuration de la base de données
 * 
 * IMPORTANT : Copiez ce fichier en "config.php" et modifiez les valeurs selon votre environnement
 */

// Configuration de la base de données
define("DB_HOST", "localhost");           // Hôte de la base de données (généralement "localhost")
define("DB_NAME", "Location");            // Nom de la base de données
define("DB_USER", "votre_utilisateur");   // Nom d'utilisateur MySQL
define("DB_PASS", "votre_mot_de_passe");  // Mot de passe MySQL

// Chemins pour les uploads de photos
define("UPLOAD_DIR", __DIR__ . "/../public/uploads/");
define("UPLOAD_URL", "/uploads/");

// Définition des rôles
define("ROLE_ADMIN", 1);
define("ROLE_PROPRIETAIRE", 2);
define("ROLE_LOCATAIRE", 3);

?>
