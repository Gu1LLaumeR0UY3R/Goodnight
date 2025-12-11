<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/CadreModel.php";

class CadreController extends BaseController {
    private $cadreModel;

    public function __construct() {
        $this->cadreModel = new CadreModel();
    }

    /**
     * Afficher la liste des cadres (admin)
     */
    public function index() {
        // Vérifier l'authentification admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            $this->redirect('/');
            return;
        }

        $cadres = $this->cadreModel->getAll();
        $this->render('admin/cadres/index', ['cadres' => $cadres]);
    }

    /**
     * Afficher le formulaire de création de cadre
     */
    public function create() {
        // Vérifier l'authentification admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            $this->redirect('/');
            return;
        }

        $this->render('admin/cadres/create');
    }

    /**
     * Stocker un nouveau cadre
     */
    public function store() {
        // Vérifier l'authentification admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Non authentifié']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];

        // Validation basique
        if (empty($data['nom'])) {
            $_SESSION['error'] = 'Le nom du cadre est requis.';
            $this->redirect('/admin/cadres/create');
            return;
        }

        if (empty($data['description'])) {
            $_SESSION['error'] = 'La description du cadre est requise.';
            $this->redirect('/admin/cadres/create');
            return;
        }

        // Traiter l'upload du fichier
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Erreur lors de l\'upload de l\'image.';
            $this->redirect('/admin/cadres/create');
            return;
        }

        // Vérifier le type de fichier (PNG seulement)
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        if ($fileType !== 'image/png') {
            $_SESSION['error'] = 'Seules les images PNG sont acceptées.';
            $this->redirect('/admin/cadres/create');
            return;
        }

        // Vérifier la taille (max 200KB)
        $maxSize = 200 * 1024; // 200KB
        if ($_FILES['image']['size'] > $maxSize) {
            $_SESSION['error'] = 'L\'image ne doit pas dépasser 200KB.';
            $this->redirect('/admin/cadres/create');
            return;
        }

        // Créer le nom de fichier sécurisé
        $filename = 'cadre_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $data['nom']) . '.png';
        $uploadDir = __DIR__ . '/../../public/cadre/images/';
        $filePath = $uploadDir . $filename;
        $webPath = '/cadre/images/' . $filename;

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Déplacer le fichier
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $_SESSION['error'] = 'Erreur lors du déplacement du fichier.';
            $this->redirect('/admin/cadres/create');
            return;
        }

        // Insérer en base de données
        $data['chemin_fichier'] = $webPath;

        try {
            $result = $this->cadreModel->create($data);
            if ($result) {
                $_SESSION['success'] = 'Cadre créé avec succès.';
                $this->redirect('/admin/cadres');
            } else {
                $_SESSION['error'] = 'Erreur lors de la création du cadre.';
                unlink($filePath);
                $this->redirect('/admin/cadres/create');
            }
        } catch (Exception $e) {
            error_log("Erreur création cadre : " . $e->getMessage());
            $_SESSION['error'] = 'Erreur lors de la création du cadre.';
            unlink($filePath);
            $this->redirect('/admin/cadres/create');
        }
    }

    /**
     * Supprimer un cadre
     */
    public function delete() {
        // Vérifier l'authentification admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Non authentifié']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID du cadre requis.']);
            return;
        }

        try {
            $result = $this->cadreModel->delete((int)$data['id']);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cadre supprimé avec succès.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression.']);
            }
        } catch (Exception $e) {
            error_log("Erreur suppression cadre : " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression.']);
        }
    }
}
?>
