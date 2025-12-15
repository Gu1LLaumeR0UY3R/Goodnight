<?php

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/SaisonModel.php";

class BienModel extends Model {
    protected $table = 'biens';
    protected $primaryKey = 'id_biens'; // Définir la clé primaire

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (designation_bien, rue_biens, complement_biens, superficie_biens, description_biens, animaux_biens, nb_couchage, id_TypeBien, id_commune, id_locataire, statut_validation, date_soumission) VALUES (:designation_bien, :rue_biens, :complement_biens, :superficie_biens, :description_biens, :animaux_biens, :nb_couchage, :id_TypeBien, :id_commune, :id_locataire, 'en_attente', NOW())");
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
        // Détecter si des champs critiques ont été modifiés
        $champsCritiquesModifies = $this->detectCriticalFieldsChanged($id, $data);
        
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
        ";
        
        // Si des champs critiques ont changé, remettre en attente
        if ($champsCritiquesModifies) {
            $sql .= ", statut_validation = 'en_attente', date_soumission = NOW()";
        }
        
        $sql .= " WHERE id_biens = :id_biens";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'designation_bien' => $data['designation_bien'],
            'rue_biens' => $data['rue_biens'],
            'complement_biens' => $data['complement_biens'],
            'superficie_biens' => $data['superficie_biens'],
            'description_biens' => $data['description_biens'],
            'animaux_biens' => $data['animaux_biens'],
            'nb_couchage' => $data['nb_couchage'],
            'id_TypeBien' => $data['id_TypeBien'],
            'id_commune' => $data['id_commune'],
            'id_locataire' => $data['id_locataire'],
            'id_biens' => $id
        ]);
        return $champsCritiquesModifies;
    }

    public function delete($id) {
        // Suppression des tarifs associés
        require_once __DIR__ . "/TarifModel.php";
        $tarifModel = new TarifModel();
        $tarifModel->deleteByBien($id);

        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_biens = :id_biens");
        $stmt->execute(['id_biens' => $id]);
        return $stmt->rowCount();
    }

    // Récupérer les biens d'un propriétaire
    public function getBiensByProprietaire($proprietaireId) {
        $sql = "
            SELECT 
                b.*,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo
            FROM biens b 
            WHERE b.id_locataire = :id_locataire
            ORDER BY b.designation_bien
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_locataire' => $proprietaireId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getById($id)
    {
        $query = "SELECT * FROM biens WHERE id_biens = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getBienWithDetailsById($id) {
        $saisonModel = new SaisonModel();
        $currentSaisonId = $saisonModel->getCurrentSaisonId();
        $currentYear = date('Y');

        $sql = "
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom,
                l.nom_locataire as proprietaire_nom,
                l.prenom_locataire as proprietaire_prenom,
                l.RaisonSociale as proprietaire_raison_sociale,
                IFNULL((SELECT prix_semaine FROM tarifs WHERE id_biens = b.id_biens AND annee = :currentYear AND id_saison = :currentSaisonId LIMIT 1), NULL) as prix_semaine
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune
            LEFT JOIN locataire l ON b.id_locataire = l.id_locataire
            WHERE b.id_biens = :id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'currentYear' => $currentYear,
            'currentSaisonId' => $currentSaisonId ?? 0 // Utiliser 0 si pas de saison trouvée
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBiensWithDetails() {
        $saisonModel = new SaisonModel();
        $currentSaisonId = $saisonModel->getCurrentSaisonId();
        $currentYear = date('Y');

        $sql = "
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom,
                c.ville_latitude_deg,
                c.ville_longitude_deg,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo,
                IFNULL((SELECT prix_semaine FROM tarifs WHERE id_biens = b.id_biens AND annee = :currentYear AND id_saison = :currentSaisonId LIMIT 1), NULL) as prix_semaine
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune
            WHERE b.statut_validation = 'valide'
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'currentYear' => $currentYear,
            'currentSaisonId' => $currentSaisonId ?? 0 // Utiliser 0 si pas de saison trouvée
        ]);
        return $stmt->fetchAll();
    }

    public function getBiensByType($typeBienId) {
        $saisonModel = new SaisonModel();
        $currentSaisonId = $saisonModel->getCurrentSaisonId();
        $currentYear = date('Y');

        $sql = "
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo,
                IFNULL((SELECT prix_semaine FROM tarifs WHERE id_biens = b.id_biens AND annee = :currentYear AND id_saison = :currentSaisonId LIMIT 1), NULL) as prix_semaine
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune 
            WHERE b.id_TypeBien = :id_TypeBien
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_TypeBien' => $typeBienId,
            'currentYear' => $currentYear,
            'currentSaisonId' => $currentSaisonId ?? 0
        ]);
        return $stmt->fetchAll();
    }

    public function searchBiensWithFilters($filters = []) {
        require_once __DIR__ . "/SaisonModel.php";
        $saisonModel = new SaisonModel();
        $currentSaisonId = $saisonModel->getCurrentSaisonId();
        $currentYear = date('Y');

        $sql = "
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom,
                c.ville_latitude_deg,
                c.ville_longitude_deg,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo,
                IFNULL((SELECT prix_semaine FROM tarifs WHERE id_biens = b.id_biens AND annee = :currentYear AND id_saison = :currentSaisonId LIMIT 1), NULL) as prix_semaine
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune
            LEFT JOIN tarifs t ON b.id_biens = t.id_biens AND t.annee = :currentYear2 AND t.id_saison = :currentSaisonId2
            WHERE b.statut_validation = 'valide'
        ";

        $params = [
            'currentYear' => $currentYear,
            'currentSaisonId' => $currentSaisonId ?? 0,
            'currentYear2' => $currentYear,
            'currentSaisonId2' => $currentSaisonId ?? 0
        ];

        // Filtre par commune
        if (!empty($filters['commune'])) {
            $sql .= " AND c.ville_nom LIKE :commune";
            $params['commune'] = '%' . $filters['commune'] . '%';
        }

        // Filtre par types de bien (OR: biens correspondant à au moins un des types sélectionnés)
        if (!empty($filters['types_bien']) && is_array($filters['types_bien'])) {
            $types = array_values(array_filter($filters['types_bien'], function($v){ return is_numeric($v); }));
            if (!empty($types)) {
                $placeholders = [];
                foreach ($types as $idx => $tid) {
                    $ph = ":type_bien_" . $idx;
                    $placeholders[] = $ph;
                    $params[$ph] = $tid;
                }
                $inClause = implode(", ", $placeholders);
                $sql .= " AND b.id_TypeBien IN (" . $inClause . ")";
            }
        }

        // Filtre par superficie minimale
        if (!empty($filters['superficie_min'])) {
            $sql .= " AND b.superficie_biens >= :superficie_min";
            $params['superficie_min'] = $filters['superficie_min'];
        }

        // Filtre par superficie maximale
        if (!empty($filters['superficie_max'])) {
            $sql .= " AND b.superficie_biens <= :superficie_max";
            $params['superficie_max'] = $filters['superficie_max'];
        }

        // Filtre par prix minimal
        if (!empty($filters['prix_min'])) {
            $sql .= " AND t.prix_semaine >= :prix_min";
            $params['prix_min'] = $filters['prix_min'];
        }

        // Filtre par prix maximal
        if (!empty($filters['prix_max'])) {
            $sql .= " AND t.prix_semaine <= :prix_max";
            $params['prix_max'] = $filters['prix_max'];
        }

        // Filtre par prestations (OR: biens possédant au moins une des prestations sélectionnées)
        if (!empty($filters['prestations']) && is_array($filters['prestations'])) {
            // Nettoyer et préparer les placeholders
            $prestations = array_values(array_filter($filters['prestations'], function($v){ return is_numeric($v); }));
            if (!empty($prestations)) {
                $placeholders = [];
                foreach ($prestations as $idx => $pid) {
                    $ph = ":prestation_" . $idx;
                    $placeholders[] = $ph;
                    $params[$ph] = $pid;
                }
                $inClause = implode(", ", $placeholders);
                $sql .= " AND EXISTS (SELECT 1 FROM se_compose sc WHERE sc.id_biens = b.id_biens AND sc.id_prestation IN (" . $inClause . "))";
            }
        }

        $sql .= " GROUP BY b.id_biens";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function searchBiensByCommune($communeNom) {
        require_once __DIR__ . "/SaisonModel.php";
        $saisonModel = new SaisonModel();
        $currentSaisonId = $saisonModel->getCurrentSaisonId();
        $currentYear = date('Y');

        $sql = "
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo,
                IFNULL((SELECT prix_semaine FROM tarifs WHERE id_biens = b.id_biens AND annee = :currentYear AND id_saison = :currentSaisonId LIMIT 1), NULL) as prix_semaine
            FROM biens b 
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien 
            LEFT JOIN commune c ON b.id_commune = c.id_commune 
            WHERE c.ville_nom LIKE :communeNom
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'communeNom' => '%' . $communeNom . '%',
            'currentYear' => $currentYear,
            'currentSaisonId' => $currentSaisonId ?? 0
        ]);
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

    // Détecte si des champs critiques ont été modifiés
    private function detectCriticalFieldsChanged($id, $newData) {
        $currentData = $this->getById($id);
        if (!$currentData) return false;
        
        // Champs critiques selon Option C
        $criticalFields = ['designation_bien', 'description_biens', 'id_TypeBien'];
        
        foreach ($criticalFields as $field) {
            if (isset($newData[$field]) && $currentData[$field] != $newData[$field]) {
                return true;
            }
        }
        
        return false;
    }
    
    // Récupère les biens en attente de validation
    public function getBiensEnAttente() {
        $sql = "
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.ville_nom as commune_nom,
                CASE 
                    WHEN l.nom_locataire IS NOT NULL AND l.prenom_locataire IS NOT NULL THEN CONCAT(l.prenom_locataire, ' ', l.nom_locataire)
                    WHEN l.RaisonSociale IS NOT NULL THEN l.RaisonSociale
                    ELSE 'Non défini'
                END AS proprietaire,
                l.email_locataire
            FROM biens b
            LEFT JOIN Type_Bien tb ON b.id_TypeBien = tb.id_typebien
            LEFT JOIN commune c ON b.id_commune = c.id_commune
            LEFT JOIN locataire l ON b.id_locataire = l.id_locataire
            WHERE b.statut_validation = 'en_attente'
            ORDER BY b.date_soumission DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Valider un bien
    public function validerBien($id, $adminId) {
        $sql = "UPDATE biens SET statut_validation = 'valide', date_validation = NOW(), id_admin_validateur = :adminId WHERE id_biens = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'adminId' => $adminId]);
    }
    
    // Refuser un bien
    public function refuserBien($id, $adminId, $motif = null) {
        $sql = "UPDATE biens SET statut_validation = 'refuse', date_validation = NOW(), id_admin_validateur = :adminId, motif_refus = :motif WHERE id_biens = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'adminId' => $adminId, 'motif' => $motif]);
    }
    
    // Compter les biens en attente
    public function countBiensEnAttente() {
        $sql = "SELECT COUNT(*) as count FROM biens WHERE statut_validation = 'en_attente'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
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

    // Mettre à jour le statut de validation d'un bien
    public function updateStatutValidation($id, $statut, $adminId = null) {
        $allowedStatuts = ['en_attente', 'valide', 'refuse'];
        if (!in_array($statut, $allowedStatuts)) {
            return false;
        }

        $sql = "UPDATE biens SET statut_validation = :statut, date_validation = NOW()";
        
        $params = [
            'id' => $id,
            'statut' => $statut
        ];

        if ($adminId !== null) {
            $sql .= ", id_admin_validateur = :adminId";
            $params['adminId'] = $adminId;
        }

        $sql .= " WHERE id_biens = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Récupérer les biens par IDs (pour les favoris)
     */
    public function getBiensByIds($ids) {
        if (empty($ids)) {
            return [];
        }

        // Nettoyer et valider les IDs
        $ids = array_filter($ids, fn($id) => is_numeric($id));
        if (empty($ids)) {
            return [];
        }

        // Créer les placeholders pour la requête
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT 
                b.*,
                tb.desc_type_bien as type_bien_nom,
                c.nom_commune as commune_nom,
                (SELECT lien_photo FROM photos WHERE id_biens = b.id_biens ORDER BY id_photo ASC LIMIT 1) as premiere_photo
            FROM biens b
            LEFT JOIN typebien tb ON b.id_TypeBien = tb.id_typebien
            LEFT JOIN communes c ON b.id_commune = c.id_commune
            WHERE b.id_biens IN ($placeholders)
            AND b.statut_validation = 'valide'
            ORDER BY b.designation_bien
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
