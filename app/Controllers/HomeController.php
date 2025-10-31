<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/PhotoModel.php";
// require_once __DIR__ . "/../Models/ReservationModel.php"; // Commenté pour l'instant

class HomeController extends BaseController {
    private $bienModel;
    private $typeBienModel;
    private $communeModel;
    private $photoModel;
    private $reservationModel;

    public function __construct() {
        $this->bienModel = new BienModel();
        $this->typeBienModel = new TypeBienModel();
        $this->communeModel = new CommuneModel();
        $this->photoModel = new PhotoModel();
        // $this->reservationModel = new ReservationModel(); // Commenté pour l'instant
    }

    public function index() {
        $typesBiens = $this->typeBienModel->getAll();
        $biens = $this->bienModel->getBiensWithDetails();

        $this->render("home/index", [
            "typesBiens" => $typesBiens,
            "biens" => $biens
        ]);
    }

    public function search() {
        $searchTerm = $_GET["q"] ?? "";
        $biens = [];
        if (!empty($searchTerm)) {
            $biens = $this->bienModel->searchBiensByCommune($searchTerm);
        }
        $typesBiens = $this->typeBienModel->getAll();

        $this->render("home/index", [
            "typesBiens" => $typesBiens,
            "biens" => $biens,
            "searchTerm" => $searchTerm
        ]);
    }

    public function details($id) {
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
        $communes = $this->communeModel->findByNom($term);
        $results = [];
        foreach ($communes as $commune) {
            $results[] = [
                'label' => $commune["ville_nom"],
                'value' => $commune["id_commune"]
            ];
        }
        header("Content-Type: application/json");
        echo json_encode($results);
    }
}

?>
