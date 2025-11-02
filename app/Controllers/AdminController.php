<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/RoleModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/SaisonModel.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TarifModel.php";
require_once __DIR__ . "/../Models/AdminModel.php";
require_once __DIR__ . "/../Models/ReservationModel.php";

class AdminController extends BaseController {
    private $userModel;
    private $roleModel;
    private $communeModel;
    private $typeBienModel;
    private $saisonModel;
    private $bienModel;
    private $tarifModel;
    private $adminModel;
    private $reservationModel;

    public function __construct() {
        AuthMiddleware::requireRole("Administrateur");

        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->communeModel = new CommuneModel();
        $this->typeBienModel = new TypeBienModel();
        $this->saisonModel = new SaisonModel();
        $this->bienModel = new BienModel();
        $this->tarifModel = new TarifModel();
        $this->adminModel = new AdminModel();
        $this->reservationModel = new ReservationModel();
    }

    public function index() {
        $this->render("admin/index", [], ["style.css", "grille.css"]);
    }

    // --- Gestion des Administrateurs ---
    public function admins() {
        $admins = $this->adminModel->getAll();
        $this->render("admin/admins", ["admins" => $admins], ["style.css", "grille.css"]);
    }

    public function addAdmin() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nom_admin" => $_POST["nom_admin"],
                "email_admin" => $_POST["email_admin"],
                "mot_de_passe" => password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT),
                "is_admin" => isset($_POST["is_admin"]) ? 1 : 0
            ];
            $this->adminModel->create($data);
            $this->redirect("/admin/admins");
        }
        $this->render("admin/add_admin", [], ["style.css", "grille.css"]);
    }

    public function editAdmin($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nom_admin" => $_POST["nom_admin"],
                "email_admin" => $_POST["email_admin"],
                "is_admin" => isset($_POST["is_admin"]) ? 1 : 0
            ];
            if (!empty($_POST["mot_de_passe"])) {
                $data["mot_de_passe"] = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
            }
            $this->adminModel->update($id, $data);
            $this->redirect("/admin/admins");
        }
        $admin = $this->adminModel->getById($id);
        $this->render("admin/edit_admin", ["admin" => $admin], ["style.css", "grille.css"]);
    }

    public function deleteAdmin($id) {
        $this->adminModel->delete($id);
        $this->redirect("/admin/admins");
    }

    // --- Gestion des Rôles ---
    public function roles() {
        $roles = $this->roleModel->getAll();
        $this->render("admin/roles", ["roles" => $roles], ["style.css", "grille.css"]);
    }

    public function addRole() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->roleModel->create(["nom_roles" => $_POST["nom_roles"]]);
            $this->redirect("/admin/roles");
        }
        $this->render("admin/add_role", [], ["style.css", "grille.css"]);
    }

    public function editRole($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->roleModel->update($id, ["nom_roles" => $_POST["nom_roles"]]);
            $this->redirect("/admin/roles");
        }
        $role = $this->roleModel->getById($id);
        $this->render("admin/edit_role", ["role" => $role], ["style.css", "grille.css"]);
    }

    public function deleteRole($id) {
        $this->roleModel->delete($id);
        $this->redirect("/admin/roles");
    }

    // --- Gestion des Communes ---
    public function communes() {
        $communes = $this->communeModel->getAll();
        $this->render("admin/communes", ["communes" => $communes], ["style.css", "grille.css"]);
    }

    // --- Gestion des Types de Biens ---
    public function typesBiens() {
        $typesBiens = $this->typeBienModel->getAll();
        $this->render("admin/types_biens", ["typesBiens" => $typesBiens], ["style.css", "grille.css"]);
    }

    public function addTypeBien() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->typeBienModel->create(["desc_type_bien" => $_POST["desc_type_bien"]]);
            $this->redirect("/admin/typesBiens");
        }
        $this->render("admin/add_type_bien", [], ["style.css", "grille.css"]);
    }

    public function editTypeBien($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->typeBienModel->update($id, ["desc_type_bien" => $_POST["desc_type_bien"]]);
            $this->redirect("/admin/typesBiens");
        }
        $typeBien = $this->typeBienModel->getById($id);
        $this->render("admin/edit_type_bien", ["typeBien" => $typeBien], ["style.css", "grille.css"]);
    }

    public function deleteTypeBien($id) {
        $this->typeBienModel->delete($id);
        $this->redirect("/admin/typesBiens");
    }

    // --- Gestion des Saisons ---
    public function saisons() {
        $saisons = $this->saisonModel->getAll();
        $this->render("admin/saisons", ["saisons" => $saisons], ["style.css", "grille.css"]);
    }

    public function addSaison() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->saisonModel->create(["lib_saison" => $_POST["lib_saison"]]);
            $this->redirect("/admin/saisons");
        }
        $this->render("admin/add_saison", [], ["style.css", "grille.css"]);
    }

    public function editSaison($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->saisonModel->update($id, ["lib_saison" => $_POST["lib_saison"]]);
            $this->redirect("/admin/saisons");
        }
        $saison = $this->saisonModel->getById($id);
        $this->render("admin/edit_saison", ["saison" => $saison], ["style.css", "grille.css"]);
    }

    public function deleteSaison($id) {
        $this->saisonModel->delete($id);
        $this->redirect("/admin/saisons");
    }

    // --- Gestion des Biens ---
    public function biens() {
        $biens = $this->bienModel->getBiensWithProprietaireDetails();
        $this->render("admin/biens", ["biens" => $biens], ["style.css", "grille.css"]);
    }

    public function addBien() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'designation_bien' => $_POST["designation_bien"],
                'rue_biens' => $_POST["rue_biens"],
                'complement_biens' => $_POST["complement_biens"] ?? null,
                'superficie_biens' => $_POST["superficie_biens"],
                'description_biens' => $_POST["description_biens"] ?? null,
                'animaux_biens' => $_POST["animaux_biens"] ?? 0,
                'nb_couchage' => $_POST["nb_couchage"],
                'id_TypeBien' => $_POST["id_TypeBien"],
                'id_commune' => $_POST["id_commune"],
                'id_locataire' => $_POST["id_locataire"] // Le propriétaire
            ];
            $this->bienModel->create($data);
            $this->redirect("/admin/biens");
        }

        // Récupérer les données nécessaires pour le formulaire
        $typesBiens = $this->typeBienModel->getAll();
        $communes = $this->communeModel->getAll();
        $personnesPhysiques = $this->userModel->getUsersByRole(2, 'physique');
        $personnesMorales = $this->userModel->getUsersByRole(2, 'morale');

        // Passer les données à la vue
        $this->render("admin/add_bien", [
            "typesBiens" => $typesBiens,
            "communes" => $communes,
            "personnesPhysiques" => $personnesPhysiques,
            "personnesMorales" => $personnesMorales
        ], ["style.css", "grille.css"]);
    }

    public function editBien($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'designation_bien' => $_POST["designation_bien"],
                'rue_biens' => $_POST["rue_biens"],
                'complement_biens' => $_POST["complement_biens"] ?? null,
                'superficie_biens' => $_POST["superficie_biens"],
                'description_biens' => $_POST["description_biens"] ?? null,
                'animaux_biens' => isset($_POST["animaux_biens"]) ? 1 : 0, // Gestion de la case à cocher
                'nb_couchage' => $_POST["nb_couchage"],
                'id_TypeBien' => $_POST["id_TypeBien"],
                'id_commune' => $_POST["id_commune"],
                'id_locataire' => $_POST["id_locataire"] ?? null // S'assurer que id_locataire est toujours défini, même si vide
            ];
            $this->bienModel->update($id, $data);
            $this->redirect("/admin/biens");
        }

        // Récupérer les données nécessaires pour la vue
        $bien = $this->bienModel->getById($id);
        $typesBiens = $this->typeBienModel->getAll();

        // Récupérer la commune associée au bien pour le pré-remplissage
        $communeBien = $this->communeModel->getById($bien['id_commune']);
        $communeNom = '';
        if ($communeBien) {
            $nomCommune = $communeBien['nom_commune'] ?? '';
            $codePostal = $communeBien['code_postal'] ?? '';
            $communeNom = $nomCommune . ' (' . $codePostal . ')';
        }

        // Récupérer le propriétaire associé au bien pour le pré-remplissage
        $proprietaireBien = $this->userModel->getById($bien['id_locataire']);
        $proprietaireNom = '';
        if ($proprietaireBien) {
            if (!empty($proprietaireBien['RaisonSociale'])) {
                $proprietaireNom = $proprietaireBien['RaisonSociale'];
            } else {
                $proprietaireNom = $proprietaireBien['prenom_locataire'] . ' ' . $proprietaireBien['nom_locataire'];
            }
        }

        $personnesPhysiques = $this->userModel->getUsersByRole(2, 'physique');
        $saisons = $this->saisonModel->getAll();
        $tarifs = $this->tarifModel->getTarifsByBien($id);

        $tarifsMapped = [];
        foreach ($tarifs as $tarif) {
            $tarifsMapped[$tarif['id_saison'] . '_' . $tarif['annee']] = $tarif['prix_semaine'];
        }

        // Passer les données à la vue
        $this->render("admin/edit_bien", [
            "bien" => $bien,
            "typesBiens" => $typesBiens,
            "communeNom" => $communeNom,
            "proprietaireNom" => $proprietaireNom,
            "saisons" => $saisons,
            "tarifsMapped" => $tarifsMapped
        ], ["style.css", "grille.css"]);
    }

    public function deleteBien($id) {
        $this->bienModel->delete($id);
        $this->redirect("/admin/biens");
    }

    // --- Gestion des Utilisateurs ---
    public function users() {
        $users = $this->userModel->getAllUsersWithRoles();
        $this->render("admin/users", ["users" => $users], ["style.css", "grille.css"]);
    }

    public function addUser() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validation des champs Siret et RaisonSociale
            $siret = $_POST["Siret"] ?? "";
            if (!empty($siret) && (!ctype_digit($siret) || strlen($siret) !== 14)) {
                $_SESSION["error"] = "Le numéro SIRET doit contenir exactement 14 chiffres.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/admin/addUser");
                return;
            }

            $raisonSociale = $_POST["RaisonSociale"] ?? "";
            if (!empty($raisonSociale) && strlen($raisonSociale) > 255) {
                $_SESSION["error"] = "La raison sociale ne peut pas dépasser 255 caractères.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/admin/addUser");
                return;
            }

            // Préparer les données
            $data = [
                'nom_locataire' => $_POST["nom_locataire"],
                'prenom_locataire' => $_POST["prenom_locataire"],
                'dateNaissance_locataire' => $_POST["dateNaissance_locataire"] ?? null,
                'email_locataire' => $_POST["email_locataire"],
                'password_locataire' => password_hash($_POST["password_locataire"], PASSWORD_DEFAULT),
                'tel_locataire' => $_POST["tel_locataire"] ?? null,
                'rue_locataire' => $_POST["rue_locataire"] ?? null,
                'complement_locataire' => $_POST["complement_locataire"] ?? null,
                'RaisonSociale' => empty($_POST["RaisonSociale"]) ? null : $_POST["RaisonSociale"],
                'Siret' => empty($_POST["Siret"]) ? null : $_POST["Siret"],
                'id_commune' => $_POST["id_commune"] ?? null
            ];

            // Créer l'utilisateur
            $userId = $this->userModel->create($data);

            // Assigner les rôles sélectionnés seulement si l'utilisateur a été créé avec succès
            if ($userId && isset($_POST["roles"]) && is_array($_POST["roles"])) {
                foreach ($_POST["roles"] as $roleId) {
                    $this->userModel->assignRole($userId, $roleId);
                }
            }

            $this->redirect("/admin/users");
        }

        $roles = $this->roleModel->getAll();
        $communes = $this->communeModel->getAll();
        $this->render("admin/addUser", ["roles" => $roles, "communes" => $communes], ["style.css", "grille.css"]);
    }

    public function editUser($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'nom_locataire' => $_POST["nom_locataire"],
                'prenom_locataire' => $_POST["prenom_locataire"],
                'dateNaissance_locataire' => $_POST["dateNaissance_locataire"] ?? null,
                'email_locataire' => $_POST["email_locataire"],
                'tel_locataire' => $_POST["tel_locataire"] ?? null,
                'rue_locataire' => $_POST["rue_locataire"] ?? null,
                'complement_locataire' => $_POST["complement_locataire"] ?? null,
                'RaisonSociale' => $_POST["RaisonSociale"] ?? null,
                'Siret' => $_POST["Siret"] ?? null,
                'id_commune' => $_POST["id_commune"] ?? null
            ];
            $this->userModel->update($id, $data);
            
            // Gérer les rôles : supprimer tous les anciens et assigner les nouveaux
            // Note : Cette approche simple supprime et recrée. Pour une approche plus fine, 
            // comparer les rôles existants avec les nouveaux.
            $currentRoles = $this->userModel->getUserRoles($id);
            foreach ($currentRoles as $role) {
                $this->userModel->removeRole($id, $role["id_roles"]);
            }
            
            if (isset($_POST["roles"]) && is_array($_POST["roles"])) {
                foreach ($_POST["roles"] as $roleId) {
                    $this->userModel->assignRole($id, $roleId);
                }
            }
            
            $this->redirect("/admin/users");
        }
        $user = $this->userModel->getById($id);
        $roles = $this->roleModel->getAll();
        $communes = $this->communeModel->getAll();
        $userRoles = $this->userModel->getUserRoles($id);
        $userRoleIds = array_column($userRoles, 'id_roles');
        $this->render(
            "admin/editUser",
            [
                "user" => $user,
                "roles" => $roles,
                "userRoles" => $userRoles,
                "userRoleIds" => $userRoleIds,
                "communes" => $communes
            ],
            ["style.css", "grille.css"]
        );
    }

    public function deleteUser($id) {
        $this->userModel->delete($id);
        $this->redirect("/admin/users");
    }









    // --- Gestion des Utilisateurs ---
    public function reservations() {
        $reservations = $this->reservationModel->getAllReservations();
        $this->render("admin/reservations", ["reservations" => $reservations], ["style.css", "grille.css"]);
    }

    public function addReservation() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'id_biens' => $_POST["id_biens"],
                'id_locataire' => $_POST["id_locataire"],
                'date_debut' => $_POST["date_debut"],
                'date_fin' => $_POST["date_fin"],
                'id_tarif' => $_POST["id_tarif"]
            ];
            $this->reservationModel->createReservation($data);
            $this->redirect("/admin/reservations");
        }

        // Récupérer les données nécessaires pour le formulaire
        $biens = $this->bienModel->getAll();
        $users = $this->userModel->getAllUsersWithRoles();
        $tarifs = $this->tarifModel->getAll();

        $this->render("admin/add_reservation", [
            "biens" => $biens,
            "users" => $users,
            "tarifs" => $tarifs
        ], ["style.css", "grille.css"]);
    }

    public function editReservation($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'id_biens' => $_POST["id_biens"],
                'id_locataire' => $_POST["id_locataire"],
                'date_debut' => $_POST["date_debut"],
                'date_fin' => $_POST["date_fin"],
                'id_tarif' => $_POST["id_tarif"]
            ];
            $this->reservationModel->update($id, $data);
            $this->redirect("/admin/reservations");
        }
        $reservation = $this->reservationModel->getById($id);

        // Récupérer les données nécessaires pour le formulaire
        $biens = $this->bienModel->getAll();
        $users = $this->userModel->getAllUsersWithRoles();
        $tarifs = $this->tarifModel->getAll();

        $this->render("admin/edit_reservation", [
            "reservation" => $reservation,
            "biens" => $biens,
            "users" => $users,
            "tarifs" => $tarifs
        ], ["style.css", "grille.css"]);
    }

    public function deleteReservation($id) {
        $this->reservationModel->delete($id);
        $this->redirect("/admin/reservations");
    }











    // --- API Endpoints for Autocomplete ---
    public function searchUsers() {
        header("Content-Type: application/json");
        $term = $_GET["term"] ?? "";
        $id_roles = $_GET["id_roles"] ?? null;
        $users = $this->userModel->searchUsersByIdRoleAndName($term, $id_roles);
        echo json_encode($users);
    }

    public function searchCommunes() {
        header("Content-Type: application/json");
        $term = $_GET["term"] ?? "";
        $communes = $this->communeModel->search($term);
        echo json_encode($communes);
    }
}

?>
