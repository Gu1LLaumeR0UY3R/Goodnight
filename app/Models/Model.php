<?php

require_once __DIR__ . "/../../lib/Database.php";

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM " . $this->table);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getDb() {
        return $this->db;
    }

    // Méthodes abstraites à implémenter par les modèles enfants
    abstract public function create($data);
    abstract public function update($id, $data);
    abstract public function delete($id);
}

?>
