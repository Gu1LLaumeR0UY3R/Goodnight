<?php
// AuthMiddleware.php

class AuthMiddleware
{
    /**
     * Démarre la session si nécessaire.
     */
    private static function ensureSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * Redirige vers /login si non.
     */
    public static function requireLogin()
    {
        self::ensureSession();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Vérifie que l'utilisateur a un des rôles autorisés.
     * Utilise le champ 'role' dans la session (ex: 'Locataire', 'Propriétaire', 'Administrateur')
     *
     * @param string|array $roles Rôle(s) autorisé(s)
     */
    public static function checkUserRole($roles)
    {
        self::requireLogin();

        // Normaliser en tableau
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $userRole = $_SESSION['role'] ?? null;

        // Autoriser les admins partout
        if ($userRole === 'Administrateur') {
            return;
        }

        if (!in_array($userRole, $roles, true)) {
            $_SESSION['error_message'] = "Accès refusé. Vous n'avez pas les permissions nécessaires.";
            header('Location: /home');
            exit;
        }
    }

    public static function requireRole($roles) {
        self::requireLogin(); // Assurez-vous que l'utilisateur est connecté

        // S'assurer que $roles est toujours un tableau
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        // Vérifier si l'utilisateur est un administrateur via la session
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true && in_array("Administrateur", $roles)) {
            return; // L'administrateur a accès à tout
        }

        $hasRole = false;
        foreach ($roles as $role) {
            if (isset($_SESSION["user_roles"]) && is_array($_SESSION["user_roles"]) && in_array($role, $_SESSION["user_roles"])) {
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
