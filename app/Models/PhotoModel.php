<?php

require_once __DIR__ . "/Model.php";

class PhotoModel extends Model {
    protected $table = 'Photos';

    protected $primaryKey = 'id_photo';
    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (nom_photo, lien_photo, id_biens) VALUES (:nom_photo, :lien_photo, :id_biens)");
        $stmt->execute([
            'nom_photo' => $data['nom_photo'] ?? null,
            'lien_photo' => $data['lien_photo'],
            'id_biens' => $data['id_biens']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET nom_photo = :nom_photo, lien_photo = :lien_photo, id_biens = :id_biens WHERE id_photo = :id_photo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nom_photo' => $data['nom_photo'] ?? null,
            'lien_photo' => $data['lien_photo'],
            'id_biens' => $data['id_biens'],
            'id_photo' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_photo = :id_photo");
        $stmt->execute(['id_photo' => $id]);
        return $stmt->rowCount();
    }

    public function getPhotosByBien($bienId) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id_biens = :id_biens");
        $stmt->execute(['id_biens' => $bienId]);
        return $stmt->fetchAll();
    }
}

?>
