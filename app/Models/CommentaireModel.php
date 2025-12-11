<?php

require_once __DIR__ . "/Model.php";

/**
 * Modèle pour gérer les commentaires sur les biens
 * 
 * @author GoodNight Team
 * @date 11/12/2025
 */
class CommentaireModel extends Model {
    
    protected $table = "commentaires";
    protected $primaryKey = "id_commentaire";

    /**
     * Récupère tous les commentaires d'un bien avec les informations de l'auteur
     * 
     * @param int $idBien ID du bien
     * @param string $statut Statut du commentaire ('publie', 'en_attente', 'rejete')
     * @param string $orderBy Ordre de tri (date_desc, date_asc, note_desc, note_asc)
     * @return array Liste des commentaires
     */
    public function getCommentairesByBien($idBien, $statut = 'publie', $orderBy = 'date_desc') {
        $orderClause = match($orderBy) {
            'date_asc' => 'c.date_creation ASC',
            'note_desc' => 'c.note DESC, c.date_creation DESC',
            'note_asc' => 'c.note ASC, c.date_creation DESC',
            default => 'c.date_creation DESC'
        };

        $sql = "SELECT c.*, 
                       l.nom_locataire, 
                       l.prenom_locataire,
                       l.pfp_loca as profile_picture
                FROM {$this->table} c
                INNER JOIN locataire l ON c.id_locataire = l.id_locataire
                WHERE c.id_biens = :id_bien 
                AND c.statut = :statut
                ORDER BY {$orderClause}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_bien' => $idBien,
            'statut' => $statut
        ]);
        
        return $stmt->fetchAll();
    }

    /**
     * Calcule la note moyenne d'un bien
     * 
     * @param int $idBien ID du bien
     * @return float|null Note moyenne ou null si aucun commentaire
     */
    public function getNoteMoyenne($idBien) {
        $sql = "SELECT AVG(note) as moyenne 
                FROM {$this->table} 
                WHERE id_biens = :id_bien 
                AND statut = 'publie' 
                AND note IS NOT NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_bien' => $idBien]);
        $result = $stmt->fetch();
        
        return $result['moyenne'] ? round($result['moyenne'], 1) : null;
    }

    /**
     * Compte le nombre de commentaires d'un bien
     * 
     * @param int $idBien ID du bien
     * @return int Nombre de commentaires
     */
    public function getNombreCommentaires($idBien) {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->table} 
                WHERE id_biens = :id_bien 
                AND statut = 'publie'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_bien' => $idBien]);
        $result = $stmt->fetch();
        
        return (int)$result['total'];
    }

    /**
     * Récupère la répartition des notes pour un bien
     * 
     * @param int $idBien ID du bien
     * @return array Tableau avec le nombre de commentaires par note (1 à 5)
     */
    public function getRepartitionNotes($idBien) {
        $sql = "SELECT note, COUNT(*) as nombre 
                FROM {$this->table} 
                WHERE id_biens = :id_bien 
                AND statut = 'publie' 
                AND note IS NOT NULL
                GROUP BY note
                ORDER BY note DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_bien' => $idBien]);
        $results = $stmt->fetchAll();
        
        // Initialiser toutes les notes à 0
        $repartition = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        
        foreach ($results as $row) {
            $repartition[$row['note']] = (int)$row['nombre'];
        }
        
        return $repartition;
    }

    /**
     * Vérifie si un utilisateur peut commenter un bien
     * (vérifie s'il a déjà eu une réservation terminée)
     * 
     * @param int $idBien ID du bien
     * @param int $idLocataire ID de l'utilisateur
     * @return bool True si l'utilisateur peut commenter
     */
    public function canUserComment($idBien, $idLocataire) {
        $sql = "SELECT COUNT(*) as total 
                FROM reservations 
                WHERE id_biens = :id_bien 
                AND id_locataire = :id_locataire 
                AND date_fin < NOW()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_bien' => $idBien,
            'id_locataire' => $idLocataire
        ]);
        $result = $stmt->fetch();
        
        return (int)$result['total'] > 0;
    }

    /**
     * Vérifie si un utilisateur a déjà commenté un bien
     * 
     * @param int $idBien ID du bien
     * @param int $idLocataire ID de l'utilisateur
     * @return bool True si l'utilisateur a déjà commenté
     */
    public function hasUserCommented($idBien, $idLocataire) {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->table} 
                WHERE id_biens = :id_bien 
                AND id_locataire = :id_locataire";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_bien' => $idBien,
            'id_locataire' => $idLocataire
        ]);
        $result = $stmt->fetch();
        
        return (int)$result['total'] > 0;
    }

    /**
     * Récupère le commentaire d'un utilisateur pour un bien spécifique
     * 
     * @param int $idBien ID du bien
     * @param int $idLocataire ID de l'utilisateur
     * @return array|false Commentaire ou false si non trouvé
     */
    public function getUserCommentaire($idBien, $idLocataire) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_biens = :id_bien 
                AND id_locataire = :id_locataire";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_bien' => $idBien,
            'id_locataire' => $idLocataire
        ]);
        
        return $stmt->fetch();
    }

    /**
     * Ajoute un nouveau commentaire
     * 
     * @param array $data Données du commentaire
     * @return int|false ID du commentaire créé ou false en cas d'erreur
     */
    public function create($data) {
        // Validation des données
        if (empty($data['id_biens']) || empty($data['id_locataire']) || empty($data['contenu'])) {
            return false;
        }

        // Vérifier que la note est valide (1-5) si fournie
        if (isset($data['note']) && ($data['note'] < 1 || $data['note'] > 5)) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} 
                (id_biens, id_locataire, note, titre, contenu, statut) 
                VALUES (:id_biens, :id_locataire, :note, :titre, :contenu, :statut)";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            'id_biens' => $data['id_biens'],
            'id_locataire' => $data['id_locataire'],
            'note' => $data['note'] ?? null,
            'titre' => $data['titre'] ?? null,
            'contenu' => $data['contenu'],
            'statut' => $data['statut'] ?? 'publie'
        ]);
        
        return $success ? $this->db->lastInsertId() : false;
    }

    /**
     * Modifie un commentaire existant
     * 
     * @param int $id ID du commentaire
     * @param array $data Nouvelles données
     * @return bool True en cas de succès
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET note = :note, 
                    titre = :titre, 
                    contenu = :contenu,
                    date_modification = NOW()
                WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'note' => $data['note'] ?? null,
            'titre' => $data['titre'] ?? null,
            'contenu' => $data['contenu']
        ]);
    }

    /**
     * Supprime un commentaire
     * 
     * @param int $id ID du commentaire
     * @return bool True en cas de succès
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Signale un commentaire comme inapproprié
     * 
     * @param int $id ID du commentaire
     * @return bool True en cas de succès
     */
    public function signaler($id) {
        $sql = "UPDATE {$this->table} 
                SET signale = 1 
                WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Modifie le statut d'un commentaire (pour l'administration)
     * 
     * @param int $id ID du commentaire
     * @param string $statut Nouveau statut ('publie', 'en_attente', 'rejete')
     * @return bool True en cas de succès
     */
    public function updateStatut($id, $statut) {
        $sql = "UPDATE {$this->table} 
                SET statut = :statut 
                WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'statut' => $statut
        ]);
    }

    /**
     * Récupère tous les commentaires signalés (pour l'administration)
     * 
     * @return array Liste des commentaires signalés
     */
    public function getCommentairesSignales() {
        $sql = "SELECT c.*, 
                       l.nom_locataire, 
                       l.prenom_locataire,
                       b.designation_bien
                FROM {$this->table} c
                INNER JOIN locataire l ON c.id_locataire = l.id_locataire
                INNER JOIN biens b ON c.id_biens = b.id_biens
                WHERE c.signale = 1
                ORDER BY c.date_creation DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * ==================================================
     * SYSTÈME DE LIKES
     * ==================================================
     */

    /**
     * Ajoute un like à un commentaire
     * 
     * @param int $idCommentaire ID du commentaire
     * @param int $idLocataire ID de l'utilisateur
     * @return bool True en cas de succès
     */
    public function addLike($idCommentaire, $idLocataire) {
        $sql = "INSERT INTO commentaire_likes (id_commentaire, id_locataire) 
                VALUES (:id_commentaire, :id_locataire)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_commentaire' => $idCommentaire,
                'id_locataire' => $idLocataire
            ]);
        } catch (PDOException $e) {
            // Si le like existe déjà (contrainte UNIQUE), retourner false
            return false;
        }
    }

    /**
     * Retire un like d'un commentaire
     * 
     * @param int $idCommentaire ID du commentaire
     * @param int $idLocataire ID de l'utilisateur
     * @return bool True en cas de succès
     */
    public function removeLike($idCommentaire, $idLocataire) {
        $sql = "DELETE FROM commentaire_likes 
                WHERE id_commentaire = :id_commentaire 
                AND id_locataire = :id_locataire";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_commentaire' => $idCommentaire,
            'id_locataire' => $idLocataire
        ]);
    }

    /**
     * Vérifie si un utilisateur a liké un commentaire
     * 
     * @param int $idCommentaire ID du commentaire
     * @param int $idLocataire ID de l'utilisateur
     * @return bool True si l'utilisateur a liké
     */
    public function hasUserLiked($idCommentaire, $idLocataire) {
        $sql = "SELECT COUNT(*) as total 
                FROM commentaire_likes 
                WHERE id_commentaire = :id_commentaire 
                AND id_locataire = :id_locataire";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_commentaire' => $idCommentaire,
            'id_locataire' => $idLocataire
        ]);
        $result = $stmt->fetch();
        
        return (int)$result['total'] > 0;
    }

    /**
     * Compte le nombre de likes d'un commentaire
     * 
     * @param int $idCommentaire ID du commentaire
     * @return int Nombre de likes
     */
    public function countLikes($idCommentaire) {
        $sql = "SELECT COUNT(*) as total 
                FROM commentaire_likes 
                WHERE id_commentaire = :id_commentaire";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_commentaire' => $idCommentaire]);
        $result = $stmt->fetch();
        
        return (int)$result['total'];
    }

    /**
     * Récupère le TOP 3 des commentaires les plus likés pour un bien
     * 
     * @param int $idBien ID du bien
     * @return array Top 3 des commentaires
     */
    public function getTop3MostLiked($idBien) {
        $sql = "SELECT c.*, 
                       l.nom_locataire, 
                       l.prenom_locataire,
                       l.pfp_loca as profile_picture,
                       COUNT(cl.id_like) as likes_count
                FROM {$this->table} c
                INNER JOIN locataire l ON c.id_locataire = l.id_locataire
                LEFT JOIN commentaire_likes cl ON c.id_commentaire = cl.id_commentaire
                WHERE c.id_biens = :id_bien 
                AND c.statut = 'publie'
                GROUP BY c.id_commentaire
                ORDER BY likes_count DESC, c.date_creation DESC
                LIMIT 3";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_bien' => $idBien]);
        
        return $stmt->fetchAll();
    }

    /**
     * Récupère les likes d'un utilisateur pour plusieurs commentaires
     * Utilisé pour afficher l'état des boutons like
     * 
     * @param array $commentairesIds Tableau des IDs de commentaires
     * @param int $idLocataire ID de l'utilisateur
     * @return array Tableau des IDs de commentaires likés
     */
    public function getUserLikes($commentairesIds, $idLocataire) {
        if (empty($commentairesIds)) {
            return [];
        }
        
        $placeholders = implode(',', array_fill(0, count($commentairesIds), '?'));
        $sql = "SELECT id_commentaire 
                FROM commentaire_likes 
                WHERE id_commentaire IN ($placeholders) 
                AND id_locataire = ?";
        
        $stmt = $this->db->prepare($sql);
        $params = array_merge($commentairesIds, [$idLocataire]);
        $stmt->execute($params);
        
        return array_column($stmt->fetchAll(), 'id_commentaire');
    }
}

