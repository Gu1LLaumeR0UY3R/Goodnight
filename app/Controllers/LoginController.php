<?php
// LoginController.php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/AdminModel.php";

class LoginController extends BaseController
{
    private $userModel;
    private $adminModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        // Si déjà connecté → rediriger selon rôle
        if (isset($_SESSION["user_id"])) {
            $this->redirectByRole();
            return;
        }

        $old_email = $_SESSION["old_email"] ?? "";
        unset($_SESSION["old_email"]);
        $this->render("login/index", ["old_email" => $old_email]);
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/login");
            return;
        }

        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        if (empty($email) || empty($password)) {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            $_SESSION["old_email"] = $email;
            $this->redirect("/login");
            return;
        }

        // 1. Vérifier si c'est un admin
        $admin = $this->adminModel->getAdminByEmail($email);
        if ($admin && password_verify($password, $admin["mot_de_passe_admin"]) && $admin["is_admin"]) {
            $_SESSION["user_id"] = $admin["id_admin"];
            $_SESSION["user_email"] = $admin["email_admin"];
            $_SESSION["user_nom"] = $admin["nom_admin"];
            $_SESSION["user_prenom"] = $admin["prenom_admin"];
            $_SESSION["is_admin"] = true;
            $_SESSION["role"] = "Administrateur"; // Rôle principal
            $_SESSION["user_roles"] = ["Administrateur"];

            $this->redirect("/admin");
            return;
        }

        // 2. Vérifier si c'est un utilisateur normal (locataire)
        $user = $this->userModel->getUserByEmail($email);
        if ($user && password_verify($password, $user["mot_de_passe_locataire"] ?? $user["password_locataire"] ?? '')) {
            // Connexion réussie
            $_SESSION["user_id"] = $user["id_locataire"];
            $_SESSION["user_email"] = $user["email_locataire"];
            $_SESSION["user_nom"] = $user["nom_locataire"];
            $_SESSION["user_prenom"] = $user["prenom_locataire"];
            $_SESSION["SESSION_USERS"] = $user;

            // Récupérer les rôles
            $roles = $this->userModel->getUserRoles($user["id_locataire"]);
            $roleNames = array_column($roles, 'nom_roles');

            $_SESSION["user_roles"] = $roleNames;
            $_SESSION["role"] = $roleNames[0] ?? null; // RÔLE PRINCIPAL (CRUCIAL)

            // Rediriger selon le rôle
            $this->redirectByRole();
            return;
        }

        // Échec de connexion
        $_SESSION["error"] = "Email ou mot de passe incorrect.";
        $_SESSION["old_email"] = $email;
        $this->redirect("/login");
    }

    public function logout()
    {
        session_destroy();
        $this->redirect("/home");
    }

    private function redirectByRole()
    {
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
            $this->redirect("/admin");
        } elseif (isset($_SESSION["user_roles"]) && is_array($_SESSION["user_roles"])) {
            if (in_array("Propriétaire", $_SESSION["user_roles"])) {
                $this->redirect("/proprietaire");
            } elseif (in_array("Locataire", $_SESSION["user_roles"])) {
                $this->redirect("/locataire");
            } else {
                $this->redirect("/home");
            }
        } else {
            $this->redirect("/home");
        }
    }

    // --- Réinitialisation mot de passe ---
    public function showResetForm()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? "";
            if ($this->userModel->emailExists($email)) {
                $this->render("login/reset_password", ["email" => $email]);
            } else {
                $_SESSION["error"] = "Cette adresse email n'existe pas.";
                $this->redirect("/login/reset");
            }
        } else {
            $this->render("login/reset_password");
        }
    }

    public function updatePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/login/reset");
            return;
        }

        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";
        $password_confirm = $_POST["password_confirm"] ?? "";

        if (empty($email) || empty($password) || empty($password_confirm)) {
            $_SESSION["error"] = "Tous les champs sont obligatoires.";
            $this->redirect("/login/reset");
            return;
        }

        if ($password !== $password_confirm) {
            $_SESSION["error"] = "Les mots de passe ne correspondent pas.";
            $this->redirect("/login/reset");
            return;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $_SESSION["error"] = "Mot de passe trop faible (8+ caractères, maj, min, chiffre, spécial).";
            $this->redirect("/login/reset");
            return;
        }

        if (!$this->userModel->emailExists($email)) {
            $_SESSION["error"] = "Email inconnu.";
            $this->redirect("/login/reset");
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->userModel->updatePassword($email, $hashedPassword);
            $_SESSION["success"] = "Mot de passe mis à jour avec succès.";
            $this->redirect("/login");
        } catch (Exception $e) {
            $_SESSION["error"] = "Erreur lors de la mise à jour.";
            $this->redirect("/login/reset");
        }
    }
}
?>