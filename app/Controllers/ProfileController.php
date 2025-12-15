<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/FavoriModel.php";

class ProfileController extends BaseController {
    private $userModel;
    private $favoriModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->favoriModel = new FavoriModel();
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

    public function updateProfile() {
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

        if (!$user) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé']);
            return;
        }

        // Récupérer les données du formulaire
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Données invalides']);
            return;
        }

        // Validation des données selon le type de personne
        $errors = [];
        
        // Email obligatoire et unique
        if (empty($data['email_locataire'])) {
            $errors['email'] = 'L\'email est obligatoire';
        } elseif (!filter_var($data['email_locataire'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format d\'email invalide';
        } elseif ($data['email_locataire'] !== $user['email_locataire'] && $this->userModel->emailExists($data['email_locataire'])) {
            $errors['email'] = 'Cet email est déjà utilisé';
        }

        // Déterminer le type de personne basé sur les données existantes
        $isPersonneMorale = !empty($user['RaisonSociale']);
        
        // Validation selon le type (personne physique ou morale)
        if ($isPersonneMorale) {
            // Personne morale
            if (empty($data['RaisonSociale'])) {
                $errors['RaisonSociale'] = 'La raison sociale est obligatoire';
            }
            // Siret optionnel mais doit être valide si fourni
            if (!empty($data['Siret']) && !preg_match('/^\d{14}$/', str_replace(' ', '', $data['Siret']))) {
                $errors['Siret'] = 'Le SIRET doit contenir 14 chiffres';
            }
        } else {
            // Personne physique
            if (empty($data['nom_locataire'])) {
                $errors['nom'] = 'Le nom est obligatoire';
            }
            if (empty($data['prenom_locataire'])) {
                $errors['prenom'] = 'Le prénom est obligatoire';
            }
            // Date de naissance optionnelle mais doit être valide si fournie
            if (!empty($data['dateNaissance_locataire'])) {
                $date = DateTime::createFromFormat('Y-m-d', $data['dateNaissance_locataire']);
                if (!$date || $date->format('Y-m-d') !== $data['dateNaissance_locataire']) {
                    $errors['dateNaissance'] = 'Date de naissance invalide';
                }
            }
        }

        // Validation du téléphone (optionnel)
        if (!empty($data['tel_locataire']) && !preg_match('/^[0-9\s\+\-\(\)]+$/', $data['tel_locataire'])) {
            $errors['tel'] = 'Numéro de téléphone invalide';
        }

        // Si des erreurs existent, les retourner
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }

        // Préparer les données pour la mise à jour
        $updateData = [
            'email_locataire' => trim($data['email_locataire']),
            'tel_locataire' => !empty($data['tel_locataire']) ? trim($data['tel_locataire']) : null,
            'rue_locataire' => !empty($data['rue_locataire']) ? trim($data['rue_locataire']) : null,
            'complement_locataire' => !empty($data['complement_locataire']) ? trim($data['complement_locataire']) : null,
            'id_commune' => $user['id_commune'] ?? null
        ];

        // Ajouter les champs spécifiques selon le type
        if ($isPersonneMorale) {
            // Personne morale
            $updateData['RaisonSociale'] = trim($data['RaisonSociale']);
            $updateData['Siret'] = !empty($data['Siret']) ? str_replace(' ', '', trim($data['Siret'])) : null;
            $updateData['nom_locataire'] = null;
            $updateData['prenom_locataire'] = null;
            $updateData['dateNaissance_locataire'] = null;
        } else {
            // Personne physique
            $updateData['nom_locataire'] = trim($data['nom_locataire']);
            $updateData['prenom_locataire'] = trim($data['prenom_locataire']);
            $updateData['dateNaissance_locataire'] = !empty($data['dateNaissance_locataire']) ? $data['dateNaissance_locataire'] : null;
            $updateData['RaisonSociale'] = null;
            $updateData['Siret'] = null;
        }

        // Effectuer la mise à jour
        try {
            // Log des données avant mise à jour pour débogage
            error_log("UserID: " . $userId);
            error_log("UpdateData: " . print_r($updateData, true));
            
            $updated = $this->userModel->update($userId, $updateData);

            // Mettre à jour la session si l'email a changé
            if ($data['email_locataire'] !== $user['email_locataire']) {
                $_SESSION['user_email'] = $data['email_locataire'];
            }

            echo json_encode([
                'success' => true,
                'message' => 'Profil mis à jour avec succès'
            ]);
        } catch (PDOException $e) {
            error_log("Erreur PDO lors de la mise à jour du profil : " . $e->getMessage());
            error_log("Code erreur: " . $e->getCode());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erreur de base de données : ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du profil : " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour du profil : ' . $e->getMessage()
            ]);
        }
    }

    // Mettre à jour le cadre de profil
    public function updateFrame() {
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
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['cadre_profil'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Données invalides']);
            return;
        }

        // Liste des chemins de cadres valides (null pour défaut, ou PNG paths)
        $validFramePaths = [
            null,
            '/cadre/images/gold.png',
            '/cadre/images/silver.png',
            '/cadre/images/bronze.png',
            '/cadre/images/rainbow.png',
            '/cadre/images/glacier.png',
            '/cadre/images/pink.png',
            '/cadre/images/emerald.png',
            '/cadre/images/mystique.png'
        ];

        $cadrePath = $data['cadre_profil']; // null ou chemin PNG

        // Valider le chemin (null ou chemin PNG valide)
        if (!in_array($cadrePath, $validFramePaths, true)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Cadre invalide']);
            return;
        }

        // Mettre à jour la base de données
        try {
            $result = $this->userModel->updateCadreProfile($userId, $cadrePath);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cadre mis à jour avec succès']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du cadre : " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
        }
    }

    /**
     * Easter Egg - Débloquer les cadres de profil
     */
    public function unlockFrames() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour débloquer cette fonctionnalité.";
            $this->redirect('/login');
            return;
        }

        $userId = $_SESSION['user_id'];

        // Mettre à jour la colonne frames_unlocked
        try {
            $result = $this->userModel->unlockFrames($userId);

            if ($result) {
                $_SESSION['success'] = "🎉 Easter Egg débloqué ! Les cadres de profil sont maintenant disponibles.";
                $this->redirect('/profile');
            } else {
                $_SESSION['error'] = "Erreur lors du déverrouillage des cadres.";
                $this->redirect('/profile');
            }
        } catch (Exception $e) {
            error_log("Erreur lors du déverrouillage des cadres : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du déverrouillage des cadres.";
            $this->redirect('/profile');
        }
    }

    /**
     * Afficher la page des favoris (accessible à tous les utilisateurs connectés)
     */
    public function myFavorites() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $userId = $_SESSION['user_id'];
        
        // Récupérer tous les favoris de l'utilisateur depuis la base de données
        $favorites = $this->favoriModel->getFavorisByUserId($userId);

        $this->render("profile/myFavorites", [
            'favorites' => $favorites
        ]);
    }

    /**
     * API pour gérer les favoris (ajouter/retirer)
     * Accessible à tous les utilisateurs connectés (locataires et propriétaires)
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
     * Accessible à tous les utilisateurs connectés
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
