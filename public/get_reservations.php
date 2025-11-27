<?php
session_start();
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../app/Models/ReservationModel.php";
require_once __DIR__ . "/../lib/Database.php";

header('Content-Type: application/json');

$id_biens = isset($_GET['id_biens']) ? intval($_GET['id_biens']) : null;

if (!$id_biens) {
    echo json_encode([]);
    exit;
}

try {
    $reservationModel = new ReservationModel();
    
    // Récupérer toutes les réservations
    $allReservations = $reservationModel->getAllReservations();
    
    // Filtrer par bien
    $reservations = array_filter($allReservations, function($r) use ($id_biens) {
        return intval($r['id_biens']) === $id_biens;
    });
    
    $events = [];
    
    // Ajouter les réservations
    foreach ($reservations as $reservation) {
        // FullCalendar attend un format end exclusif (ajouter 1 jour à la date de fin)
        $endDate = new DateTime($reservation['date_fin']);
        $endDate->modify('+1 day');
        
        $events[] = [
            'id' => 'res-' . $reservation['id_reservation'],
            'title' => 'Réservé',
            'start' => $reservation['date_debut'],
            'end' => $endDate->format('Y-m-d'),
            'backgroundColor' => '#4caf50',
            'borderColor' => '#388e3c',
            'display' => 'block'
        ];
    }
    
    // Récupérer les blocages pour ce bien
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        SELECT id_blocage, date_debut, date_fin, motif, commentaire 
        FROM blocages 
        WHERE id_biens = :id_biens
        ORDER BY date_debut
    ");
    $stmt->execute(['id_biens' => $id_biens]);
    $blocages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ajouter les blocages
    foreach ($blocages as $blocage) {
        $endDate = new DateTime($blocage['date_fin']);
        $endDate->modify('+1 day');
        
        $title = 'Bloqué';
        if (!empty($blocage['motif'])) {
            $title .= ' - ' . $blocage['motif'];
        }
        
        $events[] = [
            'id' => 'bloc-' . $blocage['id_blocage'],
            'title' => $title,
            'start' => $blocage['date_debut'],
            'end' => $endDate->format('Y-m-d'),
            'backgroundColor' => '#ff7f50',
            'borderColor' => '#ff6347',
            'display' => 'block',
            'extendedProps' => [
                'type' => 'blocage',
                'motif' => $blocage['motif'],
                'commentaire' => $blocage['commentaire']
            ]
        ];
    }
    
    echo json_encode($events);
    
} catch (Exception $e) {
    error_log("Erreur get_reservations.php: " . $e->getMessage());
    echo json_encode([]);
}
?>
