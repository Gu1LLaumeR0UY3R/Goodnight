<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";

class ProfileController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);

        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            $this->redirect('/');
            return;
        }

        $this->render('profile/index', ['user' => $user]);
    }

    public function uploadProfilePicture() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Non authentifié']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
            return;
        }

        $userId = $_SESSION['user_id'];

        // Vérifier qu'un fichier a été uploadé
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Aucun fichier uploadé ou erreur lors de l\'upload']);
            return;
        }

        $file = $_FILES['profile_picture'];

        // Validation du type de fichier
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Type de fichier non autorisé. Formats acceptés: JPG, PNG, GIF, WEBP']);
            return;
        }

        // Validation de la taille (max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Fichier trop volumineux. Taille maximale: 5MB']);
            return;
        }

        // Créer le dossier pfp s'il n'existe pas
        $uploadDir = __DIR__ . '/../../public/pfp/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        // Récupérer l'ancienne photo pour la supprimer
        $user = $this->userModel->getById($userId);
        $oldPfp = $user['pfp_loca'] ?? null;

        // Déplacer le fichier uploadé
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'enregistrement du fichier']);
            return;
        }

        // Mettre à jour la base de données
        $relativePath = '/pfp/' . $filename;
        $updated = $this->userModel->updateProfilePicture($userId, $relativePath);

        if (!$updated) {
            // Supprimer le fichier uploadé en cas d'erreur
            unlink($uploadPath);
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour de la base de données']);
            return;
        }

        // Supprimer l'ancienne photo si elle existe
        if ($oldPfp && file_exists(__DIR__ . '/../../public' . $oldPfp)) {
            unlink(__DIR__ . '/../../public' . $oldPfp);
        }

        // Mettre à jour la session
        $_SESSION['user_pfp'] = $relativePath;

        echo json_encode([
            'success' => true, 
            'message' => 'Photo de profil mise à jour avec succès',
            'pfp_url' => $relativePath
        ]);
    }

    public function deleteProfilePicture() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Non authentifié']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        $oldPfp = $user['pfp_loca'] ?? null;

        if (!$oldPfp) {
            echo json_encode(['success' => true, 'message' => 'Aucune photo de profil à supprimer']);
            return;
        }

        // Supprimer le fichier
        $filePath = __DIR__ . '/../../public' . $oldPfp;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Mettre à jour la base de données
        $updated = $this->userModel->updateProfilePicture($userId, null);

        if (!$updated) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour de la base de données']);
            return;
        }

        // Mettre à jour la session
        $_SESSION['user_pfp'] = null;

        echo json_encode([
            'success' => true, 
            'message' => 'Photo de profil supprimée avec succès'
        ]);
    }
}
?>
