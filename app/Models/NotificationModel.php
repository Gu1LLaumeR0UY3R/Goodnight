<?php

require_once __DIR__ . '/Model.php';

class NotificationModel extends Model {
    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';

    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, type, title, message, link) VALUES (:user_id, :type, :title, :message, :link)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'link' => $data['link'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        foreach (['title', 'message', 'link', 'is_read'] as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "$f = :$f";
                $params[$f] = $data[$f];
            }
        }
        if (!$fields) return 0;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function listForUser($userId, $limit = 20, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    public function markAsRead($userId, $notificationId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE {$this->primaryKey} = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $notificationId, 'user_id' => $userId]);
        return $stmt->rowCount();
    }

    public function markAllAsRead($userId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->rowCount();
    }

    public function notifyUsersWithFutureReservationsForBien($id_biens, $title, $message, $link = null) {
        $sql = "SELECT DISTINCT r.id_locataire FROM reservations r WHERE r.id_biens = :id_biens AND r.date_fin >= CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_biens' => $id_biens]);
        $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($users as $uid) {
            $this->create([
                'user_id' => (int)$uid,
                'type' => 'bien_change',
                'title' => $title,
                'message' => $message,
                'link' => $link,
            ]);
        }
        return count($users);
    }
}

?>
