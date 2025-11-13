<?php
$files = [
    "Goodnight/app/Views/admin/add_bien.php",
    "Goodnight/app/Views/admin/add_reservation.php",
    "Goodnight/app/Views/admin/add_role.php",
    "Goodnight/app/Views/admin/add_saison.php",
    "Goodnight/app/Views/admin/add_type_bien.php",
    "Goodnight/app/Views/admin/add_user.php",
    "Goodnight/app/Views/admin/admins.php",
    "Goodnight/app/Views/admin/biens.php",
    "Goodnight/app/Views/admin/communes.php",
    "Goodnight/app/Views/admin/edit_bien.php",
    "Goodnight/app/Views/admin/edit_reservation.php",
    "Goodnight/app/Views/admin/edit_role.php",
    "Goodnight/app/Views/admin/edit_saison.php",
    "Goodnight/app/Views/admin/edit_type_bien.php",
    "Goodnight/app/Views/admin/edit_user.php",
    "Goodnight/app/Views/admin/index.php",
    "Goodnight/app/Views/admin/reservations.php",
    "Goodnight/app/Views/admin/roles.php",
    "Goodnight/app/Views/admin/saisons.php",
    "Goodnight/app/Views/admin/types_biens.php",
    "Goodnight/app/Views/admin/users.php",
    "Goodnight/app/Views/bien/details.php",
    "Goodnight/app/Views/home/index.php",
    "Goodnight/app/Views/locataire/index.php",
    "Goodnight/app/Views/locataire/my_reservations.php",
    "Goodnight/app/Views/login/index.php",
    "Goodnight/app/Views/login/reset_password.php",
    "Goodnight/app/Views/proprietaire/add_bien.php",
    "Goodnight/app/Views/proprietaire/edit_bien.php",
    "Goodnight/app/Views/proprietaire/index.php",
    "Goodnight/app/Views/proprietaire/my_biens.php",
    "Goodnight/app/Views/proprietaire/my_reservations.php",
    "Goodnight/app/Views/register/index.php",
    "Goodnight/app/Views/reservation/index.php",
];

$js_link = '<script src="/public/js/aesthetic-effects.js"></script>';
$js_link_admin = '<script src="../../public/js/aesthetic-effects.js"></script>';

foreach ($files as $file_path) {
    $content = file_get_contents($file_path);
    $modified = false;

    // Déterminer le lien correct en fonction du chemin du fichier
    $link_to_insert = (strpos($file_path, 'Goodnight/app/Views/admin/') === 0) ? $js_link_admin : $js_link;

    // Trouver la position où insérer le lien (juste avant la fermeture de </body>)
    $body_end_pattern = '/<\/body>/i';
    if (preg_match($body_end_pattern, $content)) {
        // Insérer le nouveau lien juste avant </body>
        $content = preg_replace($body_end_pattern, $link_to_insert . "\n" . '$0', $content, 1);
        $modified = true;
    }

    if ($modified) {
        file_put_contents($file_path, $content);
        echo "Insertion du lien JS dans: " . $file_path . "\n";
    }
}

?>
