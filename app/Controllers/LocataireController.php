<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/ReservationModel.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/FavoriModel.php";

class LocataireController extends BaseController {
    private $reservationModel;
    private $bienModel;
    private $favoriModel;

    public function __construct() {
        AuthMiddleware::requireRole("Locataire");

        $this->reservationModel = new ReservationModel();
        $this->bienModel = new BienModel();
        $this->favoriModel = new FavoriModel();
    }

    public function index() {
        // Récupérer les réservations de l'utilisateur
        $userId = $_SESSION['user_id'];
        $reservations = $this->reservationModel->getReservationsByUserId($userId);
        
        // Récupérer les IDs des favoris de l'utilisateur
        $favoriIds = $this->favoriModel->getFavoriIdsByUserId($userId);
        
        $this->render("locataire/index", [
            'reservations' => $reservations,
            'favoriIds' => $favoriIds
        ]);
    }

    /**
     * Afficher les biens favorisés
     */
    public function myFavorites() {
        $userId = $_SESSION['user_id'];
        
        // Récupérer tous les favoris de l'utilisateur depuis la base de données
        $favorites = $this->favoriModel->getFavorisByUserId($userId);

        $this->render("locataire/myFavorites", [
            'favorites' => $favorites
        ]);
    }

    /**
     * API pour gérer les favoris (ajouter/retirer)
     */
    public function manageFavorites() {
        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Récupérer les données JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? null;
        $bienId = $input['bien_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$action || !$bienId || !$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid parameters']);
            return;
        }

        try {
            if ($action === 'add') {
                // Ajouter le bien aux favoris
                $this->favoriModel->addFavori($userId, $bienId);
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Favori ajouté avec succès']);
            } elseif ($action === 'remove') {
                // Retirer le bien des favoris
                $this->favoriModel->removeFavori($userId, $bienId);
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Favori retiré avec succès']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid action']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * API pour récupérer les IDs des favoris de l'utilisateur
     */
    public function getUserFavorites() {
        // Vérifier que c'est une requête GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        try {
            // Récupérer les IDs des favoris
            $favoriteIds = $this->favoriModel->getFavoriIdsByUserId($userId);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'favoriteIds' => $favoriteIds
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

?>
