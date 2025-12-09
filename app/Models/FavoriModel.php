<?php

require_once __DIR__ . '/Model.php';

class FavoriModel extends Model {
    protected $table = 'favoris';

    /**
     * Ajouter un bien aux favoris
     */
    public function addFavori($id_locataire, $id_biens) {
        $sql = "INSERT INTO " . $this->table . " (id_locataire, id_biens) VALUES (:id_locataire, :id_biens)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_locataire' => $id_locataire,
            ':id_biens' => $id_biens
        ]);
    }

    /**
     * Retirer un bien des favoris
     */
    public function removeFavori($id_locataire, $id_biens) {
        $sql = "DELETE FROM " . $this->table . " WHERE id_locataire = :id_locataire AND id_biens = :id_biens";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_locataire' => $id_locataire,
            ':id_biens' => $id_biens
        ]);
    }

    /**
     * Vérifier si un bien est dans les favoris
     */
    public function isFavori($id_locataire, $id_biens) {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table . " 
                WHERE id_locataire = :id_locataire AND id_biens = :id_biens";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_locataire' => $id_locataire,
            ':id_biens' => $id_biens
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Récupérer les IDs des favoris d'un utilisateur
     */
    public function getFavoriIdsByUserId($id_locataire) {
        $sql = "SELECT id_biens FROM " . $this->table . " 
                WHERE id_locataire = :id_locataire 
                ORDER BY date_ajout DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_locataire' => $id_locataire]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($results, 'id_biens');
    }

    /**
     * Récupérer les favoris complets d'un utilisateur avec les détails des biens
     */
    public function getFavorisByUserId($id_locataire) {
        $sql = "
            SELECT 
                f.id_favori,
                f.date_ajout,
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom,
                l.nom_locataire as proprietaire_nom,
                l.prenom_locataire as proprietaire_prenom,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo
            FROM " . $this->table . " f
            JOIN biens b ON f.id_biens = b.id_biens
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien
            LEFT JOIN commune c ON b.id_commune = c.id_commune
            LEFT JOIN locataire l ON b.id_locataire = l.id_locataire
            WHERE f.id_locataire = :id_locataire
            ORDER BY f.date_ajout DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_locataire' => $id_locataire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprimer tous les favoris d'un utilisateur
     */
    public function clearUserFavoris($id_locataire) {
        $sql = "DELETE FROM " . $this->table . " WHERE id_locataire = :id_locataire";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_locataire' => $id_locataire]);
    }

    /**
     * Compter les favoris d'un utilisateur
     */
    public function countFavorisByUserId($id_locataire) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE id_locataire = :id_locataire";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_locataire' => $id_locataire]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Implémentation de la méthode abstraite create
     */
    public function create($data) {
        return $this->addFavori($data['id_locataire'], $data['id_biens']);
    }

    /**
     * Implémentation de la méthode abstraite update
     */
    public function update($id, $data) {
        // Les favoris ne sont généralement pas modifiés, seulement ajoutés/supprimés
        // Cette méthode est une implémentation minimale
        return false;
    }

    /**
     * Implémentation de la méthode abstraite delete
     */
    public function delete($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id_favori = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
