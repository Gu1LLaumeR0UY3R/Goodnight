<?php

require_once __DIR__ . "/Model.php";

class PrestationModel extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = "prestation";
        $this->primaryKey = "id_prestation";
    }

    /**
     * Récupère toutes les prestations disponibles
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM prestation ORDER BY lib_prestation ASC");
        return $stmt->fetchAll();
    }

    /**
     * Récupère les prestations d'un bien spécifique
     * @param int $id_biens
     * @return array
     */
    public function getPrestationsByBien($id_biens) {
        $stmt = $this->db->prepare("
            SELECT p.*, sc.quantite_prestation
            FROM prestation p
            LEFT JOIN se_compose sc ON p.id_prestation = sc.id_prestation AND sc.id_biens = :id_biens
            ORDER BY p.lib_prestation ASC
        ");
        $stmt->execute(['id_biens' => $id_biens]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère toutes les prestations avec indication si elles sont associées à un bien
     * @param int $id_biens
     * @return array
     */
    public function getAllWithBienStatus($id_biens) {
        $stmt = $this->db->prepare("
            SELECT 
                p.*,
                sc.quantite_prestation,
                CASE WHEN sc.id_biens IS NOT NULL THEN 1 ELSE 0 END as selected
            FROM prestation p
            LEFT JOIN se_compose sc ON p.id_prestation = sc.id_prestation AND sc.id_biens = :id_biens
            ORDER BY p.lib_prestation ASC
        ");
        $stmt->execute(['id_biens' => $id_biens]);
        return $stmt->fetchAll();
    }

    /**
     * Ajoute une prestation à un bien
     * @param int $id_biens
     * @param int $id_prestation
     * @param int $quantite
     * @return bool
     */
    public function addPrestationToBien($id_biens, $id_prestation, $quantite = 1) {
        $stmt = $this->db->prepare("
            INSERT INTO se_compose (id_prestation, id_biens, quantite_prestation) 
            VALUES (:id_prestation, :id_biens, :quantite)
            ON DUPLICATE KEY UPDATE quantite_prestation = VALUES(quantite_prestation)
        ");
        return $stmt->execute([
            'id_prestation' => $id_prestation,
            'id_biens' => $id_biens,
            'quantite' => $quantite
        ]);
    }

    /**
     * Supprime une prestation d'un bien
     * @param int $id_biens
     * @param int $id_prestation
     * @return bool
     */
    public function removePrestationFromBien($id_biens, $id_prestation) {
        $stmt = $this->db->prepare("
            DELETE FROM se_compose 
            WHERE id_biens = :id_biens AND id_prestation = :id_prestation
        ");
        return $stmt->execute([
            'id_biens' => $id_biens,
            'id_prestation' => $id_prestation
        ]);
    }

    /**
     * Supprime toutes les prestations d'un bien
     * @param int $id_biens
     * @return bool
     */
    public function removeAllPrestationsFromBien($id_biens) {
        $stmt = $this->db->prepare("DELETE FROM se_compose WHERE id_biens = :id_biens");
        return $stmt->execute(['id_biens' => $id_biens]);
    }

    /**
     * Met à jour les prestations d'un bien (supprime toutes et ajoute les nouvelles)
     * @param int $id_biens
     * @param array $prestations Format: ['id_prestation' => quantite, ...]
     * @return bool
     */
    public function updateBienPrestations($id_biens, $prestations) {
        try {
            $this->db->beginTransaction();
            
            // Supprimer toutes les prestations existantes
            $this->removeAllPrestationsFromBien($id_biens);
            
            // Ajouter les nouvelles prestations
            foreach ($prestations as $id_prestation => $quantite) {
                error_log("Ajout prestation: bien=$id_biens, prestation=$id_prestation, quantite=$quantite");
                $result = $this->addPrestationToBien($id_biens, $id_prestation, $quantite);
                error_log("Résultat ajout: " . ($result ? "OK" : "ERREUR"));
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("ERREUR updateBienPrestations: " . $e->getMessage());
            return false;
        }
    }

    // Méthodes requises par la classe abstraite Model
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO prestation (lib_prestation) VALUES (:lib_prestation)");
        return $stmt->execute(['lib_prestation' => $data['lib_prestation']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE prestation SET lib_prestation = :lib_prestation WHERE id_prestation = :id");
        return $stmt->execute([
            'lib_prestation' => $data['lib_prestation'],
            'id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM prestation WHERE id_prestation = :id");
        return $stmt->execute(['id' => $id]);
    }
}

?>
