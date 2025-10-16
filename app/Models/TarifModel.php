<?php

require_once __DIR__ . "/Model.php";

class TarifModel extends Model {
    protected $table = 'tarifs';
    protected $primaryKey = 'id_tarif';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Ajoute un nouveau tarif pour un bien.
     *
     * @param array $data Les données du tarif (prix_semaine, annee, id_biens, id_saison).
     * @return int|false L'ID du tarif inséré ou false en cas d'échec.
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (prix_semaine, annee, id_biens, id_saison) VALUES (:prix_semaine, :annee, :id_biens, :id_saison)");
        $result = $stmt->execute([
            'prix_semaine' => $data['prix_semaine'],
            'annee' => $data['annee'],
            'id_biens' => $data['id_biens'],
            'id_saison' => $data['id_saison']
        ]);
        return $result ? $this->db->lastInsertId() : false;
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET prix_semaine = :prix_semaine, annee = :annee, id_saison = :id_saison WHERE id_tarif = :id_tarif";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'prix_semaine' => $data['prix_semaine'],
            'annee' => $data['annee'],
            'id_saison' => $data['id_saison'],
            'id_tarif' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_tarif = :id_tarif");
        $stmt->execute(['id_tarif' => $id]);
        return $stmt->rowCount();
    }

    /**
     * Récupère un tarif spécifique pour un bien, une saison et une année donnés.
     *
     * @param int $bienId L'ID du bien.
     * @param int $saisonId L'ID de la saison.
     * @param int $annee L'année du tarif.
     * @return array|false Le tarif trouvé ou false si aucun tarif correspondant.
     */
    public function getTarifByBienSaisonAnnee($bienId, $saisonId, $annee) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id_biens = :id_biens AND id_saison = :id_saison AND annee = :annee");
        $stmt->execute([
            'id_biens' => $bienId,
            'id_saison' => $saisonId,
            'annee' => $annee
        ]);
        return $stmt->fetch();
    }

    /**
     * Récupère les tarifs pour un bien donné.
     *
     * @param int $bienId L'ID du bien.
     * @return array Les tarifs du bien.
     */
    public function getTarifsByBien($bienId) {
        $stmt = $this->db->prepare("
            SELECT 
                t.*,
                s.lib_saison
            FROM " . $this->table . " t
            JOIN saison s ON t.id_saison = s.id_saison
            WHERE t.id_biens = :id_biens
            ORDER BY t.annee DESC, s.lib_saison ASC
        ");
        $stmt->execute(['id_biens' => $bienId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Supprime les tarifs pour un bien donné.
     *
     * @param int $bienId L'ID du bien.
     * @return int Le nombre de lignes supprimées.
     */
    public function deleteByBien($bienId) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_biens = :id_biens");
        $stmt->execute(['id_biens' => $bienId]);
        return $stmt->rowCount();
    }
}

?>
