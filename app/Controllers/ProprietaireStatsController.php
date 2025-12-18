<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/AuthMiddleware.php";
require_once __DIR__ . "/../Models/ReservationModel.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TarifModel.php";

class ProprietaireStatsController extends BaseController {
    private $reservationModel;
    private $bienModel;
    private $tarifModel;

    public function __construct() {
        AuthMiddleware::requireRole("Propriétaire");
        $this->reservationModel = new ReservationModel();
        $this->bienModel = new BienModel();
        $this->tarifModel = new TarifModel();
    }

    /**
     * API endpoint pour les statistiques
     * GET /proprietaire/stats?period=month&year=2025&month=12
     * period: hour (24h), day (7j), month, year
     */
    public function getStats() {
        header('Content-Type: application/json; charset=utf-8');
        
        $period = $_GET['period'] ?? 'month';
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        // Filtres optionnels
        $bienIds = [];
        if (!empty($_GET['bien_ids'])) {
            $bienIds = array_filter(array_map('intval', explode(',', $_GET['bien_ids'])));
        }
        $typeId = isset($_GET['type_id']) && $_GET['type_id'] !== 'all' ? intval($_GET['type_id']) : null;
        $dateDebut = isset($_GET['date_debut']) ? $_GET['date_debut'] : null;
        $dateFin = isset($_GET['date_fin']) ? $_GET['date_fin'] : null;
        
        // Récupérer toutes les réservations du propriétaire
        $allReservations = $this->reservationModel->getReservationsByProprietaire($_SESSION['user_id']);
        
        // Appliquer filtres côté serveur si fournis
        if (!empty($bienIds)) {
            $allReservations = array_filter($allReservations, function($res) use ($bienIds) {
                return in_array(intval($res['id_biens']), $bienIds);
            });
        }
        
        if ($typeId) {
            $allReservations = array_filter($allReservations, function($res) use ($typeId) {
                return intval($res['id_TypeBien'] ?? 0) === $typeId;
            });
        }
        
        if ($dateDebut || $dateFin) {
            $startFilter = $dateDebut ? new DateTime($dateDebut) : null;
            $endFilter = $dateFin ? new DateTime($dateFin) : null;
            $allReservations = array_filter($allReservations, function($res) use ($startFilter, $endFilter) {
                $startDate = new DateTime($res['date_debut']);
                $endDate = new DateTime($res['date_fin']);
                if ($startFilter && $endFilter) {
                    return $startDate <= $endFilter && $endDate >= $startFilter;
                } elseif ($startFilter) {
                    return $endDate >= $startFilter;
                } elseif ($endFilter) {
                    return $startDate <= $endFilter;
                }
                return true;
            });
        }
        
        $stats = [];
        
        switch ($period) {
            case 'hour':
                $stats = $this->getHourlyStats($allReservations);
                break;
            case 'day':
                $stats = $this->getLast7DaysStats($allReservations);
                break;
            case 'month':
                $stats = $this->getMonthlyStats($allReservations, $year);
                break;
            case 'year':
                $stats = $this->getYearlyStats($allReservations);
                break;
            default:
                $stats = $this->getMonthlyStats($allReservations, $year);
        }
        
        echo json_encode($stats);
        exit;
    }

    /**
     * Statistiques des dernières 24 heures
     */
    private function getHourlyStats($reservations) {
        $labels = [];
        $reservationsCount = [];
        $revenue = [];
        $now = new DateTime();
        
        // Créer les 24 dernières heures
        for ($i = 23; $i >= 0; $i--) {
            $hour = clone $now;
            $hour->modify("-{$i} hours");
            $labels[] = $hour->format('H') . 'h';
            $reservationsCount[] = 0;
            $revenue[] = 0;
        }
        
        // Compter les réservations créées dans les 24 dernières heures
        // Note: nécessite un champ created_at dans la table reservations
        foreach ($reservations as $res) {
            $startDate = new DateTime($res['date_debut']);
            $endDate = new DateTime($res['date_fin']);
            
            // Pour cette version, on compte les réservations qui se déroulent aujourd'hui
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            $tomorrow = clone $today;
            $tomorrow->modify('+1 day');
            
            if ($startDate <= $tomorrow && $endDate >= $today) {
                $hourIndex = intval($now->format('H'));
                $reservationsCount[$hourIndex]++;
                
                $duration = $startDate->diff($endDate)->days;
                $tarif = $this->getTarifForReservation($res);
                if ($tarif && $duration > 0) {
                    $revenuePerDay = $tarif / 7;
                    $revenue[$hourIndex] += $revenuePerDay * $duration;
                }
            }
        }
        
        // Comparaison avec les 24h précédentes
        $currentTotal = array_sum($reservationsCount);
        $currentRevenue = array_sum($revenue);
        
        $comparison = $this->getComparisonData($reservations, 'hour', $currentTotal, $currentRevenue);
        
        return [
            'labels' => $labels,
            'reservations' => $reservationsCount,
            'revenue' => array_map(function($v) { return round($v, 2); }, $revenue),
            'period' => 'hour',
            'comparison' => $comparison
        ];
    }

    /**
     * Statistiques des 7 derniers jours
     */
    private function getLast7DaysStats($reservations) {
        $labels = [];
        $reservationsCount = [];
        $revenue = [];
        $now = new DateTime();
        
        // Créer les 7 derniers jours
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = clone $now;
            $day->modify("-{$i} days");
            $day->setTime(0, 0, 0);
            $days[] = $day;
            $labels[] = $day->format('d/m');
            $reservationsCount[] = 0;
            $revenue[] = 0;
        }
        
        foreach ($reservations as $res) {
            $startDate = new DateTime($res['date_debut']);
            $endDate = new DateTime($res['date_fin']);
            
            // Pour chaque jour, vérifier si la réservation est active
            foreach ($days as $index => $day) {
                $nextDay = clone $day;
                $nextDay->modify('+1 day');
                
                if ($startDate <= $nextDay && $endDate >= $day) {
                    $reservationsCount[$index]++;
                    
                    $duration = $startDate->diff($endDate)->days;
                    $tarif = $this->getTarifForReservation($res);
                    if ($tarif && $duration > 0) {
                        $revenuePerDay = $tarif / 7;
                        // Ajouter seulement le revenu pour ce jour
                        $revenue[$index] += $revenuePerDay;
                    }
                }
            }
        }
        
        // Comparaison avec les 7 jours précédents
        $currentTotal = array_sum($reservationsCount);
        $currentRevenue = array_sum($revenue);
        
        $comparison = $this->getComparisonData($reservations, 'day', $currentTotal, $currentRevenue);
        
        return [
            'labels' => $labels,
            'reservations' => $reservationsCount,
            'revenue' => array_map(function($v) { return round($v, 2); }, $revenue),
            'period' => 'day',
            'comparison' => $comparison
        ];
    }

    /**
     * Statistiques par mois (pour une année donnée)
     */
    private function getMonthlyStats($reservations, $year) {
        $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $reservationsCount = array_fill(0, 12, 0);
        $revenue = array_fill(0, 12, 0);
        
        foreach ($reservations as $res) {
            $startDate = new DateTime($res['date_debut']);
            $endDate = new DateTime($res['date_fin']);
            
            if ($startDate->format('Y') == $year) {
                $month = intval($startDate->format('n')) - 1; // 0-indexed
                $reservationsCount[$month]++;
                
                $duration = $startDate->diff($endDate)->days;
                $tarif = $this->getTarifForReservation($res);
                if ($tarif && $duration > 0) {
                    $revenuePerDay = $tarif / 7;
                    $revenue[$month] += $revenuePerDay * $duration;
                }
            }
        }
        
        // Comparaison mois courant vs mois précédent
        $currentMonth = date('n');
        $currentTotal = $currentMonth > 0 ? $reservationsCount[$currentMonth - 1] : 0;
        $currentRevenue = $currentMonth > 0 ? $revenue[$currentMonth - 1] : 0;
        
        $comparison = $this->getComparisonData($reservations, 'month', $currentTotal, $currentRevenue);
        
        return [
            'labels' => $labels,
            'reservations' => $reservationsCount,
            'revenue' => array_map(function($v) { return round($v, 2); }, $revenue),
            'period' => 'month',
            'year' => $year,
            'comparison' => $comparison
        ];
    }

    /**
     * Statistiques par année (toutes les années disponibles)
     */
    private function getYearlyStats($reservations) {
        $yearlyData = [];
        
        foreach ($reservations as $res) {
            $startDate = new DateTime($res['date_debut']);
            $endDate = new DateTime($res['date_fin']);
            $year = $startDate->format('Y');
            
            if (!isset($yearlyData[$year])) {
                $yearlyData[$year] = [
                    'count' => 0,
                    'revenue' => 0
                ];
            }
            
            $yearlyData[$year]['count']++;
            
            $duration = $startDate->diff($endDate)->days;
            $tarif = $this->getTarifForReservation($res);
            if ($tarif && $duration > 0) {
                $revenuePerDay = $tarif / 7;
                $yearlyData[$year]['revenue'] += $revenuePerDay * $duration;
            }
        }
        
        ksort($yearlyData);
        
        $labels = array_keys($yearlyData);
        $reservationsCount = array_map(function($data) { return $data['count']; }, $yearlyData);
        $revenue = array_map(function($data) { return round($data['revenue'], 2); }, $yearlyData);
        
        // Comparaison année courante vs année précédente
        $currentYear = date('Y');
        $currentTotal = isset($yearlyData[$currentYear]) ? $yearlyData[$currentYear]['count'] : 0;
        $currentRevenue = isset($yearlyData[$currentYear]) ? $yearlyData[$currentYear]['revenue'] : 0;
        
        $comparison = $this->getComparisonData($reservations, 'year', $currentTotal, $currentRevenue);
        
        return [
            'labels' => $labels,
            'reservations' => $reservationsCount,
            'revenue' => $revenue,
            'period' => 'year',
            'comparison' => $comparison
        ];
    }

    /**
     * Récupérer le tarif pour une réservation
     */
    private function getTarifForReservation($reservation) {
        if (isset($reservation['id_tarif']) && $reservation['id_tarif']) {
            $tarif = $this->tarifModel->getById($reservation['id_tarif']);
            if ($tarif) {
                return floatval($tarif['prix_semaine']);
            }
        }
        return null;
    }

    /**
     * Calculer les données de comparaison avec la période précédente
     */
    private function getComparisonData($reservations, $period, $currentTotal, $currentRevenue) {
        $previousTotal = 0;
        $previousRevenue = 0;
        
        switch ($period) {
            case 'hour':
                // Comparer avec les 24h précédentes
                $start = new DateTime();
                $start->modify('-48 hours');
                $end = new DateTime();
                $end->modify('-24 hours');
                break;
            case 'day':
                // Comparer avec les 7 jours précédents
                $start = new DateTime();
                $start->modify('-14 days');
                $end = new DateTime();
                $end->modify('-7 days');
                break;
            case 'month':
                // Comparer avec le mois précédent
                $currentMonth = date('n');
                $currentYear = date('Y');
                $previousMonth = $currentMonth - 1;
                $previousYear = $currentYear;
                if ($previousMonth < 1) {
                    $previousMonth = 12;
                    $previousYear--;
                }
                break;
            case 'year':
                // Comparer avec l'année précédente
                $currentYear = date('Y');
                $previousYear = $currentYear - 1;
                break;
            default:
                return [
                    'reservationsDiff' => 0,
                    'revenueDiff' => 0,
                    'reservationsPercent' => 0,
                    'revenuePercent' => 0
                ];
        }
        
        // Calculer les totaux de la période précédente
        foreach ($reservations as $res) {
            $startDate = new DateTime($res['date_debut']);
            $endDate = new DateTime($res['date_fin']);
            
            $inPreviousPeriod = false;
            
            if ($period === 'hour' || $period === 'day') {
                $inPreviousPeriod = ($startDate <= $end && $endDate >= $start);
            } elseif ($period === 'month') {
                $inPreviousPeriod = ($startDate->format('Y') == $previousYear && $startDate->format('n') == $previousMonth);
            } elseif ($period === 'year') {
                $inPreviousPeriod = ($startDate->format('Y') == $previousYear);
            }
            
            if ($inPreviousPeriod) {
                $previousTotal++;
                $duration = $startDate->diff($endDate)->days;
                $tarif = $this->getTarifForReservation($res);
                if ($tarif && $duration > 0) {
                    $revenuePerDay = $tarif / 7;
                    $previousRevenue += $revenuePerDay * $duration;
                }
            }
        }
        
        // Calculer les différences et pourcentages
        $reservationsDiff = $currentTotal - $previousTotal;
        $revenueDiff = $currentRevenue - $previousRevenue;
        
        $reservationsPercent = $previousTotal > 0 ? round(($reservationsDiff / $previousTotal) * 100, 1) : 0;
        $revenuePercent = $previousRevenue > 0 ? round(($revenueDiff / $previousRevenue) * 100, 1) : 0;
        
        return [
            'reservationsDiff' => $reservationsDiff,
            'revenueDiff' => round($revenueDiff, 2),
            'reservationsPercent' => $reservationsPercent,
            'revenuePercent' => $revenuePercent
        ];
    }

    /**
     * Statistiques avancées (taux d'occupation, etc.)
     */
    public function getAdvancedStats() {
        header('Content-Type: application/json; charset=utf-8');
        
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        $bienIds = [];
        if (!empty($_GET['bien_ids'])) {
            $bienIds = array_filter(array_map('intval', explode(',', $_GET['bien_ids'])));
        }
        $typeId = isset($_GET['type_id']) && $_GET['type_id'] !== 'all' ? intval($_GET['type_id']) : null;
        
        // Récupérer les biens du propriétaire
        $biens = $this->bienModel->getBiensByProprietaire($_SESSION['user_id']);
        
        // Filtrer les biens si une liste est fournie
        if (!empty($bienIds)) {
            $biens = array_filter($biens, function($b) use ($bienIds) {
                return in_array(intval($b['id_biens']), $bienIds);
            });
        }
        
        if ($typeId) {
            $biens = array_filter($biens, function($b) use ($typeId) {
                return intval($b['id_TypeBien'] ?? 0) === $typeId;
            });
        }
        $allReservations = $this->reservationModel->getReservationsByProprietaire($_SESSION['user_id']);
        
        // Appliquer les mêmes filtres aux réservations
        if (!empty($bienIds)) {
            $allReservations = array_filter($allReservations, function($res) use ($bienIds) {
                return in_array(intval($res['id_biens']), $bienIds);
            });
        }
        
        if ($typeId) {
            $allReservations = array_filter($allReservations, function($res) use ($typeId) {
                return intval($res['id_TypeBien'] ?? 0) === $typeId;
            });
        }
        
        // Taux d'occupation par bien
        $occupancyByBien = [];
        foreach ($biens as $bien) {
            $bienReservations = array_filter($allReservations, function($res) use ($bien, $year) {
                $startDate = new DateTime($res['date_debut']);
                return $res['id_biens'] == $bien['id_biens'] && $startDate->format('Y') == $year;
            });
            
            $totalDays = 0;
            foreach ($bienReservations as $res) {
                $startDate = new DateTime($res['date_debut']);
                $endDate = new DateTime($res['date_fin']);
                $totalDays += $startDate->diff($endDate)->days;
            }
            
            $occupancyRate = ($totalDays / 365) * 100;
            
            $occupancyByBien[] = [
                'bien' => $bien['designation_bien'],
                'rate' => round($occupancyRate, 1),
                'days' => $totalDays
            ];
        }
        
        // Revenu par bien
        $revenueByBien = [];
        foreach ($biens as $bien) {
            $bienReservations = array_filter($allReservations, function($res) use ($bien, $year) {
                $startDate = new DateTime($res['date_debut']);
                return $res['id_biens'] == $bien['id_biens'] && $startDate->format('Y') == $year;
            });
            
            $totalRevenue = 0;
            foreach ($bienReservations as $res) {
                $startDate = new DateTime($res['date_debut']);
                $endDate = new DateTime($res['date_fin']);
                $duration = $startDate->diff($endDate)->days;
                $tarif = $this->getTarifForReservation($res);
                if ($tarif && $duration > 0) {
                    $revenuePerDay = $tarif / 7;
                    $totalRevenue += $revenuePerDay * $duration;
                }
            }
            
            $revenueByBien[] = [
                'bien' => $bien['designation_bien'],
                'revenue' => round($totalRevenue, 2)
            ];
        }
        
        echo json_encode([
            'occupancy' => $occupancyByBien,
            'revenue' => $revenueByBien,
            'year' => $year
        ]);
        exit;
    }
}

?>
