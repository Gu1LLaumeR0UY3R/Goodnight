<?php

require_once __DIR__ . "/Model.php";

class UserModel extends Model {
    protected $table = 'locataire';
    protected $primaryKey = 'id_locataire';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (nom_locataire, prenom_locataire, dateNaissance_locataire, email_locataire, password_locataire, tel_locataire, rue_locataire, complement_locataire, RaisonSociale, Siret, id_commune) VALUES (:nom_locataire, :prenom_locataire, :dateNaissance_locataire, :email_locataire, :password_locataire, :tel_locataire, :rue_locataire, :complement_locataire, :RaisonSociale, :Siret, :id_commune)");
        try {
            $stmt->execute([
                'nom_locataire' => $data['nom_locataire'],
                'prenom_locataire' => $data['prenom_locataire'],
                'dateNaissance_locataire' => $data['dateNaissance_locataire'] ?? null,
                'email_locataire' => $data['email_locataire'],
                'password_locataire' => $data['password_locataire'],
                'tel_locataire' => $data['tel_locataire'] ?? null,
                'rue_locataire' => $data['rue_locataire'] ?? null,
                'complement_locataire' => $data['complement_locataire'] ?? null,
                'RaisonSociale' => $data['RaisonSociale'] ?? null,
                'Siret' => $data['Siret'] ?? null,
                'id_commune' => $data['id_commune'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur d'enregistrement de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " \n            SET \n                nom_locataire = :nom_locataire, \n                prenom_locataire = :prenom_locataire, \n                dateNaissance_locataire = :dateNaissance_locataire, \n                email_locataire = :email_locataire, \n                tel_locataire = :tel_locataire, \n                rue_locataire = :rue_locataire, \n                complement_locataire = :complement_locataire, \n                RaisonSociale = :RaisonSociale, \n                Siret = :Siret, \n                id_commune = :id_commune \n            WHERE id_locataire = :id_locataire";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nom_locataire' => $data['nom_locataire'] ?? null,
            'prenom_locataire' => $data['prenom_locataire'] ?? null,
            'dateNaissance_locataire' => $data['dateNaissance_locataire'] ?? null,
            'email_locataire' => $data['email_locataire'],
            'tel_locataire' => $data['tel_locataire'] ?? null,
            'rue_locataire' => $data['rue_locataire'] ?? null,
            'complement_locataire' => $data['complement_locataire'] ?? null,
            'RaisonSociale' => $data['RaisonSociale'] ?? null,
            'Siret' => $data['Siret'] ?? null,
            'id_commune' => $data['id_commune'] ?? null,
            'id_locataire' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_locataire = :id_locataire");
        $stmt->execute(['id_locataire' => $id]);
        return $stmt->rowCount();
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE email_locataire = :email_locataire");
        $stmt->execute(['email_locataire' => $email]);
        return $stmt->fetch();
    }

    public function getUserWithRoles($userId) {
        $stmt = $this->db->prepare("\n            SELECT \n                u.*,\n                GROUP_CONCAT(r.nom_roles) as roles\n            FROM locataire u\n            LEFT JOIN User_role ur ON u.id_locataire = ur.id_locataire\n            LEFT JOIN Roles r ON ur.id_roles = r.id_roles\n            WHERE u.id_locataire = :id_locataire\n            GROUP BY u.id_locataire\n        ");
        $stmt->execute(['id_locataire' => $userId]);
        return $stmt->fetch();
    }

    public function getAllUsersWithRoles() {
        $stmt = $this->db->query("\n            SELECT \n                u.*,\n                GROUP_CONCAT(r.nom_roles SEPARATOR ', ') as roles\n            FROM locataire u\n            LEFT JOIN User_role ur ON u.id_locataire = ur.id_locataire\n            LEFT JOIN Roles r ON ur.id_roles = r.id_roles\n            GROUP BY u.id_locataire\n        ");
        return $stmt->fetchAll();
    }

    public function assignRole($userId, $roleId) {
        $stmt = $this->db->prepare("INSERT INTO User_role (id_locataire, id_roles) VALUES (:id_locataire, :id_roles)");
        $stmt->execute([
            'id_locataire' => $userId,
            'id_roles' => $roleId
        ]);
        return $stmt->rowCount();
    }

    public function removeRole($userId, $roleId) {
        $stmt = $this->db->prepare("DELETE FROM User_role WHERE id_locataire = :id_locataire AND id_roles = :id_roles");
        $stmt->execute([
            'id_locataire' => $userId,
            'id_roles' => $roleId
        ]);
        return $stmt->rowCount();
    }

    public function getUserRoles($userId) {
        $stmt = $this->db->prepare("\n            SELECT r.* \n            FROM Roles r\n            INNER JOIN User_role ur ON r.id_roles = ur.id_roles\n            WHERE ur.id_locataire = :id_locataire\n        ");
        $stmt->execute(['id_locataire' => $userId]);
        return $stmt->fetchAll();
    }

    public function searchUsersByIdRoleAndName($term, $id_roles = null) {
        $query = "\n            SELECT DISTINCT l.id_locataire, l.nom_locataire, l.prenom_locataire, l.RaisonSociale, l.Siret, l.type_locataire\n            FROM locataire l\n            LEFT JOIN User_role ur ON l.id_locataire = ur.id_locataire\n            WHERE (l.nom_locataire LIKE :term_nom OR l.prenom_locataire LIKE :term_prenom OR l.RaisonSociale LIKE :term_raison)\n        ";
        $params = [
            ":term_nom" => "%" . $term . "%",
            ":term_prenom" => "%" . $term . "%",
            ":term_raison" => "%" . $term . "%"
        ];

        if ($id_roles) {
            $query .= " AND ur.id_roles = :id_roles";
            $params[":id_roles"] = $id_roles;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersByRole($roleId, $type = null) {
        $query = "\n            SELECT l.*\n            FROM locataire l\n            JOIN user_role ur ON l.id_locataire = ur.id_locataire\n            WHERE ur.id_roles = :roleId\n        ";
        $params = [':roleId' => $roleId];

        if ($type === 'physique') {
            $query .= " AND l.nom_locataire IS NOT NULL AND l.prenom_locataire IS NOT NULL";
        } elseif ($type === 'morale') {
            $query .= " AND l.RaisonSociale IS NOT NULL AND l.Siret IS NOT NULL";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
