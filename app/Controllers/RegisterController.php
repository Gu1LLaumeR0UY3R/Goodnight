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
        $old_data = $_SESSION["old_data"] ?? [];
        unset($_SESSION["old_data"]);
        $this->render("register/index", ["communes" => $communes, "old_data" => $old_data]);
    }

    public function register() {
       if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Validation des données
                $siret = $_POST["siret"] ?? "";
            // Téléphone facultatif : le navigateur envoie le format E.164 ; sinon on normalise côté serveur
            $rawTel = trim($_POST["tel_locataire_formatted"] ?? "") ?: trim($_POST["tel_locataire"] ?? "");
            $tel = PhoneHelper::normalize($rawTel);
            if (PhoneHelper::isProvided($rawTel) && $tel === null) {
                $_SESSION["error"] = "Le numéro de téléphone n'est pas valide.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/register");
                return;
            }
                if (!empty($siret) && (!ctype_digit($siret) || strlen($siret) !== 14)) {
                    $_SESSION["error"] = "Le numéro SIRET doit contenir exactement 14 chiffres.";
                    $_SESSION["old_data"] = $_POST;
                    $this->redirect("/register");
                    return;
                }
            $email = $_POST["email"] ?? "";
            $password = $_POST["password"] ?? "";
            $confirmPassword = $_POST["confirm_password"] ?? "";

            // Vérifier que les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $_SESSION["error"] = "Les mots de passe ne correspondent pas.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/register");
                return;
            }

            // Validation de la force du mot de passe
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{12,}$/', $password)) {
                $_SESSION["error"] = "Mot de passe trop faible (12+ caractères, majuscule, minuscule, chiffre, caractère spécial).";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/register");
                return;
            }

            // Vérifier que l'email n'existe pas déjà
            $existingUser = $this->userModel->getUserByEmail($email);
            if ($existingUser) {
                $_SESSION["error"] = "Un compte existe déjà avec cet email.";
                $_SESSION["old_data"] = $_POST;
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
                'tel_locataire' => $tel,
                'rue_locataire' => $_POST["rue"] ?? null,
                'complement_locataire' => $_POST["complement"] ?? null,
                'RaisonSociale' => empty($_POST["raison_sociale"]) ? null : $_POST["raison_sociale"],
                'Siret' => empty($_POST["siret"]) ? null : $_POST["siret"],
                'id_commune' => $_POST["id_commune"] ?? null
            ];

            // Créer l'utilisateur
            $userId = $this->userModel->create($data);

            if (!$userId) {
                $_SESSION["error"] = "Une erreur est survenue lors de l\"inscription.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/register");
                return;
            }

            if ($userId) {
                // Assigner les rôles.
                $roleChoice = $_POST["role_choice"] ?? "locataire";
                
                // Debug: Log pour vérifier la valeur
                error_log("RegisterController - role_choice reçu: " . $roleChoice);
                error_log("RegisterController - POST data: " . json_encode($_POST));
                
                if ($roleChoice === "proprietaire") {
                    error_log("RegisterController - Attribution du rôle Propriétaire (ID 2)");
                    $this->userModel->assignRole($userId, 1); // ID 2 = Propriétaire
                } else if ($roleChoice === "locataire") {
                    error_log("RegisterController - Attribution du rôle Locataire (ID 3)");
                    $this->userModel->assignRole($userId, 2); // ID 3 = Locataire
                } else {
                    error_log("RegisterController - Rôle non reconnu, attribution Locataire par défaut (ID 3)");
                    // Gérer le cas où le rôle n'est ni propriétaire ni locataire, par défaut locataire
                    $this->userModel->assignRole($userId, 2); // ID 3 = Locataire
                }

                $_SESSION["success"] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                $this->redirect("/login");
            } else {
                $_SESSION["error"] = "Une erreur est survenue lors de l\'inscription.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/register");
            }
        }
    }
}
