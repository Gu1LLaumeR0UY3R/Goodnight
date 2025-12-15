<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/PhotoModel.php";
require_once __DIR__ . "/../Models/PrestationModel.php";
// require_once __DIR__ . "/../Models/ReservationModel.php"; // Commenté pour l'instant

class HomeController extends BaseController {
    private $bienModel;
    private $typeBienModel;
    private $communeModel;
    private $photoModel;
    private $reservationModel;
    private $prestationModel;

    public function __construct() {
        $this->bienModel = new BienModel();
        $this->typeBienModel = new TypeBienModel();
        $this->communeModel = new CommuneModel();
        $this->photoModel = new PhotoModel();
        $this->prestationModel = new PrestationModel();
        // $this->reservationModel = new ReservationModel(); // Commenté pour l'instant
    }

    public function index() {
        $typesBiens = $this->typeBienModel->getAll();
        $prestations = $this->prestationModel->getAll();
        
        // Récupérer les filtres s'ils existent
        $filters = [
            'commune' => $_GET['q'] ?? '',
            'types_bien' => isset($_GET['types_bien']) ? (is_array($_GET['types_bien']) ? $_GET['types_bien'] : array_filter([$_GET['types_bien']])) : [],
            'superficie_min' => $_GET['superficie_min'] ?? '',
            'superficie_max' => $_GET['superficie_max'] ?? '',
            'prix_min' => $_GET['prix_min'] ?? '',
            'prix_max' => $_GET['prix_max'] ?? '',
            'prestations' => isset($_GET['prestations']) ? (is_array($_GET['prestations']) ? $_GET['prestations'] : array_filter([$_GET['prestations']])) : [],
            'couchage_min' => $_GET['couchage_min'] ?? '',
            'couchage_max' => $_GET['couchage_max'] ?? '',
            'animaux' => isset($_GET['animaux']) ? $_GET['animaux'] : '',
            'tri' => $_GET['tri'] ?? ''
        ];

        // Appliquer les filtres s'il y en a
        $hasFilters = !empty(array_filter($filters));
        if ($hasFilters) {
            $biens = $this->bienModel->searchBiensWithFilters($filters);
        } else {
            $biens = $this->bienModel->getBiensWithDetails();
        }

        $this->render("home/index", [
            "typesBiens" => $typesBiens,
            "prestations" => $prestations,
            "biens" => $biens,
            "filters" => $filters
        ]);
    }

    public function search() {
        $filters = [
            'commune' => $_GET['q'] ?? '',
            'types_bien' => isset($_GET['types_bien']) ? (is_array($_GET['types_bien']) ? $_GET['types_bien'] : array_filter([$_GET['types_bien']])) : [],
            'superficie_min' => $_GET['superficie_min'] ?? '',
            'superficie_max' => $_GET['superficie_max'] ?? '',
            'prix_min' => $_GET['prix_min'] ?? '',
            'prix_max' => $_GET['prix_max'] ?? '',
            'prestations' => isset($_GET['prestations']) ? (is_array($_GET['prestations']) ? $_GET['prestations'] : array_filter([$_GET['prestations']])) : [],
            'couchage_min' => $_GET['couchage_min'] ?? '',
            'couchage_max' => $_GET['couchage_max'] ?? '',
            'animaux' => isset($_GET['animaux']) ? $_GET['animaux'] : '',
            'tri' => $_GET['tri'] ?? ''
        ];

        $hasFilters = !empty(array_filter($filters));
        if ($hasFilters) {
            $biens = $this->bienModel->searchBiensWithFilters($filters);
        } else {
            $biens = $this->bienModel->getBiensWithDetails();
        }
        
        $typesBiens = $this->typeBienModel->getAll();
        $prestations = $this->prestationModel->getAll();

        $this->render("home/index", [
            "typesBiens" => $typesBiens,
            "prestations" => $prestations,
            "biens" => $biens,
            "filters" => $filters
        ]);
    }

    public function map() {
        $biens = $this->bienModel->getBiensWithDetails();
        $this->render("home/map", [
            "biens" => $biens
        ]);
    }

    public function details($id) {
        // Exiger la connexion avant d'afficher la page de description du bien
        require_once __DIR__ . '/AuthMiddleware.php';
        AuthMiddleware::requireLogin();
        $bien = $this->bienModel->getBienWithDetailsById($id);
        
        if (!$bien) {
            // Gérer le cas où le bien n'est pas trouvé
            $this->redirect("/");
            return;
        }

        // Récupérer les photos
        $photos = $this->photoModel->getPhotosByBien($id);

        $this->render("bien/details", [
            "bien" => $bien,
            "photos" => $photos
        ]);
    }

    public function autocompleteCommunes() {
        $term = $_GET["term"] ?? "";
        $communes = $this->communeModel->search($term);
        $results = [];
        foreach ($communes as $commune) {
            $results[] = [
                'label' => $commune["ville_nom"] . ' (' . $commune["ville_code_postal"] . ')',
                'value' => $commune["id_commune"],
                'codePostal' => $commune["ville_code_postal"]
            ];
        }
        header("Content-Type: application/json");
        echo json_encode($results);
    }

    public function signaler($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bien/' . $id);
            return;
        }

        require_once __DIR__ . '/../Models/SignalementModel.php';
        $signalementModel = new SignalementModel();

        $data = [
            'id_biens' => $id,
            'id_locataire' => $_SESSION['user_id'] ?? null,
            'email_signaleur' => $_POST['email_signaleur'] ?? null,
            'motif' => $_POST['motif'],
            'description' => $_POST['description'] ?? null
        ];

        // Vérifier si l'utilisateur a déjà signalé ce bien
        if (isset($_SESSION['user_id'])) {
            if ($signalementModel->hasUserReported($id, $_SESSION['user_id'])) {
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'message' => 'Vous avez déjà signalé ce bien.'
                ];
                $this->redirect('/bien/' . $id);
                return;
            }
        }

        $signalementModel->create($data);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Votre signalement a été envoyé avec succès. Merci de votre contribution.'
        ];

        $this->redirect('/bien/' . $id);
    }
}

?>
