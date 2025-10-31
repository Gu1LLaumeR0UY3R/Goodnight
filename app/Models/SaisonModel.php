<?php

require_once __DIR__ . "/Model.php";

class SaisonModel extends Model {
    protected $table = 'Saison';

    protected $primaryKey = 'id_saison';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (lib_saison, date_debut, date_fin) VALUES (:lib_saison, :date_debut, :date_fin)");
        $stmt->execute([
            'lib_saison' => $data['lib_saison'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET lib_saison = :lib_saison, date_debut = :date_debut, date_fin = :date_fin WHERE id_saison = :id_saison";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'lib_saison' => $data['lib_saison'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'id_saison' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_saison = :id_saison");
        $stmt->execute(['id_saison' => $id]);
        return $stmt->rowCount();
    }

    /**
     * Détermine l'ID de la saison actuelle en fonction de la date du jour.
     * Nécessite que la table Saison ait les colonnes date_debut et date_fin.
     * @return int|null L'ID de la saison actuelle ou null si aucune saison n'est trouvée.
     */
    public function getCurrentSaisonId() {
        $currentDate = date('Y-m-d');
        $currentYear = date('Y');

        // La logique de détermination de la saison doit gérer les saisons qui chevauchent l'année (ex: Hiver)
        // La table `saison` contient maintenant `date_debut` et `date_fin` (sans l'année).
        $currentMonthDay = date('m-d');
        
        $stmt = $this->db->prepare("
            SELECT id_saison 
            FROM " . $this->table . " 
            WHERE 
                -- Cas normal : date_debut <= date_fin (saison dans la même année)
                (DATE_FORMAT(date_debut, '%m-%d') <= :cmd1 AND DATE_FORMAT(date_fin, '%m-%d') >= :cmd2)
                -- Cas de chevauchement d'année : date_debut > date_fin (ex: Décembre à Février)
                OR (DATE_FORMAT(date_debut, '%m-%d') > DATE_FORMAT(date_fin, '%m-%d') 
                    AND (DATE_FORMAT(date_debut, '%m-%d') <= :cmd3 OR DATE_FORMAT(date_fin, '%m-%d') >= :cmd4)
                )
            LIMIT 1
        ");

        $stmt->execute([
            'cmd1' => $currentMonthDay,
            'cmd2' => $currentMonthDay,
            'cmd3' => $currentMonthDay,
            'cmd4' => $currentMonthDay
        ]);
        $result = $stmt->fetch();
        
	        return $result ? $result['id_saison'] : null;
	    }

	    /**
	     * Détermine l'ID de la saison en fonction d'une date donnée.
	     * @param string $date La date à vérifier (format Y-m-d).
	     * @return int|null L'ID de la saison ou null si aucune saison n'est trouvée.
	     */
	    public function getSaisonIdByDate($date) {
	        $monthDay = date('m-d', strtotime($date));
	        
	        $stmt = $this->db->prepare("
	            SELECT id_saison 
	            FROM " . $this->table . " 
	            WHERE 
	                -- Cas normal : date_debut <= date_fin (saison dans la même année)
	                (DATE_FORMAT(date_debut, '%m-%d') <= :md1 AND DATE_FORMAT(date_fin, '%m-%d') >= :md2)
	                -- Cas de chevauchement d'année : date_debut > date_fin (ex: Décembre à Février)
	                OR (DATE_FORMAT(date_debut, '%m-%d') > DATE_FORMAT(date_fin, '%m-%d') 
	                    AND (DATE_FORMAT(date_debut, '%m-%d') <= :md3 OR DATE_FORMAT(date_fin, '%m-%d') >= :md4)
	                )
	            LIMIT 1
	        ");

	        $stmt->execute([
	            'md1' => $monthDay,
	            'md2' => $monthDay,
	            'md3' => $monthDay,
	            'md4' => $monthDay
	        ]);
	        $result = $stmt->fetch();
	        
	        return $result ? $result['id_saison'] : null;
	    }
	}
	
	?>
