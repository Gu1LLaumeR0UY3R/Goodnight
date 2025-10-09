<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/ReservationModel.php";

class LocataireController extends BaseController {
    private $reservationModel;

    public function __construct() {
        AuthMiddleware::requireRole("Locataire");

        $this->reservationModel = new ReservationModel();
    }

    public function index() {
        $this->render("locataire/index");
    }

    public function myReservations() {
        $reservations = $this->reservationModel->getReservationsByUser($_SESSION["user_id"]);
        $this->render("locataire/my_reservations", ["reservations" => $reservations]);
    }
}

?>
