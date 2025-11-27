<?php
require_once __DIR__ . "/Model.php";

class BlocageModel extends Model {
    protected $table = 'blocages';
    protected $primaryKey = 'id_blocage';

    public function __construct() {
        parent::__construct();
    }

    public function getBlocagesByBien($id_biens) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id_biens = :id_biens ORDER BY date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_biens' => $id_biens]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBlocagesByProprietaire($id_proprietaire) {
        $sql = "SELECT bl.* FROM " . $this->table . " bl JOIN biens b ON bl.id_biens = b.id_biens WHERE b.id_locataire = :id_locataire ORDER BY bl.date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_locataire' => $id_proprietaire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createBlocage($data) {
        $sql = "INSERT INTO " . $this->table . " (id_biens, date_debut, date_fin, motif, commentaire) VALUES (:id_biens, :date_debut, :date_fin, :motif, :commentaire)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_biens' => $data['id_biens'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'motif' => $data['motif'] ?? 'personnel',
            'commentaire' => $data['commentaire'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function deleteBlocage($id_blocage) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id");
        $stmt->execute(['id' => $id_blocage]);
        return $stmt->rowCount();
    }

    // Implement abstract methods from Model
    public function create($data) {
        return $this->createBlocage($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET date_debut = :date_debut, date_fin = :date_fin, motif = :motif, commentaire = :commentaire WHERE " . $this->primaryKey . " = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'motif' => $data['motif'] ?? 'personnel',
            'commentaire' => $data['commentaire'] ?? null,
            'id' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        return $this->deleteBlocage($id);
    }
}
?>
