<?php

require_once __DIR__ . "/Model.php";

class RoleModel extends Model {
    protected $table = 'Roles';
    protected $primaryKey = 'id_roles';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (nom_roles) VALUES (:nom_roles)");
        $stmt->execute(['nom_roles' => $data['nom_roles']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET nom_roles = :nom_roles WHERE id_roles = :id_roles";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nom_roles' => $data['nom_roles'], 'id_roles' => $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_roles = :id_roles");
        $stmt->execute(['id_roles' => $id]);
        return $stmt->rowCount();
    }

    public function findByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE nom_roles = :name");
        $stmt->execute(['name' => $name]);
        return $stmt->fetch();
    }
}

?>
