<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";

class RegisterController extends BaseController {
    private $userModel;
    private $communeModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->communeModel = new CommuneModel();
    }

    public function index() {
        $communes = $this->communeModel->getAll();
        $this->render("register/index", ["communes" => $communes]);
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validation des données
            $email = $_POST["email"] ?? "";
            $password = $_POST["password"] ?? "";
            $confirmPassword = $_POST["confirm_password"] ?? "";

            // Vérifier que les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $_SESSION["error"] = "Les mots de passe ne correspondent pas.";
                $this->redirect("/register");
                return;
            }

            // Vérifier que l'email n'existe pas déjà
            $existingUser = $this->userModel->getUserByEmail($email);
            if ($existingUser) {
                $_SESSION["error"] = "Un compte existe déjà avec cet email.";
                $this->redirect("/register");
                return;
            }

            // Préparer les données
            $data = [
                'nom_locataire' => $_POST["nom"] ?? null,
                'prenom_locataire' => $_POST["prenom"] ?? null,
                'dateNaissance_locataire' => $_POST["date_naissance"] ?? null,
                'email_locataire' => $email,
                'password_locataire' => password_hash($password, PASSWORD_DEFAULT),
                'tel_locataire' => $_POST["tel"] ?? null,
                'rue_locataire' => $_POST["rue"] ?? null,
                'complement_locataire' => $_POST["complement"] ?? null,
                'RaisonSociale' => $_POST["raison_sociale"] ?? null,
                'Siret' => $_POST["siret"] ?? null,
                'id_commune' => $_POST["id_commune"] ?? null
            ];

            // Créer l'utilisateur
            $userId = $this->userModel->create($data);

            if ($userId) {
                // Assigner le rôle par défaut "Locataire" (id_roles = 3)
                $this->userModel->assignRole($userId, 3);

                // Si l'utilisateur a fourni une raison sociale, lui assigner aussi le rôle "Propriétaire" (id_roles = 2)
                if (!empty($_POST["RaisonSociale"])) {
                    $this->userModel->assignRole($userId, 2);
                }

                $_SESSION["success"] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                $this->redirect("/login");
            } else {
                $_SESSION["error"] = "Une erreur est survenue lors de l'inscription.";
                $this->redirect("/register");
            }
        }
    }
}

?>
