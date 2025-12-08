<?php

require_once __DIR__ . "/Model.php";

class SignalementModel extends Model {
    protected $table = "signalements";

    // Créer un nouveau signalement
    public function create($data) {
        $sql = "INSERT INTO signalements 
                (id_biens, id_locataire, email_signaleur, motif, description, date_signalement) 
                VALUES (:id_biens, :id_locataire, :email_signaleur, :motif, :description, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_biens' => $data['id_biens'],
            'id_locataire' => $data['id_locataire'] ?? null,
            'email_signaleur' => $data['email_signaleur'] ?? null,
            'motif' => $data['motif'],
            'description' => $data['description'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    // Récupérer tous les signalements en attente
    public function getSignalementsEnAttente() {
        $sql = "
            SELECT 
                s.*,
                b.designation_bien,
                b.id_locataire as id_proprietaire,
                CASE 
                    WHEN l.nom_locataire IS NOT NULL AND l.prenom_locataire IS NOT NULL 
                    THEN CONCAT(l.prenom_locataire, ' ', l.nom_locataire)
                    WHEN l.RaisonSociale IS NOT NULL 
                    THEN l.RaisonSociale
                    ELSE 'Non défini'
                END AS proprietaire,
                CASE 
                    WHEN signaleur.nom_locataire IS NOT NULL 
                    THEN CONCAT(signaleur.prenom_locataire, ' ', signaleur.nom_locataire)
                    WHEN s.email_signaleur IS NOT NULL 
                    THEN s.email_signaleur
                    ELSE 'Anonyme'
                END AS signaleur
            FROM signalements s
            LEFT JOIN biens b ON s.id_biens = b.id_biens
            LEFT JOIN locataire l ON b.id_locataire = l.id_locataire
            LEFT JOIN locataire signaleur ON s.id_locataire = signaleur.id_locataire
            WHERE s.statut = 'en_attente'
            ORDER BY s.date_signalement DESC
        ";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Compter les signalements en attente
    public function countSignalementsEnAttente() {
        $sql = "SELECT COUNT(*) as count FROM signalements WHERE statut = 'en_attente'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    // Compter les signalements pour un bien spécifique
    public function countSignalementsByBien($idBiens) {
        $sql = "SELECT COUNT(*) as count FROM signalements WHERE id_biens = :id_biens AND statut = 'en_attente'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_biens' => $idBiens]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    // Marquer un signalement comme traité
    public function traiterSignalement($id, $adminId, $commentaire = null) {
        $sql = "UPDATE signalements 
                SET statut = 'traite', 
                    date_traitement = NOW(), 
                    id_admin_traitant = :adminId,
                    commentaire_admin = :commentaire
                WHERE id_signalement = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'adminId' => $adminId,
            'commentaire' => $commentaire
        ]);
    }

    // Rejeter un signalement
    public function rejeterSignalement($id, $adminId, $commentaire = null) {
        $sql = "UPDATE signalements 
                SET statut = 'rejete', 
                    date_traitement = NOW(), 
                    id_admin_traitant = :adminId,
                    commentaire_admin = :commentaire
                WHERE id_signalement = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'adminId' => $adminId,
            'commentaire' => $commentaire
        ]);
    }

    // Récupérer un signalement par ID
    public function getById($id) {
        $sql = "
            SELECT 
                s.*,
                b.designation_bien,
                b.description_biens,
                b.id_locataire as id_proprietaire,
                CASE 
                    WHEN l.nom_locataire IS NOT NULL AND l.prenom_locataire IS NOT NULL 
                    THEN CONCAT(l.prenom_locataire, ' ', l.nom_locataire)
                    WHEN l.RaisonSociale IS NOT NULL 
                    THEN l.RaisonSociale
                    ELSE 'Non défini'
                END AS proprietaire,
                CASE 
                    WHEN signaleur.nom_locataire IS NOT NULL 
                    THEN CONCAT(signaleur.prenom_locataire, ' ', signaleur.nom_locataire)
                    WHEN s.email_signaleur IS NOT NULL 
                    THEN s.email_signaleur
                    ELSE 'Anonyme'
                END AS signaleur
            FROM signalements s
            LEFT JOIN biens b ON s.id_biens = b.id_biens
            LEFT JOIN locataire l ON b.id_locataire = l.id_locataire
            LEFT JOIN locataire signaleur ON s.id_locataire = signaleur.id_locataire
            WHERE s.id_signalement = :id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Vérifier si un utilisateur a déjà signalé ce bien
    public function hasUserReported($idBiens, $idLocataire) {
        $sql = "SELECT COUNT(*) as count 
                FROM signalements 
                WHERE id_biens = :id_biens 
                  AND id_locataire = :id_locataire 
                  AND statut = 'en_attente'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_biens' => $idBiens,
            'id_locataire' => $idLocataire
        ]);
        
        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }

    // Implémentation requise de la classe abstraite Model
    public function update($id, $data) {
        // Les signalements sont mis à jour via traiterSignalement() et rejeterSignalement()
        // Cette méthode n'est pas utilisée directement
        return false;
    }

    public function delete($id) {
        $sql = "DELETE FROM signalements WHERE id_signalement = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
