<?php

class Notification extends Controller {
    public function __construct() {
        if(!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function markAllRead() {
        if ($this->model('Notification_model')->markAllAsRead($_SESSION['user']['id_user']) > 0) {
            Flasher::setFlash('Semua notifikasi', 'berhasil', 'ditandai sudah dibaca', 'success');
        } else {
            Flasher::setFlash('Tidak ada notifikasi baru', 'untuk', 'ditandai', 'info');
        }
        $referrer = $_SERVER['HTTP_REFERER'] ?? BASEURL;
        header('Location: ' . $referrer);
        exit;
    }
}
