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
    "home/autocompleteCommunes" => ["controller" => "HomeController", "action" => "autocompleteCommunes"],
    "bien/([0-9]+)" => ["controller" => "HomeController", "action" => "details"],
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

    //Partie Réservations
    "admin/reservations" => ["controller" => "AdminController", "action" => "reservations"],
    "admin/addReservation" => ["controller" => "AdminController", "action" => "addReservation"],
    "admin/editReservation/([0-9]+)" => ["controller" => "AdminController", "action" => "editReservation"],
    "admin/deleteReservation/([0-9]+)" => ["controller" => "AdminController", "action" => "deleteReservation"],

    // Routes Propriétaire
    "proprietaire" => ["controller" => "ProprietaireController", "action" => "index"],
    "proprietaire/myBiens" => ["controller" => "ProprietaireController", "action" => "myBiens"],
    "proprietaire/addBien" => ["controller" => "ProprietaireController", "action" => "addBien"],
    "proprietaire/editBien/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "editBien"],
    "proprietaire/deleteBien/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "deleteBien"],
    "proprietaire/myReservations" => ["controller" => "ProprietaireController", "action" => "myReservations"],
    "proprietaire/deletePhoto/([0-9]+)" => ["controller" => "ProprietaireController", "action" => "deletePhoto"],

    // Routes Locataire
    "locataire" => ["controller" => "LocataireController", "action" => "index"],
    "locataire/myReservations" => ["controller" => "ReservationController", "action" => "myReservations"],
    
    "reservation/store" => ["controller" => "ReservationController", "action" => "store"],
    "reservation/cancel/([0-9]+)" => ["controller" => "ReservationController", "action" => "cancel"],
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
