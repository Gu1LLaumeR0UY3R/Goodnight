<?php

require_once __DIR__ . "/Model.php";

class ReservationModel extends Model {
    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Vérifie si une réservation chevauche des réservations existantes pour un bien donné.
     * @param int $id_biens L'ID du bien.
     * @param string $date_debut Date de début de la nouvelle réservation (format Y-m-d).
     * @param string $date_fin Date de fin de la nouvelle réservation (format Y-m-d).
     * @param int|null $exclude_id_reservation ID de la réservation à exclure de la vérification (pour la modification).
     * @return bool Vrai si un chevauchement est trouvé, Faux sinon.
     */
    public function hasOverlap($id_biens, $date_debut, $date_fin, $exclude_id_reservation = null) {
        // La condition de chevauchement est : (Date de début A < Date de fin B) AND (Date de fin A > Date de début B)
        // Nous vérifions si la nouvelle période [date_debut, date_fin] chevauche une période existante [date_debut_existante, date_fin_existante]
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

    /**
     * Crée une nouvelle réservation.
     * @param array $data Les données de la réservation (id_biens, id_locataire, date_debut, date_fin).
     * @return int L'ID de la nouvelle réservation.
     */
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
	            'id_tarif' => $data['id_tarif'] ?? null // Ajout de id_tarif
	        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Récupère les réservations d'un locataire avec les détails du bien.
     * @param int $id_locataire L'ID du locataire.
     * @return array Les réservations.
     */
    public function getReservationsByLocataire($id_locataire) {
        $sql = "
            SELECT 
                r.*,
                b.designation_bien,
                b.rue_biens,
                c.ville_nom as commune_nom,
                l.nom_locataire, -- Ajout des colonnes demandées
                l.prenom_locataire, -- Ajout des colonnes demandées
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo
            FROM " . $this->table . " r
            JOIN biens b ON r.id_biens = b.id_biens
            JOIN commune c ON b.id_commune = c.id_commune
            JOIN locataire l ON r.id_locataire = l.id_locataire -- Ajout de la jointure pour les détails du locataire
            WHERE r.id_locataire = :id_locataire
            ORDER BY r.date_debut DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_locataire' => $id_locataire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère toutes les réservations.
     * @return array Les réservations.
     */
    /**
     * Récupère les réservations pour les biens d'un propriétaire avec les détails du locataire.
     * @param int $id_locataire L'ID du propriétaire (qui est un locataire avec le rôle Propriétaire).
     * @return array Les réservations.
     */
    public function hasActiveReservation($id_locataire, $id_biens) {
        $sql = "
            SELECT COUNT(*) 
            FROM " . $this->table . " 
            WHERE 
                id_locataire = :id_locataire AND 
                id_biens = :id_biens AND 
                statut IN (
                'en_attente
                ', 
                'confirmee
                ') AND
                date_fin >= CURDATE()
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_locataire' => $id_locataire,
            'id_biens' => $id_biens
        ]);
        return $stmt->fetchColumn() > 0;
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
            JOIN locataire l ON r.id_locataire = l.id_locataire -- Le locataire qui a réservé
            WHERE b.id_locataire = :id_locataire -- Le propriétaire
            ORDER BY r.date_debut DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_locataire' => $id_locataire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère toutes les réservations.
     * @return array Les réservations.
     */
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

    /**
     * Annule une réservation.
     * @param int $id_reservation L'ID de la réservation.
     * @return int Le nombre de lignes affectées (1 si succès, 0 sinon).
     */
    public function cancelReservation($id_reservation) {
        $sql = "
            UPDATE " . $this->table . " 
            SET statut = 'annulee'
            WHERE id_reservation = :id_reservation AND statut = 'en_attente'
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_reservation' => $id_reservation]);
        return $stmt->rowCount();
    }

    // Implémentation des méthodes abstraites de Model.php
    public function create($data) {
        // Cette méthode n'est pas utilisée directement, on utilise createReservation
        throw new \Exception("La méthode create n'est pas implémentée pour ReservationModel. Utilisez createReservation.");
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
        // Implémentation de base pour satisfaire l'abstraction
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
?>
