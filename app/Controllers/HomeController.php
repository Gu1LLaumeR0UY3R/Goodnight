<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";

class HomeController extends BaseController {
    private $bienModel;
    private $typeBienModel;
    private $communeModel;

    public function __construct() {
        $this->bienModel = new BienModel();
        $this->typeBienModel = new TypeBienModel();
        $this->communeModel = new CommuneModel();
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
