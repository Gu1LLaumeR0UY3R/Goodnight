    // --- Gestion des Utilisateurs ---
    public function users() {
        $users = $this->userModel->getAll();
        $this->render("admin/users", ["users" => $users]);
    }

    public function editUser($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userData = [
                "nom_locataire" => $_POST["nom_locataire"],
                "prenom_locataire" => $_POST["prenom_locataire"],
                "email_locataire" => $_POST["email_locataire"],
                "tel_locataire" => $_POST["tel_locataire"],
                "RaisonSociale" => $_POST["RaisonSociale"] ?? null,
                "Siret" => $_POST["Siret"] ?? null,
                "is_moral" => isset($_POST["is_moral"]) ? 1 : 0,
                "id_commune" => $_POST["id_commune"] ?? null
            ];
            $this->userModel->update($id, $userData);

            // Gestion des rôles de l'utilisateur
            // Supprimer tous les rôles existants pour cet utilisateur
            $this->userModel->deleteUserRoles($id);
            // Ajouter les nouveaux rôles sélectionnés
            if (isset($_POST["roles"]) && is_array($_POST["roles"])) {
                foreach ($_POST["roles"] as $roleId) {
                    $this->userModel->addRoleToUser($id, $roleId);
                }
            }
            $this->redirect("/admin/users");
        }
        $user = $this->userModel->getById($id);
        $userRoles = $this->userModel->getUserRoles($id);
        $allRoles = $this->roleModel->getAll();
        $communes = $this->communeModel->getAll(); // Pour le champ id_commune
        $this->render("admin/edit_user", ["user" => $user, "userRoles" => $userRoles, "allRoles" => $allRoles, "communes" => $communes]);
    }

    public function deleteUser($id) {
        $this->userModel->delete($id);
        $this->redirect("/admin/users");
    }
