<?php

require_once __DIR__ . "/Model.php";

class BienModel extends Model {
    protected $table = 'biens';
    protected $primaryKey = 'id_biens'; // Définir la clé primaire

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (designation_bien, rue_biens, complement_biens, superficie_biens, description_biens, animaux_biens, nb_couchage, id_TypeBien, id_commune, id_locataire) VALUES (:designation_bien, :rue_biens, :complement_biens, :superficie_biens, :description_biens, :animaux_biens, :nb_couchage, :id_TypeBien, :id_commune, :id_locataire)");
        $stmt->execute([
            'designation_bien' => $data['designation_bien'],
            'rue_biens' => $data['rue_biens'],
            'complement_biens' => $data['complement_biens'] ?? null,
            'superficie_biens' => $data['superficie_biens'],
            'description_biens' => $data['description_biens'] ?? null,
            'animaux_biens' => $data['animaux_biens'] ?? 0,
            'nb_couchage' => $data['nb_couchage'],
            'id_TypeBien' => $data['id_TypeBien'],
            'id_commune' => $data['id_commune'],
            'id_locataire' => $data['id_locataire'] // Le propriétaire
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET designation_bien = :designation_bien, rue_biens = :rue_biens, complement_biens = :complement_biens, superficie_biens = :superficie_biens, description_biens = :description_biens, animaux_biens = :animaux_biens, nb_couchage = :nb_couchage, id_TypeBien = :id_TypeBien, id_commune = :id_commune WHERE id_biens = :id_biens";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'designation_bien' => $data['designation_bien'],
            'rue_biens' => $data['rue_biens'],
            'complement_biens' => $data['complement_biens'] ?? null,
            'superficie_biens' => $data['superficie_biens'],
            'description_biens' => $data['description_biens'] ?? null,
            'animaux_biens' => $data['animaux_biens'] ?? 0,
            'nb_couchage' => $data['nb_couchage'],
            'id_TypeBien' => $data['id_TypeBien'],
            'id_commune' => $data['id_commune'],
            'id_biens' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_biens = :id_biens");
        $stmt->execute(['id_biens' => $id]);
        return $stmt->rowCount();
    }

    // Récupérer les biens d'un propriétaire
    public function getBiensByProprietaire($proprietaireId) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id_locataire = :id_locataire");
        $stmt->execute(['id_locataire' => $proprietaireId]);
        return $stmt->fetchAll();
    }

    public function getBiensWithDetails() {
        $stmt = $this->db->query("
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom 
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune
        ");
        return $stmt->fetchAll();
    }

    public function getBiensByType($typeBienId) {
        $stmt = $this->db->prepare("
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom 
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune 
            WHERE b.id_TypeBien = :id_TypeBien
        ");
        $stmt->execute(['id_TypeBien' => $typeBienId]);
        return $stmt->fetchAll();
    }

    public function searchBiensByCommune($communeNom) {
        $stmt = $this->db->prepare("
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom 
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune 
            WHERE c.ville_nom LIKE :communeNom
        ");
        $stmt->execute(['communeNom' => '%' . $communeNom . '%']);
        return $stmt->fetchAll();
    }
}

?>
