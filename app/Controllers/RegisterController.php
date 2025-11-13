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
            $fullTel = $_POST["tel_locataire_formatted"] ?? "";
            $tel = $_POST["tel_locataire"] ?? "";

            // Utiliser le numéro non formaté si le champ formaté est vide (ce qui arrive si intl-tel-input n'est pas initialisé ou désactivé)
            $numberToValidate = !empty($fullTel) ? $fullTel : $tel;

            // Validation du numéro de téléphone (si fourni)
            // Le format E.164 est requis pour $fullTel. Si $tel est utilisé, on suppose que c'est un numéro local qui devrait être validé par intl-tel-input,
            // mais comme le champ formaté est vide, on va valider le champ non formaté pour s'assurer qu'il contient au moins des chiffres.
            // Pour l'instant, on se concentre sur la correction du bug où $fullTel est vide.
            if (!empty($fullTel) && !preg_match('/^\+[1-9]\d{1,14}$/', $fullTel)) {
                $_SESSION["error"] = "Le numéro de téléphone n'est pas valide (format E.164 requis).";
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
                'tel_locataire' => $fullTel ?: ($tel ?? null), // $fullTel est prioritaire, sinon $tel (qui peut être vide)
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
                if ($roleChoice === "proprietaire") {
                    $this->userModel->assignRole($userId, 2); // ID 2 = Propriétaire
                } else if ($roleChoice === "locataire") {
                    $this->userModel->assignRole($userId, 3); // ID 3 = Locataire
                } else {
                    // Gérer le cas où le rôle n'est ni propriétaire ni locataire, par défaut locataire
                    $this->userModel->assignRole($userId, 3); // ID 3 = Locataire
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
