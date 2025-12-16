<?php
// Controllers/ReservationController.php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/AuthMiddleware.php";
require_once __DIR__ . "/../Models/ReservationModel.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TarifModel.php"; // Ajout pour id_tarif
require_once __DIR__ . "/../Models/NotificationModel.php";

class ReservationController extends BaseController
{
    private $model;
    private $bienModel;
    private $notificationModel;

    public function __construct()
    {
        $this->model = new ReservationModel();
        $this->bienModel = new BienModel();
        $this->notificationModel = new NotificationModel();
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/home");
        }

        $id_biens = $_POST['id_biens'] ?? null;
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $id_locataire = $_SESSION['user_id'] ?? null;

        if (!$id_locataire) {
            $_SESSION['errors'] = ["Vous devez être connecté pour effectuer une réservation."];
            $this->redirect("/login");
        }

        $errors = [];

        if (empty($id_biens) || !is_numeric($id_biens)) {
            $errors[] = "Identifiant du bien manquant ou invalide.";
        }
        if (empty($date_debut) || empty($date_fin)) {
            $errors[] = "Les dates de début et de fin sont obligatoires.";
        }
        if (strtotime($date_debut) >= strtotime($date_fin)) {
            $errors[] = "La date de début doit être antérieure à la date de fin.";
        }
        if (strtotime($date_debut) < strtotime(date('Y-m-d'))) {
            $errors[] = "La date de début ne peut pas être dans le passé.";
        }

        $bien = $this->bienModel->getById($id_biens);
        if (!$bien) {
            $errors[] = "Le bien spécifié n'existe pas.";
        }

        if (empty($errors)) {
            if ($this->model->hasOverlap($id_biens, $date_debut, $date_fin)) {
                $errors[] = "Les dates sélectionnées chevauchent une réservation existante pour ce bien.";
            }
        }

        if (empty($errors)) {
            $tarifModel = new TarifModel();
            $id_tarif = $tarifModel->getTarifIdByDates($id_biens, $date_debut);
            if (!$id_tarif) {
                $errors[] = "Aucun tarif trouvé pour ce bien à la date de début sélectionnée.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect("/bien/" . $id_biens);
        } else {
            try {
                $data = [
                    'id_biens' => $id_biens,
                    'id_locataire' => $id_locataire,
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                    'id_tarif' => $id_tarif
                ];
                $reservationId = $this->model->createReservation($data);
                // Notify the proprietaire (owner) of this bien about a new reservation
                $bienDetails = $this->bienModel->getById($id_biens);
                if ($bienDetails && !empty($bienDetails['id_locataire'])) {
                    $locaName = trim(($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? ''));
                    $title = "Nouvelle réservation";
                    $dd = date('d/m/Y', strtotime($date_debut));
                    $df = date('d/m/Y', strtotime($date_fin));
                    $bienName = $bienDetails['designation_bien'] ?? ("Bien #" . $id_biens);
                    $message = ($locaName ? $locaName : 'Un locataire') . " a réservé votre bien \"" . $bienName . "\" du " . $dd . " au " . $df . ".";
                    $link = "/proprietaire/myReservations";
                    try { $this->notificationModel->create([
                        'user_id' => (int)$bienDetails['id_locataire'],
                        'type' => 'reservation_created',
                        'title' => $title,
                        'message' => $message,
                        'link' => $link,
                    ]); } catch (\Throwable $e) { /* ignore notif errors */ }
                }
                $_SESSION['success_message'] = "Votre demande de réservation a été envoyée avec succès.";
                $this->redirect("/home");
            } catch (\Exception $e) {
                $_SESSION['errors'] = ["Une erreur est survenue : " . $e->getMessage()];
                $_SESSION['old_input'] = $_POST;
                $this->redirect("/bien/" . $id_biens);
            }
        }
    }

    public function myReservations() {
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]);
        $id_locataire = $_SESSION['user_id'] ?? null;
        if ($id_locataire) {
            $reservations = $this->model->getReservationsByLocataire($id_locataire);
            $this->render("locataire/my_reservations", [
                "reservations" => $reservations,
                "success_message" => $_SESSION['success_message'] ?? null,
                "error_message" => $_SESSION['error_message'] ?? null
            ]);
            unset($_SESSION['success_message'], $_SESSION['error_message']);
        } else {
            $this->redirect("/login");
        }
    }

    public function cancel($id_reservation) {
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]);
        $id_locataire = $_SESSION['user_id'] ?? null;       
        if (!$id_locataire || !is_numeric($id_reservation)) {
            $this->redirect("/login");
        }

            try {
            $reservation = $this->model->getById($id_reservation);
            if (!$reservation || $reservation['id_locataire'] != $id_locataire) {
                $_SESSION['error_message'] = "Réservation introuvable ou vous n'avez pas la permission de l'annuler.";
            } else {
                $affectedRows = $this->model->cancelReservation($id_reservation, $id_locataire);
                if ($affectedRows > 0) {
                    $_SESSION['success_message'] = "La réservation a été annulée avec succès.";
                    // Notify proprietaire that a reservation was cancelled by the locataire
                    $bien = $this->bienModel->getById($reservation['id_biens']);
                    if ($bien && !empty($bien['id_locataire'])) {
                        $title = "Réservation annulée";
                        $bienName = $bien['designation_bien'] ?? ("Bien #" . $reservation['id_biens']);
                        $message = ((trim(($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? ''))) ?: 'Un locataire') . " a annulé sa réservation (#" . $id_reservation . ") sur votre bien \"" . $bienName . "\".";
                        $link = "/proprietaire/myReservations";
                        try { $this->notificationModel->create([
                            'user_id' => (int)$bien['id_locataire'],
                            'type' => 'reservation_cancelled',
                            'title' => $title,
                            'message' => $message,
                            'link' => $link,
                        ]); } catch (\Throwable $e) { /* ignore notif errors */ }
                    }
                } else {
                    $_SESSION['error_message'] = "Impossible d'annuler la réservation.";
                }
            }
        } catch (\Exception $e) {
            $_SESSION['error_message'] = "Une erreur est survenue lors de l'annulation: " . $e->getMessage();
        }

        $this->redirect("/locataire/myReservations");
    }
}
?>