<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/CadreModel.php";

class EasterEggController extends BaseController {
    private $cadreModel;

    public function __construct() {
        $this->cadreModel = new CadreModel();
    }

    /**
     * Affiche la page de gestion des easter eggs
     */
    public function index() {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            $this->redirect('/');
            return;
        }

        // Récupérer tous les cadres
        $cadres = $this->cadreModel->getAll();

        // Préparer les données pour la vue
        $data = [
            'cadres' => $cadres ?? []
        ];

        // Charger la vue
        $this->render('admin/easter_eggs/index', $data);
    }

    /**
     * Affiche le formulaire d'ajout d'un cadre
     */
    public function create() {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            $this->redirect('/');
            return;
        }

        // Charger la vue
        $this->render('admin/easter_eggs/create');
    }

    /**
     * Enregistre un nouveau cadre
     */
    public function store() {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            $this->redirect('/');
            return;
        }

        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $this->redirect('/admin/easter-eggs');
            return;
        }

        // Validation des champs
        $nom = trim($_POST['nom'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($nom)) {
            $data = ['error' => 'Le nom du cadre est obligatoire'];
            $this->render('admin/easter_eggs/create', $data);
            return;
        }

        // Gestion de l'upload du fichier
        if (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] === UPLOAD_ERR_NO_FILE) {
            $data = ['error' => 'Veuillez sélectionner un fichier PNG'];
            $this->render('admin/easter_eggs/create', $data);
            return;
        }

        $file = $_FILES['fichier'];

        // Vérifier les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $data = ['error' => 'Erreur lors de l\'upload du fichier'];
            $this->render('admin/easter_eggs/create', $data);
            return;
        }

        // Vérifier le type MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if ($mimeType !== 'image/png') {
            $data = ['error' => 'Le fichier doit être au format PNG'];
            $this->render('admin/easter_eggs/create', $data);
            return;
        }

        // Vérifier la taille (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $data = ['error' => 'Le fichier est trop volumineux (max 5MB)'];
            $this->render('admin/easter_eggs/create', $data);
            return;
        }

        // Créer le dossier si nécessaire
        $uploadDir = __DIR__ . '/../../public/cadre/frames/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer un nom unique pour le fichier
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('frame_', true) . '.' . $extension;
        $filePath = $uploadDir . $fileName;
        $relativePath = '/cadre/frames/' . $fileName;

        // Déplacer le fichier uploadé
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $data = ['error' => 'Erreur lors de l\'enregistrement du fichier'];
            $this->render('admin/easter_eggs/create', $data);
            return;
        }

        // Créer le cadre en base de données
        $cadreData = [
            'nom' => $nom,
            'description' => $description,
            'chemin_fichier' => $relativePath
        ];

        if ($this->cadreModel->create($cadreData)) {
            $_SESSION['success_message'] = 'Cadre créé avec succès !';
            $this->redirect('/admin/easter-eggs');
        } else {
            // Supprimer le fichier uploadé en cas d'erreur
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $data = ['error' => 'Erreur lors de la création du cadre'];
            $this->render('admin/easter_eggs/create', $data);
        }
    }

    /**
     * Supprime un cadre (API JSON)
     */
    public function delete() {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Non autorisé']);
            return;
        }

        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
            return;
        }

        // Récupérer les données JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID manquant']);
            return;
        }

        // Vérifier que le cadre existe
        $cadre = $this->cadreModel->getById($id);
        if (!$cadre) {
            echo json_encode(['success' => false, 'error' => 'Cadre introuvable']);
            return;
        }

        // Supprimer le cadre (le modèle gère aussi la suppression du fichier)
        if ($this->cadreModel->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Cadre supprimé avec succès']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression']);
        }
    }
}

?>
