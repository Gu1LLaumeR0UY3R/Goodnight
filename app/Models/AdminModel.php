<?php

require_once __DIR__ . "/Model.php";

class AdminModel extends Model {
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    public function __construct() {
        parent::__construct();
    }

    public function getAdminByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE email_admin = :email_admin");
        $stmt->execute(['email_admin' => $email]);
        return $stmt->fetch();
    }

    public function create($data) {
        // Implémentation pour la création d'un administrateur
        // Exemple: INSERT INTO admin (nom_admin, email_admin, mot_de_passe, is_admin) VALUES (:nom_admin, :email_admin, :mot_de_passe, :is_admin)
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (nom_admin, email_admin, mot_de_passe, is_admin) VALUES (:nom_admin, :email_admin, :mot_de_passe, :is_admin)");
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        // Implémentation pour la mise à jour d'un administrateur
        // Exemple: UPDATE admin SET nom_admin = :nom_admin, email_admin = :email_admin, mot_de_passe = :mot_de_passe, is_admin = :is_admin WHERE id_admin = :id_admin
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "{$key} = :{$key}";
        }
        $stmt = $this->db->prepare("UPDATE " . $this->table . " SET " . implode(', ', $setClause) . " WHERE " . $this->primaryKey . " = :id");
        $data["id"] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        // Implémentation pour la suppression d'un administrateur
        // Exemple: DELETE FROM admin WHERE id_admin = :id_admin
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id");
        return $stmt->execute(["id" => $id]);
    }
}

?>
