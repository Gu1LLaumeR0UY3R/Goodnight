<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/AdminModel.php";

class LoginController extends BaseController {
    private $userModel;
    private $adminModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->adminModel = new AdminModel();
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
            // Tenter de se connecter en tant qu'administrateur
            $admin = $this->adminModel->getAdminByEmail($email);

            if ($admin && password_verify($password, $admin["mot_de_passe_admin"]) && $admin["is_admin"]) {
                // Connexion admin réussie
                $_SESSION["user_id"] = $admin["id_admin"];
                $_SESSION["user_email"] = $admin["email_admin"];
                $_SESSION["user_nom"] = $admin["nom_admin"];
                $_SESSION["user_prenom"] = $admin["prenom_admin"];
                $_SESSION["is_admin"] = true;
                $this->redirect("/admin");
                return;
            }

            // Si ce n'est pas un admin, tenter de se connecter en tant qu'utilisateur normal (locataire)
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
            } else if (!$admin) { // Seulement si l'email n'est pas celui d'un admin
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
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
            $this->redirect("/admin");
        } else if (isset($_SESSION["user_roles"]) && is_array($_SESSION["user_roles"])) {
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
}

?>
