<?php

require_once __DIR__ . "/Model.php";

class ReservationModel extends Model {
    protected $table = 'Reservations';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (date_debut, date_fin, id_users, id_biens, id_tarif) VALUES (:date_debut, :date_fin, :id_users, :id_biens, :id_tarif)");
        $stmt->execute([
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'id_users' => $data['id_users'],
            'id_biens' => $data['id_biens'],
            'id_tarif' => $data['id_tarif']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET date_debut = :date_debut, date_fin = :date_fin, id_locataire = :id_locataire, id_biens = :id_biens, id_tarif = :id_tarif WHERE id_reservation = :id_reservation";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'id_locataire' => $data['id_locataire'],
            'id_biens' => $data['id_biens'],
            'id_tarif' => $data['id_tarif'],
            'id_reservation' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_reservation = :id_reservation");
        $stmt->execute(['id_reservation' => $id]);
        return $stmt->rowCount();
    }

    public function getReservationsByUser($userId) {
        $stmt = $this->db->prepare("SELECT r.*, b.designation_bien, b.rue_biens, c.ville_nom FROM Reservations r JOIN Biens b ON r.id_biens = b.id_biens JOIN commune c ON b.id_commune = c.id_commune WHERE r.id_locataire = :id_locataire");
        $stmt->execute(['id_locataire' => $userId]);
        return $stmt->fetchAll();
    }

    public function getReservationsByProprietaire($proprietaireId) {
        $stmt = $this->db->prepare("SELECT r.*, b.designation_bien, b.rue_biens, c.ville_nom, Locataire.nom_locataire, Locataire.prenom_locataire FROM Reservations r JOIN Biens b ON r.id_biens = b.id_biens JOIN commune c ON b.id_commune = c.id_commune JOIN Locataire ON r.id_Locataire = u.id_locataire WHERE b.id_Locataire = :id_Locataire");
        $stmt->execute(['id_proprietaire' => $proprietaireId]);
        return $stmt->fetchAll();
    }
}

?>
