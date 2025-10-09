<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/RoleModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";

class AdminController extends BaseController {
    private $userModel;
    private $roleModel;
    private $communeModel;
    private $typeBienModel;

    public function __construct() {
        // Vérifier si l'utilisateur est connecté et a le rôle d'administrateur
        AuthMiddleware::requireRole("Administrateur");

        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->communeModel = new CommuneModel();
        $this->typeBienModel = new TypeBienModel();
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
            
            // Assigner les rôles sélectionnés
            if (isset($_POST["roles"]) && is_array($_POST["roles"])) {
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
}

?>
