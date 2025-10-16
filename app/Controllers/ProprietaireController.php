<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/ReservationModel.php";
require_once __DIR__ . "/../Models/PhotoModel.php";
require_once __DIR__ . "/../Models/SaisonModel.php";
require_once __DIR__ . "/../Models/TarifModel.php";

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
        $this->render("proprietaire/index");
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
                // Gérer l'upload de photos si nécessaire
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
                "id_commune" => $_POST["id_commune"]
            ];
            $this->bienModel->update($id, $data);
            
            // Gérer l'upload de photos si nécessaire
            if (isset($_FILES["photos"]) && !empty($_FILES["photos"]["name"][0])) {
                $this->handlePhotoUpload($id, $_FILES["photos"]);
            }
            
            $this->redirect("/proprietaire/myBiens");
        }
        
        $typesBiens = $this->typeBienModel->getAll();
        $communes = $this->communeModel->getAll();
        $photos = $this->photoModel->getPhotosByBien($id);
        $this->render("proprietaire/edit_bien", ["bien" => $bien, "typesBiens" => $typesBiens, "communes" => $communes, "photos" => $photos]);
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
        $reservations = $this->reservationModel->getReservationsByProprietaire($_SESSION["user_id"]);
        $this->render("proprietaire/my_reservations", ["reservations" => $reservations]);
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
