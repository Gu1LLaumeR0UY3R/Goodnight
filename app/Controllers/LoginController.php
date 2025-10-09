<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";

class LoginController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index() {
        // Si l'utilisateur est déjà connecté, rediriger selon son rôle
        if (isset($_SESSION["user_id"])) {
            $this->redirectByRole();
            return;
        }

        $old_email = $_SESSION["old_email"] ?? "";
        unset($_SESSION["old_email"]);
        $this->render("login/index", ["old_email" => $old_email]);
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? "";
            $password = $_POST["password"] ?? "";

            // Récupérer l'utilisateur par email
            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user["password_locataire"])) {
                // Connexion réussie
                $_SESSION["user_id"] = $user["id_locataire"];
                $_SESSION["user_email"] = $user["email_locataire"];
                $_SESSION["user_nom"] = $user["nom_locataire"];
                $_SESSION["user_prenom"] = $user["prenom_locataire"];

                // Récupérer les rôles
                $roles = $this->userModel->getUserRoles($user["id_locataire"]);
                $_SESSION["user_roles"] = array_column($roles, 'nom_roles');

                // Rediriger selon le rôle
                $this->redirectByRole();
            } else {
                // Échec de la connexion
                $_SESSION["error"] = "Email ou mot de passe incorrect.";
                $_SESSION["old_email"] = $email;
                $this->redirect("/login");
            }
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect("/home");
    }

    private function redirectByRole() {
        if (in_array("Administrateur", $_SESSION["user_roles"])) {
            $this->redirect("/admin");
        } elseif (in_array("Propriétaire", $_SESSION["user_roles"])) {
            $this->redirect("/proprietaire");
        } elseif (in_array("Locataire", $_SESSION["user_roles"])) {
            $this->redirect("/locataire");
        } else {
            $this->redirect("/home");
        }
    }
}

?>
