<?php
// app/Controllers/CalendarController.php

class CalendarController {
    private $reservationModel;
    private $bienModel;

    public function __construct() {
        // Vérification session + rôle (compatible avec ton système AuthMiddleware si tu l'utilises)
        if (!isset($_SESSION['user_id']) || 
            !isset($_SESSION['user_roles']) || 
            !in_array('Propriétaire', $_SESSION['user_roles'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Accès refusé - Rôle Propriétaire requis']);
            exit;
        }

        // Utilise l'autoloader existant (pas besoin de require_once ici, grâce à spl_autoload_register)
        $this->bienModel = new BienModel();
        $this->reservationModel = new ReservationModel();
    }

    public function events() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); // Pour les fetch JS si besoin (optionnel)

        $proprietaireId = (int) $_SESSION['user_id'];
        $bienIdFilter = isset($_GET['bien']) ? (int) $_GET['bien'] : null;

        try {
            // 1. Récup biens du proprio pour mapping des noms
            $biens = $this->bienModel->getBiensByProprietaire($proprietaireId);
            $biensMap = [];
            foreach ($biens as $bien) {
                $biensMap[$bien['id_biens']] = $bien['designation_bien'] . 
                    (!empty($bien['rue_biens']) ? ' (' . $bien['rue_biens'] . ')' : '');
            }

            // 2. Requête SQL optimisée (utilise getDb() pour éviter l'erreur protected)
            $sql = "
                SELECT 
                    r.id_reservation, r.date_debut, r.date_fin, r.id_biens,
                    b.designation_bien, b.rue_biens,
                    l.prenom_locataire, l.nom_locataire, l.RaisonSociale
                FROM reservations r
                INNER JOIN biens b ON r.id_biens = b.id_biens
                INNER JOIN locataire l ON r.id_locataire = l.id_locataire
                WHERE b.id_locataire = :proprietaireId
                  AND r.date_debut >= CURDATE()  -- Optionnel : ne montrer que les futures réservations
            ";
            $params = [':proprietaireId' => $proprietaireId];

            if ($bienIdFilter > 0) {
                $sql .= " AND r.id_biens = :bienId";
                $params[':bienId'] = $bienIdFilter;
            }

            $stmt = $this->reservationModel->getDb()->prepare($sql);
            $stmt->execute($params);
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 3. Mapper vers événements FullCalendar
            $events = [];
            foreach ($reservations as $reservation) {
                $title = $biensMap[$reservation['id_biens']] ?? 'Bien inconnu';
                $locataire = '';
                if (!empty($reservation['prenom_locataire']) && !empty($reservation['nom_locataire'])) {
                    $locataire = trim($reservation['prenom_locataire'] . ' ' . $reservation['nom_locataire']);
                } elseif (!empty($reservation['RaisonSociale'])) {
                    $locataire = $reservation['RaisonSociale'];
                } else {
                    $locataire = 'Locataire anonyme';
                }
                $title .= ' - ' . $locataire;
                
                $events[] = [
                    'id' => 'res-' . $reservation['id_reservation'],  // Préfixe pour éviter conflits
                    'title' => $title,
                    'start' => $reservation['date_debut'],
                    'end' => date('Y-m-d', strtotime($reservation['date_fin'] . ' +1 day')),  // FullCalendar inclusif
                    'backgroundColor' => '#28a745',  // Vert pour réservations confirmées
                    'borderColor' => '#1e7e34',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'bienId' => $reservation['id_biens'],
                        'reservationId' => $reservation['id_reservation']
                    ]
                ];
            }

            echo json_encode([
                'success' => true,
                'events' => $events,
                'count' => count($events)
            ]);

        } catch (Exception $e) {
            error_log('Erreur CalendarController: ' . $e->getMessage());  // Log pour debug
            echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
        }

        exit;
    }
}
?>