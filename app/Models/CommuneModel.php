<?php

require_once __DIR__ . "/Model.php";

class CommuneModel extends Model {
    protected $table = 'commune';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        // La table commune est généralement pré-remplie, donc la création directe n'est pas typique.
        // Cette méthode peut être adaptée si nécessaire pour ajouter de nouvelles communes manuellement.
        throw new Exception("La création de communes n'est pas supportée directement via ce modèle.");
    }

    public function update($id, $data) {
        // Similaire à create, la mise à jour directe n'est pas typique.
        throw new Exception("La mise à jour de communes n'est pas supportée directement via ce modèle.");
    }

    public function delete($id) {
        // Similaire à create, la suppression directe n'est pas typique.
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
