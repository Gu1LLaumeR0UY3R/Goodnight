<?php

require_once __DIR__ . "/BaseController.php";

class AuthMiddleware extends BaseController {
    public static function requireLogin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["user_id"])) {
            header("Location: /login");
            exit();
        }
    }

    public static function requireRole($roles) {
        self::requireLogin(); // Assurez-vous que l'utilisateur est connecté

        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $hasRole = false;
        foreach ($roles as $role) {
            if (in_array($role, $_SESSION["user_roles"])) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            // Rediriger vers une page d'erreur ou d'accès refusé
            header("HTTP/1.0 403 Forbidden");
            echo "Accès refusé. Vous n'avez pas les permissions nécessaires.";
            exit();
        }
    }
}

?>
