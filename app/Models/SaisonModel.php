<?php

require_once __DIR__ . "/Model.php";

class SaisonModel extends Model {
    protected $table = 'Saison';
    protected $primaryKey = 'id_saison';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (lib_saison) VALUES (:lib_saison)");
        $stmt->execute(['lib_saison' => $data['lib_saison']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET lib_saison = :lib_saison WHERE id_saison = :id_saison";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['lib_saison' => $data['lib_saison'], 'id_saison' => $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_saison = :id_saison");
        $stmt->execute(['id_saison' => $id]);
        return $stmt->rowCount();
    }
}

?>
