<?php
// Page de déverrouillage des cadres de profil
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit;
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/Models/UserModel.php';

$userModel = new UserModel();
$userId = $_SESSION['user_id'];

// Mettre à jour la colonne frames_unlocked
$updateResult = $userModel->update($userId, ['frames_unlocked' => 1]);

if ($updateResult) {
    // Redirection vers le profil avec succès
    header('Location: /profile?frames_unlocked=1');
    exit;
} else {
    // Erreur lors de la mise à jour
    header('Location: /profile?frames_error=1');
    exit;
}
?>