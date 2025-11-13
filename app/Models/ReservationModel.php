<?php
require_once __DIR__ . "/Model.php";

class ReservationModel extends Model {
    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';

    public function __construct() {
        parent::__construct();
    }

    public function hasOverlap($id_biens, $date_debut, $date_fin, $exclude_id_reservation = null) {
        $sql = "
            SELECT COUNT(*)
            FROM " . $this->table . "
            WHERE
                id_biens = :id_biens AND
                (
                    (:date_debut < date_fin AND :date_fin > date_debut)
                )
        ";
       
        $params = [
            'id_biens' => $id_biens,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ];
        if ($exclude_id_reservation !== null) {
            $sql .= " AND id_reservation != :exclude_id_reservation";
            $params['exclude_id_reservation'] = $exclude_id_reservation;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
       
        return $stmt->fetchColumn() > 0;
    }

    public function createReservation($data) {
        $sql = "
            INSERT INTO " . $this->table . " (id_biens, id_locataire, date_debut, date_fin, id_tarif)
            VALUES (:id_biens, :id_locataire, :date_debut, :date_fin, :id_tarif)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_biens' => $data['id_biens'],
            'id_locataire' => $data['id_locataire'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'id_tarif' => $data['id_tarif'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function getReservationsByLocataire($id_locataire) {
        $sql = "
            SELECT
                r.*,
                b.designation_bien,
                b.rue_biens,
                c.ville_nom as commune_nom,
                l.nom_locataire,
                l.prenom_locataire,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo
            FROM " . $this->table . " r
            JOIN biens b ON r.id_biens = b.id_biens
            JOIN commune c ON b.id_commune = c.id_commune
            JOIN locataire l ON r.id_locataire = l.id_locataire
            WHERE r.id_locataire = :id_locataire
            ORDER BY r.date_debut DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_locataire' => $id_locataire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationsByProprietaire($id_locataire) {
        $sql = "
            SELECT
                r.*,
                b.designation_bien,
                b.rue_biens,
                c.ville_nom as commune_nom,
                l.nom_locataire,
                l.prenom_locataire,
                l.RaisonSociale
            FROM " . $this->table . " r
            JOIN biens b ON r.id_biens = b.id_biens
            JOIN commune c ON b.id_commune = c.id_commune
            JOIN locataire l ON r.id_locataire = l.id_locataire
            WHERE b.id_locataire = :id_locataire
            ORDER BY r.date_debut DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_locataire' => $id_locataire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReservations() {
        $sql = "
            SELECT
                r.*,
                b.designation_bien,
                b.rue_biens,
                c.ville_nom as commune_nom,
                l.nom_locataire,
                l.prenom_locataire,
                l.RaisonSociale
            FROM " . $this->table . " r
            JOIN biens b ON r.id_biens = b.id_biens
            JOIN commune c ON b.id_commune = c.id_commune
            JOIN locataire l ON r.id_locataire = l.id_locataire
            ORDER BY r.date_debut DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancelReservation($id_reservation, $id_locataire) {
        $sql = "
            DELETE FROM " . $this->table . "
            WHERE id_reservation = :id_reservation AND id_locataire = :id_locataire
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_reservation' => $id_reservation,
            'id_locataire' => $id_locataire
        ]);
        return $stmt->rowCount();
    }

    public function hasReservationForBien($id_locataire, $id_biens) {
        $sql = "
            SELECT COUNT(*)
            FROM " . $this->table . "
            WHERE
                id_locataire = :id_locataire AND
                id_biens = :id_biens AND
                date_fin >= CURDATE()
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_locataire' => $id_locataire,
            'id_biens' => $id_biens
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function create($data) {
        // Plus d'exception : appelle createReservation
        return $this->createReservation($data);
    }

    public function update($id, $data) {
        $sql = "
            UPDATE " . $this->table . "
            SET id_biens = :id_biens, id_locataire = :id_locataire, date_debut = :date_debut, date_fin = :date_fin, id_tarif = :id_tarif
            WHERE id_reservation = :id_reservation
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_biens' => $data['id_biens'],
            'id_locataire' => $data['id_locataire'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'id_tarif' => $data['id_tarif'],
            'id_reservation' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
?>