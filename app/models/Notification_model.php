<?php

class Notification_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createNotif($user_id, $message, $type = 'info') {
        try {
            $this->db->query("INSERT INTO notifikasi (id_user, pesan, tipe) VALUES (:id_user, :pesan, :tipe)");
            $this->db->bind('id_user', $user_id);
            $this->db->bind('pesan', $message);
            $this->db->bind('tipe', $type);
            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            error_log("Failed to create notification: " . $e->getMessage());
            return 0;
        }
    }

    public function getUnreadNotif($user_id) {
        $this->db->query("SELECT * FROM notifikasi WHERE id_user = :id_user AND is_read = 0 ORDER BY created_at DESC");
        $this->db->bind('id_user', $user_id);
        return $this->db->resultSet();
    }

    public function markAsRead($id) {
        $this->db->query("UPDATE notifikasi SET is_read = 1 WHERE id_notif = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function markAllAsRead($user_id) {
        try {
            $this->db->query("UPDATE notifikasi SET is_read = 1 WHERE id_user = :id_user");
            $this->db->bind('id_user', $user_id);
            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            error_log("Failed to mark all notifications as read: " . $e->getMessage());
            return 0;
        }
    }
}
