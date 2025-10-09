<?php

require_once __DIR__ . "/Model.php";

class TypeBienModel extends Model {
    protected $table = 'Type_Bien';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (desc_type_bien) VALUES (:desc_type_bien)");
        $stmt->execute(['desc_type_bien' => $data['desc_type_bien']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET desc_type_bien = :desc_type_bien WHERE id_typebien = :id_typebien";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['desc_type_bien' => $data['desc_type_bien'], 'id_typebien' => $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_typebien = :id_typebien");
        $stmt->execute(['id_typebien' => $id]);
        return $stmt->rowCount();
    }
}

?>
