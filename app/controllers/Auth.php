<?php

class Auth extends Controller {
    public function index() {
        if(isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
        $data['judul'] = 'Login';
        $this->view('auth/login', $data);
    }

    public function login() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $this->model('User_model')->login($username, $password);

        if($user) {
            $_SESSION['login'] = true;
            $_SESSION['user'] = $user;
            
            // Log aktivitas
            $this->logActivity($user['id_user'], "User logged in");

            // Simpan ke Model Notifikasi
            $this->model('Notification_model')->createNotif($user['id_user'], "Sesi login baru pada " . date('H:i:s'), 'success');

            Flasher::setModalFlash('Login Berhasil!', 'Selamat datang kembali, ' . $user['nama'], 'success');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        } else {
            Flasher::setModalFlash('Login Gagal', 'Username atau password salah!', 'danger');
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function logout() {
        if (isset($_SESSION['user']['id_user'])) {
            $user_id = $_SESSION['user']['id_user'];
            $this->logActivity($user_id, "User logged out");
            
            // Simpan ke Model Notifikasi
            $this->model('Notification_model')->createNotif($user_id, "Logout berhasil pada " . date('H:i:s'), 'info');
        }

        session_destroy();
        session_start();
        Flasher::setModalFlash('Logout Berhasil', 'Anda telah keluar dari sistem.', 'info');
        header('Location: ' . BASEURL . '/auth');
        exit;
    }

    private function logActivity($id_user, $aktivitas) {
        try {
            $db = new Database;
            $db->query("INSERT INTO log_aktivitas (id_user, aktivitas) VALUES (:id_user, :aktivitas)");
            $db->bind('id_user', $id_user);
            $db->bind('aktivitas', $aktivitas);
            $db->execute();
        } catch (PDOException $e) {
            // Log error to file if needed, but don't crash the app
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
}
