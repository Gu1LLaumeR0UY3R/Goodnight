<?php

require_once __DIR__ . "/Model.php";

class CommuneModel extends Model {
    protected $table = 'commune';
    protected $primaryKey = 'id_commune'; // Définir la clé primaire pour la table commune

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        throw new Exception("La création de communes n'est pas supportée directement via ce modèle.");
    }

    public function update($id, $data) {
        throw new Exception("La mise à jour de communes n'est pas supportée directement via ce modèle.");
    }

    public function delete($id) {
        throw new Exception("La suppression de communes n'est pas supportée directement via ce modèle.");
    }

    public function findByCodePostal($codePostal) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE ville_code_postal = :codePostal");
        $stmt->execute(['codePostal' => $codePostal]);
        return $stmt->fetchAll();
    }

    public function findByNom($nom) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE ville_nom LIKE :nom");
        $stmt->execute(['nom' => '%' . $nom . '%']);
        return $stmt->fetchAll();
    }

    public function getCommuneByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE ville_nom = :ville_nom");
        $stmt->execute(['ville_nom' => $name]);
        return $stmt->fetch();
    }
}

?>
