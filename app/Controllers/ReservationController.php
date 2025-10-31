<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/AuthMiddleware.php";
require_once __DIR__ . "/../Models/ReservationModel.php";
require_once __DIR__ . "/../Models/BienModel.php";

class ReservationController extends BaseController {
    private $reservationModel;
    private $bienModel;

    public function __construct() {
        $this->reservationModel = new ReservationModel();
        $this->bienModel = new BienModel();
        // Le middleware d'authentification sera géré par la méthode appelée si nécessaire
    }



    /**
     * Traite la soumission du formulaire de réservation.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/home");
        }

        $id_biens = $_POST['id_biens'] ?? null;
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $id_locataire = $_SESSION['user_id'] ?? null;
        $errors = [];

        // 1. Validation des données
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

        // 2. Vérification de chevauchement
        if (empty($errors)) {
            if ($this->reservationModel->hasOverlap($id_biens, $date_debut, $date_fin)) {
                $errors[] = "Les dates sélectionnées chevauchent une réservation existante pour ce bien.";
            }
        }

        // 3. Récupération de l'ID du tarif
        if (empty($errors)) {
            require_once __DIR__ . "/../Models/TarifModel.php";
            $tarifModel = new TarifModel();
            $id_tarif = $tarifModel->getTarifIdByDates($id_biens, $date_debut);

            if (!$id_tarif) {
                $errors[] = "Aucun tarif trouvé pour ce bien à la date de début sélectionnée.";
            }
        }

        // 4. Traitement
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
                    'id_tarif' => $id_tarif // Utilisation de l'ID du tarif récupéré
                ];
                $this->reservationModel->createReservation($data);
                $_SESSION['success_message'] = "Votre demande de réservation a été envoyée avec succès et est en attente de confirmation.";
                $this->redirect("/home");
            } catch (\Exception $e) {
                $_SESSION['errors'] = ["Une erreur est survenue lors de l'enregistrement de la réservation: " . $e->getMessage()];
                $_SESSION['old_input'] = $_POST;
                $this->redirect("/bien/" . $id_biens);
            }
        }
    }

    /**
     * Affiche les réservations de l'utilisateur.
     *    public function myReservations() {
        // Vérification de connexion et de rôle pour les non-admins
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]); 
        $id_locataire = $_SESSION['user_id'] ?? null;
        if ($id_locataire) {
            $reservations = $this->reservationModel->getReservationsByLocataire($id_locataire);
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

    /**
     * Annule une réservation.
     */
    public function cancel($id_reservation) {
        // Vérification de connexion et de rôle pour les non-admins
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]);
        $id_locataire = $_SESSION['user_id'] ?? null;       if (!$id_locataire || !is_numeric($id_reservation)) {
            $this->redirect("/login");
        }

        try {
            $reservation = $this->reservationModel->getById($id_reservation);
            if (!$reservation || $reservation['id_locataire'] != $id_locataire) {
                $_SESSION['error_message'] = "Réservation introuvable ou vous n'avez pas la permission de l'annuler.";
            } else {
                $affectedRows = $this->reservationModel->cancelReservation($id_reservation);
                if ($affectedRows > 0) {
                    $_SESSION['success_message'] = "La réservation a été annulée avec succès.";
                } else {
                    $_SESSION['error_message'] = "Impossible d'annuler la réservation. Elle est peut-être déjà confirmée ou annulée.";
                }
            }
        } catch (\Exception $e) {
            $_SESSION['error_message'] = "Une erreur est survenue lors de l'annulation: " . $e->getMessage();
        }

        $this->redirect("/locataire/my_reservations");
    }
}
?>
