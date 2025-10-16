<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/RoleModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/SaisonModel.php";
require_once __DIR__ . "/../Models/BienModel.php";

class AdminController extends BaseController {
    private $userModel;
    private $roleModel;
    private $communeModel;
    private $typeBienModel;
    private $saisonModel;
    private $bienModel;

    public function __construct() {
        // Vérifier si l\'utilisateur est connecté et a le rôle d\'administrateur
        AuthMiddleware::requireRole("Administrateur");

        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->communeModel = new CommuneModel();
        $this->typeBienModel = new TypeBienModel();
        $this->saisonModel = new SaisonModel();
        $this->bienModel = new BienModel();

    }

    public function index() {
        $this->render("admin/index");
    }

    // --- Gestion des Rôles ---
    public function roles() {
        $roles = $this->roleModel->getAll();
        $this->render("admin/roles", ["roles" => $roles]);
    }

    public function addRole() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->roleModel->create(["nom_roles" => $_POST["nom_roles"]]);
            $this->redirect("/admin/roles");
        }
        $this->render("admin/add_role");
    }

    public function editRole($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->roleModel->update($id, ["nom_roles" => $_POST["nom_roles"]]);
            $this->redirect("/admin/roles");
        }
        $role = $this->roleModel->getById($id);
        $this->render("admin/edit_role", ["role" => $role]);
    }

    public function deleteRole($id) {
        $this->roleModel->delete($id);
        $this->redirect("/admin/roles");
    }

    // --- Gestion des Communes ---
    public function communes() {
        $communes = $this->communeModel->getAll();
        $this->render("admin/communes", ["communes" => $communes]);
    }

    // --- Gestion des Types de Biens ---
    public function typesBiens() {
        $typesBiens = $this->typeBienModel->getAll();
        $this->render("admin/types_biens", ["typesBiens" => $typesBiens]);
    }

    public function addTypeBien() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->typeBienModel->create(["desc_type_bien" => $_POST["desc_type_bien"]]);
            $this->redirect("/admin/typesBiens");
        }
        $this->render("admin/add_type_bien");
    }

    public function editTypeBien($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->typeBienModel->update($id, ["desc_type_bien" => $_POST["desc_type_bien"]]);
            $this->redirect("/admin/typesBiens");
        }
        $typeBien = $this->typeBienModel->getById($id);
        $this->render("admin/edit_type_bien", ["typeBien" => $typeBien]);
    }

    public function deleteTypeBien($id) {
        $this->typeBienModel->delete($id);
        $this->redirect("/admin/typesBiens");
    }

    // --- Gestion des Saisons ---
    public function saisons() {
        $saisons = $this->saisonModel->getAll();
        $this->render("admin/saisons", ["saisons" => $saisons]);
    }

    public function addSaison() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->saisonModel->create(["lib_saison" => $_POST["lib_saison"]]);
            $this->redirect("/admin/saisons");
        }
        $this->render("admin/add_saison");
    }

    public function editSaison($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->saisonModel->update($id, ["lib_saison" => $_POST["lib_saison"]]);
            $this->redirect("/admin/saisons");
        }
        $saison = $this->saisonModel->getById($id);
        $this->render("admin/edit_saison", ["saison" => $saison]);
    }

    public function deleteSaison($id) {
        $this->saisonModel->delete($id);
        $this->redirect("/admin/saisons");
    }


    // --- Gestion des Biens ---
    public function biens() {
        $biens = $this->bienModel->getBiensWithProprietaireDetails();
        $this->render("admin/biens", ["biens" => $biens]);
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
        ]);
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
        $personnesMorales = $this->userModel->getUsersByRole(2, 'morale');

        // Passer les données à la vue
        $this->render("admin/edit_bien", [
            "bien" => $bien,
            "typesBiens" => $typesBiens,
            "communeNom" => $communeNom,
            "proprietaireNom" => $proprietaireNom,
            "personnesPhysiques" => $personnesPhysiques,
            "personnesMorales" => $personnesMorales
        ]);
    }

    public function deleteBien($id) {
        $this->bienModel->delete($id);
        $this->redirect("/admin/biens");
    }



    // --- Gestion des Utilisateurs ---
    public function users() {
        $users = $this->userModel->getAllUsersWithRoles();
        $this->render("admin/users", ["users" => $users]);
    }

    public function addUser() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'nom_locataire' => $_POST["nom_locataire"],
                'prenom_locataire' => $_POST["prenom_locataire"],
                'dateNaissance_locataire' => $_POST["dateNaissance_locataire"] ?? null,
                'email_locataire' => $_POST["email_locataire"],
                'password_locataire' => password_hash($_POST["password_locataire"], PASSWORD_DEFAULT),
                'tel_locataire' => $_POST["tel_locataire"] ?? null,
                'rue_locataire' => $_POST["rue_locataire"] ?? null,
                'complement_locataire' => $_POST["complement_locataire"] ?? null,
                'RaisonSociale' => $_POST["RaisonSociale"] ?? null,
                'Siret' => $_POST["Siret"] ?? null,
                'id_commune' => $_POST["id_commune"] ?? null
            ];
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
        $this->render("admin/add_user", ["roles" => $roles, "communes" => $communes]);
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
        $this->render("admin/edit_user", ["user" => $user, "roles" => $roles, "communes" => $communes, "userRoleIds" => $userRoleIds]);
    }

    public function deleteUser($id) {
        $this->userModel->delete($id);
        $this->redirect("/admin/users");
    }

    // --- API Endpoints for Autocomplete ---
    public function searchUsers() {
        header("Content-Type: application/json");
        $term = $_GET["term"] ?? "";
        $role = $_GET["role"] ?? null;
        $users = $this->userModel->searchUsersByRoleAndName($term, $role);
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
