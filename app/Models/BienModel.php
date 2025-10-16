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
        $sql = "
            UPDATE " . $this->table . " 
            SET 
                designation_bien = :designation_bien,
                rue_biens = :rue_biens,
                complement_biens = :complement_biens,
                superficie_biens = :superficie_biens,
                description_biens = :description_biens,
                animaux_biens = :animaux_biens,
                nb_couchage = :nb_couchage,
                id_TypeBien = :id_TypeBien,
                id_commune = :id_commune,
                id_locataire = :id_locataire
            WHERE id_biens = :id_biens
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'designation_bien' => $data['designation_bien'],
            'rue_biens' => $data['rue_biens'],
            'complement_biens' => $data['complement_biens'],
            'superficie_biens' => $data['superficie_biens'],
            'description_biens' => $data['description_biens'],
            'animaux_biens' => $data['animaux_biens'], // Mise à jour du champ animaux_biens
            'nb_couchage' => $data['nb_couchage'],
            'id_TypeBien' => $data['id_TypeBien'],
            'id_commune' => $data['id_commune'],
            'id_locataire' => $data['id_locataire'],
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

    public function getBienWithRole($bienId = null) {
        $query = "
            SELECT l.*
            FROM locataire l
            JOIN user_role ur ON l.id_locataire = ur.id_locataire
            WHERE ur.id_roles = 2
        ";

        $params = [];
        if ($bienId !== null) {
            if (!is_numeric($bienId) || $bienId <= 0) {
                throw new InvalidArgumentException("L'identifiant du bien doit être un entier positif.");
            }
            $query .= " AND l.id_locataire IN (
                SELECT b.id_locataire
                FROM biens b
                WHERE b.id_biens = :id_biens
            )";
            $params['id_biens'] = $bienId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        if ($bienId !== null) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un seul propriétaire pour un bien spécifique
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne tous les propriétaires
        }
    }

    public function getBienWithPPRole($bienId = null) {
        $query = "
            SELECT l.*
            FROM locataire l
            JOIN user_role ur ON l.id_locataire = ur.id_locataire
            WHERE ur.id_roles = 2
            AND l.nom_locataire IS NOT NULL
            AND l.prenom_locataire IS NOT NULL
        ";

        $params = [];
        if ($bienId !== null) {
            if (!is_numeric($bienId) || $bienId <= 0) {
                throw new InvalidArgumentException("L'identifiant du bien doit être un entier positif.");
            }
            $query .= " AND l.id_locataire IN (
                SELECT b.id_locataire
                FROM biens b
                WHERE b.id_biens = :id_biens
            )";
            $params['id_biens'] = $bienId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        if ($bienId !== null) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne une seule personne physique pour un bien spécifique
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne toutes les personnes physiques
        }
    }

    public function getBienWithPMRole($bienId = null) {
        $query = "
            SELECT l.*
            FROM locataire l
            JOIN user_role ur ON l.id_locataire = ur.id_locataire
            WHERE ur.id_roles = 2
            AND l.RaisonSociale IS NOT NULL
            AND l.Siret IS NOT NULL
        ";

        $params = [];
        if ($bienId !== null) {
            if (!is_numeric($bienId) || $bienId <= 0) {
                throw new InvalidArgumentException("L'identifiant du bien doit être un entier positif.");
            }
            $query .= " AND l.id_locataire IN (
                SELECT b.id_locataire
                FROM biens b
                WHERE b.id_biens = :id_biens
            )";
            $params['id_biens'] = $bienId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        if ($bienId !== null) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne une seule personne morale pour un bien spécifique
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne toutes les personnes morales
        }
    }

    public function getBiensWithProprietaireDetails() {
        $query = "
            SELECT 
                b.*,
                CASE 
                    WHEN l.nom_locataire IS NOT NULL AND l.prenom_locataire IS NOT NULL THEN CONCAT(l.prenom_locataire, ' ', l.nom_locataire)
                    WHEN l.RaisonSociale IS NOT NULL THEN l.RaisonSociale
                    ELSE 'Non défini'
                END AS proprietaire
            FROM biens b
            LEFT JOIN locataire l ON b.id_locataire = l.id_locataire
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
