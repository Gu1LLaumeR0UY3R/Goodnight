<?php
/**
 * Configuration de l application.
 * Copiez ce fichier vers config/config.php puis adaptez les valeurs
 * (config.php est ignoré par Git : ne commitez jamais vos identifiants).
 * Chaque valeur peut aussi être fournie par une variable d environnement.
 */

define("DB_HOST", getenv("DB_HOST") ?: "localhost");
define("DB_NAME", getenv("DB_NAME") ?: "goodnight");
define("DB_USER", getenv("DB_USER") ?: "root");
define("DB_PASS", getenv("DB_PASS") ?: "");

// Jeton public Mapbox (https://account.mapbox.com) : restreignez-le à votre domaine.
define("MAPBOX_TOKEN", getenv("MAPBOX_TOKEN") ?: "");

// Chemins pour les uploads de photos
define("UPLOAD_DIR", __DIR__ . "/../public/uploads/");
define("UPLOAD_URL", "/uploads/");

// Définition des rôles
define("ROLE_ADMIN", 1);
define("ROLE_PROPRIETAIRE", 2);
define("ROLE_LOCATAIRE", 3);
