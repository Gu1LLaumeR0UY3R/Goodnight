<?php
require_once __DIR__ . "/Model.php";

class CadreModel extends Model {
    protected $table = 'cadres';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'chemin_fichier', 'description'];

    /**
     * Récupérer tous les cadres
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un cadre par son ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupérer un cadre par son chemin fichier
     */
    public function getByPath($path) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE chemin_fichier = :path");
        $stmt->execute(['path' => $path]);
        return $stmt->fetch();
    }

    /**
     * Créer un nouveau cadre
     */
    public function create($data) {
        $data['date_creation'] = date('Y-m-d H:i:s');
        $data['date_modification'] = date('Y-m-d H:i:s');
        
        $fields = array_keys($data);
        $placeholders = array_map(fn($field) => ':' . $field, $fields);
        
        $query = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute($data);
    }

    /**
     * Supprimer un cadre
     */
    public function delete($id) {
        // Récupérer d'abord le cadre pour connaître le chemin fichier
        $cadre = $this->getById($id);
        if ($cadre && $cadre['chemin_fichier']) {
            // Supprimer le fichier physique
            $filePath = __DIR__ . '/../../public' . $cadre['chemin_fichier'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Supprimer de la base de données
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Vérifier si un chemin fichier existe déjà
     */
    public function pathExists($path, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE chemin_fichier = :path AND id != :id");
            $result = $stmt->execute(['path' => $path, 'id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE chemin_fichier = :path");
            $result = $stmt->execute(['path' => $path]);
        }
        
        $row = $stmt->fetch();
        return $row['count'] > 0;
    }

    /**
     * Mettre à jour un cadre (pour les enfants qui le voudraient)
     */
    public function update($id, $data) {
        $data['date_modification'] = date('Y-m-d H:i:s');
        
        $fields = array_map(fn($field) => "{$field} = :{$field}", array_keys($data));
        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute($data);
    }
}
?>
