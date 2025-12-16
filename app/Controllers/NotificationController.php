<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/NotificationModel.php';
require_once __DIR__ . '/AuthMiddleware.php';

class NotificationController extends BaseController {
    private $notificationModel;

    public function __construct() {
        $this->notificationModel = new NotificationModel();
    }

    public function list() {
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]);
        header('Content-Type: application/json; charset=utf-8');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { echo json_encode([]); return; }
        $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 20;
        $offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;
        echo json_encode($this->notificationModel->listForUser($userId, $limit, $offset));
    }

    public function count() {
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]);
        header('Content-Type: application/json; charset=utf-8');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { echo json_encode(['count' => 0]); return; }
        $count = $this->notificationModel->countUnread($userId);
        echo json_encode(['count' => $count]);
    }

    public function markRead($id) {
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]);
        header('Content-Type: application/json; charset=utf-8');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { echo json_encode(['ok' => false]); return; }
        $ok = $this->notificationModel->markAsRead($userId, (int)$id) > 0;
        echo json_encode(['ok' => $ok]);
    }

    public function markAllRead() {
        AuthMiddleware::checkUserRole(["Locataire", "Propriétaire"]);
        header('Content-Type: application/json; charset=utf-8');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { echo json_encode(['updated' => 0]); return; }
        $updated = $this->notificationModel->markAllAsRead($userId);
        echo json_encode(['updated' => $updated]);
    }
}

?>
