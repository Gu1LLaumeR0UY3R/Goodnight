<?php
session_start();


require_once __DIR__ . "/../config/config.php";

// Autoload des classes (à améliorer avec un vrai autoloader PSR-4)
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . "/../app/Models/",
        __DIR__ . "/../app/Controllers/",

        __DIR__ . "/../lib/"
    ];
    foreach ($paths as $path) {
        $file = $path . $className . ".php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$requestUri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Définition des routes avec support des regex pour les paramètres
$routes = [
    "" => ["controller" => "HomeController", "action" => "index"],
    "home" => ["controller" => "HomeController", "action" => "index"],
    "home/search" => ["controller" => "HomeController", "action" => "search"],
    "home/map" => ["controller" => "HomeController", "action" => "map"],
    "home/autocompleteCommunes" => ["controller" => "HomeController", "action" => "autocompleteCommunes"],
    "bien/([0-9]+)" => ["controller" => "HomeController", "action" => "details"],
    "signaler/([0-9]+)" => ["controller" => "HomeController", "action" => "signaler"],
    "register" => ["controller" => "RegisterController", "action" => "index"],
    "register/process" => ["controller" => "RegisterController", "action" => "register"],
    
    "login" => ["controller" => "LoginController", "action" => "index"],
    "login/process" => ["controller" => "LoginController", "action" => "login"],
    "login/reset" => ["controller" => "LoginController", "action" => "showResetForm"],
    "login/reset-password/request" => ["controller" => "LoginController", "action" => "requestPasswordReset"],
    "login/reset-password/update" => ["controller" => "LoginController", "action" => "updatePassword"],
    "logout" => ["controller" => "LoginController", "action" => "logout"],
    
    // Routes Administrateur
    "admin" => ["controller" => "AdminController", "action" => "index"],
    "admin/admins" => ["controller" => "AdminController", "action" => "admins"],
    "admin/addAdmin" => ["controller" => "AdminController", "action" => "addAdmin"],
    "admin/editAdmin/([0-9]+)" => ["controller" => "AdminController", "action" => "editAdmin"],
    "admin/deleteAdmin/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteAdmin"],
    "admin/roles" => ["controller" => "AdminController", "action" => "roles"],
    "admin/addRole" => ["controller" => "AdminController", "action" => "addRole"],
    "admin/editRole/([0-9]+)" => ["controller" => "AdminController", "action" => "editRole"],
    "admin/deleteRole/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteRole"],
    "admin/communes" => ["controller" => "AdminController", "action" => "communes"],
    "admin/typesBiens" => ["controller" => "AdminController", "action" => "typesBiens"],
    "admin/addTypeBien" => ["controller" => "AdminController", "action" => "addTypeBien"],
    "admin/editTypeBien/([0-9]+)" => ["controller" => "AdminController", "action" => "editTypeBien"],
    "admin/deleteTypeBien/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteTypeBien"],

    // Partie Saisons
    "admin/saisons" => ["controller" => "AdminController", "action" => "saisons"],
    "admin/addSaison" => ["controller" => "AdminController", "action" => "addSaison"],
    "admin/editSaison/([0-9]+)" => ["controller" => "AdminController", "action" => "editSaison"],
    "admin/deleteSaison/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteSaison"],

    // Partie Biens
    "admin/biens" => ["controller" => "AdminController", "action" => "biens"],
    "admin/addBien" => ["controller" => "AdminController", "action" => "addBien"],
    "admin/editBien/([0-9]+)" => ["controller" => "AdminController", "action" => "editBien"],
    "admin/deleteBien/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteBien"],

    //Partie Utilisateurs
    "admin/users" => ["controller" => "AdminController", "action" => "users"],
    "admin/addUser" => ["controller" => "AdminController", "action" => "addUser"],
    "admin/editUser/([0-9]+)" => ["controller" => "AdminController", "action" => "editUser"],
    "admin/deleteUser/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteUser"],

    //Partie Validation des biens
    "admin/validations" => ["controller" => "AdminController", "action" => "validations"],
    "admin/validerBien/([0-9]+)" => ["controller" => "AdminController", "action" => "validerBien"],
    "admin/refuserBien/([0-9]+)" => ["controller" => "AdminController", "action" => "refuserBien"],

    //Partie Signalements
    "admin/signalements" => ["controller" => "AdminController", "action" => "signalements"],
    "admin/traiterSignalement/([0-9]+)" => ["controller" => "AdminController", "action" => "traiterSignalement"],
    "admin/rejeterSignalement/([0-9]+)" => ["controller" => "AdminController", "action" => "rejeterSignalement"],
    "admin/updateStatutBien/([0-9]+)" => ["controller" => "AdminController", "action" => "updateStatutBien"],

    //Partie Commentaires signalés
    "admin/commentaires-signales" => ["controller" => "AdminController", "action" => "commentairesSignales"],
    "admin/commentaire/approuver/([0-9]+)" => ["controller" => "AdminController", "action" => "approuverCommentaire"],
    "admin/commentaire/rejeter/([0-9]+)" => ["controller" => "AdminController", "action" => "rejeterCommentaire"],

    //Partie Réservations
    "admin/reservations" => ["controller" => "AdminController", "action" => "reservations"],
    "admin/addReservation" => ["controller" => "AdminController", "action" => "addReservation"],
    "admin/editReservation/([0-9]+)" => ["controller" => "AdminController", "action" => "editReservation"],
    "admin/deleteReservation/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteReservation"],

    // Routes Propriétaire
    "proprietaire" => ["controller" => "ProprietaireController", "action" => "index"],
    "proprietaire/myBiens" => ["controller" => "ProprietaireController", "action" => "myBiens"],
    "proprietaire/addBien" => ["controller" => "ProprietaireController", "action" => "addBien"],
    "proprietaire/viewBien/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "viewBien"],
    "proprietaire/editBien/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "editBien"],
    "proprietaire/deleteBien/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "deleteBien"],
    "proprietaire/managePrestations/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "managePrestations"],
    "proprietaire/prestationsList" => ["controller" => "ProprietaireController", "action" => "prestationsList"],
    "proprietaire/myReservations" => ["controller" => "ProprietaireController", "action" => "myReservations"],
    "proprietaire/deletePhoto/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "deletePhoto"],
    "proprietaire/calendar/events" => ["controller" => "ProprietaireController", "action" => "calendarEvents"],
    "proprietaire/calendar/block" => ["controller" => "ProprietaireController", "action" => "calendarBlock"],
    "proprietaire/calendar/unblock" => ["controller" => "ProprietaireController", "action" => "calendarUnblock"],
    "proprietaire/stats" => ["controller" => "ProprietaireStatsController", "action" => "getStats"],
    "proprietaire/stats/advanced" => ["controller" => "ProprietaireStatsController", "action" => "getAdvancedStats"],

    // Routes Locataire
    "locataire" => ["controller" => "LocataireController", "action" => "index"],
    "locataire/myReservations" => ["controller" => "ReservationController", "action" => "myReservations"],
    "api/favoris" => ["controller" => "ProfileController", "action" => "manageFavorites"],
    "api/get-user-favorites" => ["controller" => "ProfileController", "action" => "getUserFavorites"],
    
    // Routes Commentaires
    "commentaire/add" => ["controller" => "CommentaireController", "action" => "add"],
    "commentaire/edit/([0-9]+)" => ["controller" => "CommentaireController", "action" => "edit"],
    "commentaire/delete/([0-9]+)" => ["controller" => "CommentaireController", "action" => "delete"],
    "commentaire/signaler/([0-9]+)" => ["controller" => "CommentaireController", "action" => "signaler"],
    "commentaire/get/([0-9]+)" => ["controller" => "CommentaireController", "action" => "getCommentaires"],
    "commentaire/like/([0-9]+)" => ["controller" => "CommentaireController", "action" => "toggleLike"],
    "commentaire/top3/([0-9]+)" => ["controller" => "CommentaireController", "action" => "getTop3"],
    
    "reservation/store" => ["controller" => "ReservationController", "action" => "store"],
    "reservation/cancel/([0-9]+)" => ["controller" => "ReservationController", "action" => "cancel"],

    // Routes Profil
    "profile" => ["controller" => "ProfileController", "action" => "index"],
    "profile/uploadProfilePicture" => ["controller" => "ProfileController", "action" => "uploadProfilePicture"],
    "profile/deleteProfilePicture" => ["controller" => "ProfileController", "action" => "deleteProfilePicture"],
    "profile/updateProfile" => ["controller" => "ProfileController", "action" => "updateProfile"],
    "profile/updateFrame" => ["controller" => "ProfileController", "action" => "updateFrame"],
    "profile/cadre" => ["controller" => "ProfileController", "action" => "unlockFrames"],
    
    // Route Favoris (accessible à tous les utilisateurs connectés)
    "favoris" => ["controller" => "ProfileController", "action" => "myFavorites"],
    "locataire/myFavorites" => ["controller" => "ProfileController", "action" => "myFavorites"], // Redirection ancienne route
    
    // Routes Cadres (Admin)
    "admin/cadres" => ["controller" => "CadreController", "action" => "index"],
    "admin/cadres/create" => ["controller" => "CadreController", "action" => "create"],
    "cadre/store" => ["controller" => "CadreController", "action" => "store"],
    "cadre/delete" => ["controller" => "CadreController", "action" => "delete"],

    // Routes Easter Eggs (Admin)
    "admin/easter-eggs" => ["controller" => "EasterEggController", "action" => "index"],

    // API Notifications
    "api/notifications" => ["controller" => "NotificationController", "action" => "list"],
    "api/notifications/count" => ["controller" => "NotificationController", "action" => "count"],
    "api/notifications/mark-read/([0-9]+)" => ["controller" => "NotificationController", "action" => "markRead"],
    "api/notifications/mark-all-read" => ["controller" => "NotificationController", "action" => "markAllRead"],
];

$matchedRoute = null;
$params = [];

foreach ($routes as $routePath => $routeConfig) {
    // Convertir les chemins de route en regex
    $pattern = "#^" . $routePath . "$#";
    if (preg_match($pattern, $requestUri, $matches)) {
        $matchedRoute = $routeConfig;
        array_shift($matches); // Supprimer la correspondance complète
        $params = $matches; // Les captures sont les paramètres
        break;
    }
}

if ($matchedRoute) {
    $controllerName = $matchedRoute["controller"];
    $actionName = $matchedRoute["action"];

    $controller = new $controllerName();
    // Appeler l\'action avec les paramètres capturés
    call_user_func_array([$controller, $actionName], $params);
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}

?>
