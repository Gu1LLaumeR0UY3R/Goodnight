<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/AuthMiddleware.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/ReservationModel.php";
require_once __DIR__ . "/../Models/PhotoModel.php";
require_once __DIR__ . "/../Models/SaisonModel.php";
require_once __DIR__ . "/../Models/TarifModel.php";
require_once __DIR__ . "/../Models/BlocageModel.php";

class ProprietaireController extends BaseController {
    private $bienModel;
    private $typeBienModel;
    private $communeModel;
    private $reservationModel;
    private $photoModel;
    private $saisonModel;
    private $tarifModel;

    public function __construct() {
        AuthMiddleware::requireRole("Propriétaire");

        $this->bienModel = new BienModel();
        $this->typeBienModel = new TypeBienModel();
        $this->communeModel = new CommuneModel();
        $this->reservationModel = new ReservationModel();
        $this->photoModel = new PhotoModel();
        $this->saisonModel = new SaisonModel();
        $this->tarifModel = new TarifModel();
    }

    public function index() {
        $biens = $this->bienModel->getBiensByProprietaire($_SESSION['user_id']);
        $typesBiens = $this->typeBienModel->getAllTypesBiens();
        $this->render("proprietaire/index", [
            "biens" => $biens,
            "typesBiens" => $typesBiens
        ]);
    }

    public function myBiens() {
        $biens = $this->bienModel->getBiensByProprietaire($_SESSION["user_id"]);
        $this->render("proprietaire/my_biens", ["biens" => $biens]);
    }

    public function addBien() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "designation_bien" => $_POST["designation_bien"],
                "rue_biens" => $_POST["rue_biens"],
                "complement_biens" => $_POST["complement_biens"] ?? null,
                "superficie_biens" => $_POST["superficie_biens"],
                "description_biens" => $_POST["description_biens"] ?? null,
                "animaux_biens" => isset($_POST["animaux_biens"]) ? 1 : 0,
                "nb_couchage" => $_POST["nb_couchage"],
                "id_TypeBien" => $_POST["id_TypeBien"],
                "id_commune" => $_POST["id_commune"],
                "id_locataire" => $_SESSION["user_id"] // Le propriétaire connecté
            ];
            
            $bienId = $this->bienModel->create($data);
            
            // Gérer l'upload de photos si nécessaire
            if ($bienId) {
                // Gérer l'upload de photos
                if (isset($_FILES["photos"]) && !empty($_FILES["photos"]["name"][0])) {
                    $this->handlePhotoUpload($bienId, $_FILES["photos"]);
                }

                // Gérer l'ajout des tarifs
                if (isset($_POST["tarifs"]) && is_array($_POST["tarifs"])) {
                    foreach ($_POST["tarifs"] as $tarif) {
                        if (!empty($tarif["prix_semaine"]) && !empty($tarif["annee"]) && !empty($tarif["id_saison"])) {
                            $this->tarifModel->create([
                                "prix_semaine" => $tarif["prix_semaine"],
                                "annee" => $tarif["annee"],
                                "id_biens" => $bienId,
                                "id_saison" => $tarif["id_saison"]
                            ]);
                        }
                    }
                }
            }
            
            $this->redirect("/proprietaire/myBiens");
        }
        
        $typesBiens = $this->typeBienModel->getAll();
        $communes = $this->communeModel->getAll();
        $saisons = $this->saisonModel->getAll();
        $this->render("proprietaire/add_bien", ["typesBiens" => $typesBiens, "communes" => $communes, "saisons" => $saisons]);
    }

    public function editBien($id) {
        $bien = $this->bienModel->getById($id);
        if ($bien) {
            $commune = $this->communeModel->getById($bien["id_commune"]);
            if ($commune) {
                $bien["commune_nom"] = $commune["ville_nom"];
            }
        }
        
        // Vérifier que le bien appartient au propriétaire connecté
        if (!$bien || $bien["id_locataire"] != $_SESSION["user_id"]) {
            $this->redirect("/proprietaire/myBiens");
            return;
        }
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "designation_bien" => $_POST["designation_bien"],
                "rue_biens" => $_POST["rue_biens"],
                "complement_biens" => $_POST["complement_biens"] ?? null,
                "superficie_biens" => $_POST["superficie_biens"],
                "description_biens" => $_POST["description_biens"] ?? null,
                "animaux_biens" => isset($_POST["animaux_biens"]) ? 1 : 0,
                "nb_couchage" => $_POST["nb_couchage"],
                "id_TypeBien" => $_POST["id_TypeBien"],
                "id_commune" => $_POST["id_commune"],
                "id_locataire" => $_SESSION["user_id"] // S'assurer que l'ID du propriétaire est toujours inclus
            ];
            $this->bienModel->update($id, $data);

            // Gérer la mise à jour des tarifs
            if (isset($_POST["tarifs"]) && is_array($_POST["tarifs"])) {
                foreach ($_POST["tarifs"] as $tarif) {
                    if (!empty($tarif["prix_semaine"]) && !empty($tarif["annee"]) && !empty($tarif["id_saison"])) {
                        // Vérifier si un tarif existe déjà pour ce bien, cette saison et cette année
                        $existingTarif = $this->tarifModel->getTarifByBienSaisonAnnee($id, $tarif["id_saison"], $tarif["annee"]);
                        if ($existingTarif) {
                            // Mettre à jour le tarif existant
                            $this->tarifModel->update($existingTarif["id_tarif"], [
                                "prix_semaine" => $tarif["prix_semaine"],
                                "annee" => $tarif["annee"],
                                "id_saison" => $tarif["id_saison"]
                            ]);
                        } else {
                            // Créer un nouveau tarif
                            $this->tarifModel->create([
                                "prix_semaine" => $tarif["prix_semaine"],
                                "annee" => $tarif["annee"],
                                "id_biens" => $id,
                                "id_saison" => $tarif["id_saison"]
                            ]);
                        }
                    }
                }
            }
            
            // Gérer l'upload de photos si nécessaire
            if (isset($_FILES["photos"]) && !empty($_FILES["photos"]["name"][0])) {
                $this->handlePhotoUpload($id, $_FILES["photos"]);
            }
            
            $this->redirect("/proprietaire/myBiens");
        }
        
        $typesBiens = $this->typeBienModel->getAll();
        $communes = $this->communeModel->getAll();
        $photos = $this->photoModel->getPhotosByBien($id);
        $saisons = $this->saisonModel->getAll();
        $tarifs = $this->tarifModel->getTarifsByBien($id);
        
        // Mapper les tarifs existants pour un accès facile par id_saison et annee
        $tarifsMapped = [];
        foreach ($tarifs as $tarif) {
            $tarifsMapped[$tarif['id_saison'] . '_' . $tarif['annee']] = $tarif['prix_semaine'];
        }

        $this->render("proprietaire/edit_bien", [
            "bien" => $bien,
            "typesBiens" => $typesBiens,
            "communes" => $communes,
            "photos" => $photos,
            "saisons" => $saisons,
            "tarifsMapped" => $tarifsMapped
        ]);
    }

    public function deleteBien($id) {
        $bien = $this->bienModel->getById($id);
        
        // Vérifier que le bien appartient au propriétaire connecté
        if ($bien && $bien["id_locataire"] == $_SESSION["user_id"]) {
            $this->bienModel->delete($id);
        }
        
        $this->redirect("/proprietaire/myBiens");
    }

    public function myReservations() {
        // Affiche les réservations faites par l'utilisateur connecté (le propriétaire)
        $reservations = $this->reservationModel->getReservationsByLocataire($_SESSION["user_id"]);
        $this->render("proprietaire/my_reservations", ["reservations" => $reservations]);
    }

    // Retourne les événements (réservations + blocages) au format FullCalendar
    public function calendarEvents() {
        // Le constructeur a déjà vérifié le rôle du propriétaire via AuthMiddleware
        header('Content-Type: application/json; charset=utf-8');

        // Support single 'bien' or multiple 'biens' (comma-separated or array)
        $bien = isset($_GET['bien']) ? intval($_GET['bien']) : null;
        $biensFilter = null;
        if (isset($_GET['biens'])) {
            // can be '1,2,3' or biens[]=1&biens[]=2
            if (is_array($_GET['biens'])) {
                $biensFilter = array_map('intval', $_GET['biens']);
            } else {
                $biensFilter = array_filter(array_map('intval', explode(',', $_GET['biens'])));
            }
        } elseif ($bien !== null) {
            $biensFilter = [$bien];
        }

        // Récupérer les réservations du propriétaire
        $allReservations = $this->reservationModel->getReservationsByProprietaire($_SESSION['user_id']);
        $events = [];

        foreach ($allReservations as $r) {
            if ($biensFilter !== null && !in_array(intval($r['id_biens']), $biensFilter)) continue;

            // FullCalendar attend end en tant qu'exclusive pour allDay events -> ajouter 1 jour
            $end = date('Y-m-d', strtotime($r['date_fin'] . ' +1 day'));

            // Construire le nom du locataire (personne physique ou raison sociale)
            $locataireName = '';
            if (!empty($r['RaisonSociale'])) {
                $locataireName = $r['RaisonSociale'];
            } else {
                $locataireName = trim(($r['nom_locataire'] ?? '') . ' ' . ($r['prenom_locataire'] ?? ''));
                if (empty($locataireName)) {
                    $locataireName = 'Locataire';
                }
            }

            $events[] = [
                'id' => 'res-' . $r['id_reservation'],
                'title' => 'Réservation: ' . $locataireName,
                'start' => $r['date_debut'],
                'end' => $end,
                'color' => '#3788d8',
                'extendedProps' => [
                    'type' => 'reservation',
                    'bien_id' => $r['id_biens'],
                    'bien_name' => $r['designation_bien'],
                    'reservation_id' => $r['id_reservation'],
                    'locataire_nom' => $r['nom_locataire'] ?? '',
                    'locataire_prenom' => $r['prenom_locataire'] ?? '',
                    'locataire_raison_sociale' => $r['RaisonSociale'] ?? '',
                    'locataire_email' => $r['email_locataire'] ?? '',
                    'locataire_tel' => $r['tel_locataire'] ?? '',
                    'date_debut' => $r['date_debut'],
                    'date_fin' => $r['date_fin'],
                    'commune' => $r['commune_nom'] ?? ''
                ]
            ];
        }

        // Récupérer les blocages
        $blocageModel = new BlocageModel();
        // If filtering by a single bien, fetch blocages for that bien directly for efficiency.
        if (is_array($biensFilter) && count($biensFilter) === 1) {
            $blocages = $blocageModel->getBlocagesByBien($biensFilter[0]);
        } else {
            // get all blocages for proprietor then filter in PHP if needed
            $blocages = $blocageModel->getBlocagesByProprietaire($_SESSION['user_id']);
            if (is_array($biensFilter)) {
                $blocages = array_filter($blocages, function($b) use ($biensFilter) {
                    return in_array(intval($b['id_biens']), $biensFilter);
                });
            }
        }

        foreach ($blocages as $b) {
            // envoyer end comme exclusive
            $end = date('Y-m-d', strtotime($b['date_fin'] . ' +1 day'));
            $events[] = [
                'id' => 'block-' . $b['id_blocage'],
                'title' => 'Blocage',
                'start' => $b['date_debut'],
                'end' => $end,
                'color' => '#ff7f50',
                'extendedProps' => [
                    'type' => 'blocage',
                    'motif' => $b['motif'],
                    'commentaire' => $b['commentaire'] ?? null,
                    'bien_id' => $b['id_biens']
                ]
            ];
        }

        echo json_encode($events);
        exit;
    }

    // Créer un blocage (POST JSON)
    public function calendarBlock() {
        header('Content-Type: application/json; charset=utf-8');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Données invalides']);
            exit;
        }

        $bienId = isset($input['bien_id']) ? intval($input['bien_id']) : null;
        $start = $input['start'] ?? null;
        $end = $input['end'] ?? null;
        $motif = $input['motif'] ?? 'personnel';
        $commentaire = $input['commentaire'] ?? null;

        if (!$bienId || !$start || !$end) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            exit;
        }

        // Vérifier que le bien appartient bien au propriétaire connecté
        $bien = $this->bienModel->getById($bienId);
        if (!$bien || $bien['id_locataire'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Vous n\'avez pas la permission']);
            exit;
        }

        // Vérifier qu'il n'y a pas de chevauchement avec des réservations existantes
        $allReservations = $this->reservationModel->getReservationsByProprietaire($_SESSION['user_id']);
        $existingReservations = array_filter($allReservations, function($r) use ($bienId) {
            return intval($r['id_biens']) === $bienId;
        });
        
        $blocageStartDate = new DateTime($start);
        $blocageEndDate = new DateTime($end);

        foreach ($existingReservations as $res) {
            $resStartDate = new DateTime($res['date_debut']);
            $resEndDate = new DateTime($res['date_fin']);
            
            // Vérifier le chevauchement : blocage [start, end] vs réservation [res_start, res_end]
            // Chevauchement si : start < res_end ET end > res_start
            // Exception : un chevauchement d'un jour seulement est autorisé
            if ($blocageStartDate < $resEndDate && $blocageEndDate > $resStartDate) {
                // Calculer le nombre de jours de chevauchement
                $overlapStart = max($blocageStartDate, $resStartDate);
                $overlapEnd = min($blocageEndDate, $resEndDate);
                $interval = $overlapStart->diff($overlapEnd);
                $overlapDays = $interval->days;

                // Si chevauchement > 1 jour, refuser
                if ($overlapDays > 1) {
                    http_response_code(409);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Ce blocage chevauche une réservation existante (chevauchement de ' . $overlapDays . ' jours). Un chevauchement d\'un jour seulement est autorisé.'
                    ]);
                    exit;
                }
            }
        }

        $blocageModel = new BlocageModel();
        try {
            $id = $blocageModel->createBlocage([
                'id_biens' => $bienId,
                'date_debut' => $start,
                'date_fin' => $end,
                'motif' => $motif,
                'commentaire' => $commentaire
            ]);

            echo json_encode(['success' => true, 'id' => $id, 'message' => 'Blocage créé']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // Supprimer un blocage (POST JSON) : { eventId: 'block-123' }
    public function calendarUnblock() {
        header('Content-Type: application/json; charset=utf-8');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['eventId'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            exit;
        }

        $eventId = $input['eventId'];
        if (strpos($eventId, 'block-') !== 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID invalide']);
            exit;
        }

        $id = intval(substr($eventId, strlen('block-')));
        $blocageModel = new BlocageModel();

        // Vérifier que le blocage appartient au propriétaire via le bien
        $blocage = $blocageModel->getById($id);
        if (!$blocage) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Blocage introuvable']);
            exit;
        }

        $bien = $this->bienModel->getById($blocage['id_biens']);
        if (!$bien || $bien['id_locataire'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Permission refusée']);
            exit;
        }

        $deleted = $blocageModel->deleteBlocage($id);
        if ($deleted) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Impossible de supprimer']);
        }
        exit;
    }

    private function handlePhotoUpload($bienId, $files) {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0777, true);
        }

        foreach ($files["name"] as $key => $name) {
            if ($files["error"][$key] == UPLOAD_ERR_OK) {
                $tmp_name = $files["tmp_name"][$key];
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $newFileName = uniqid("photo_") . "." . $extension;
                $targetFile = UPLOAD_DIR . $newFileName;

                if (move_uploaded_file($tmp_name, $targetFile)) {
                    $this->photoModel->create([
                        "nom_photo" => $name,
                        "lien_photo" => UPLOAD_URL . $newFileName,
                        "id_biens" => $bienId
                    ]);
                }
            }
        }
    }

    public function deletePhoto($photoId) {
        $photo = $this->photoModel->getById($photoId);
        if ($photo) {
            $bien = $this->bienModel->getById($photo["id_biens"]);
            
            // Vérifier que le bien associé à la photo appartient bien au propriétaire connecté
            if ($bien && $bien["id_locataire"] == $_SESSION["user_id"]) {
                // Supprimer le fichier physique
                $filePath = str_replace(UPLOAD_URL, UPLOAD_DIR, $photo["lien_photo"]);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $this->photoModel->delete($photoId);
                $this->redirect("/proprietaire/editBien/" . $photo["id_biens"]);
                return;
            }
        }
        $this->redirect("/proprietaire/myBiens");
    }
}

?>
