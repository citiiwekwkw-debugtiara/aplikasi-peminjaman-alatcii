<?php

class User extends Controller {
    public function __construct() {
        if(!isset($_SESSION['login']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Manajemen User';
        $db = new Database;
        $db->query("SELECT * FROM users");
        $data['users'] = $db->resultSet();
        $this->view('templates/header', $data);
        $this->view('user/index', $data);
        $this->view('templates/footer');
    }

    public function tambah() {
        $db = new Database;
        $db->query("INSERT INTO users (nama, username, password_md5, role) VALUES (:nama, :username, :pass, :role)");
        $db->bind('nama', $_POST['nama']);
        $db->bind('username', $_POST['username']);
        $db->bind('pass', md5($_POST['password']));
        $db->bind('role', $_POST['role']);
        $db->execute();
        Flasher::setFlash('berhasil', 'ditambahkan', 'success');
        header('Location: ' . BASEURL . '/user');
        exit;
    }

    public function getubah() {
        echo json_encode($this->model('User_model')->getUserById($_POST['id']));
    }

    public function ubah() {
        if($this->model('User_model')->ubahDataUser($_POST) > 0) {
            Flasher::setFlash('berhasil', 'diubah', 'success');
        } else {
            Flasher::setFlash('gagal', 'diubah', 'danger');
        }
        header('Location: ' . BASEURL . '/user');
        exit;
    }

    public function hapus($id) {
        if($this->model('User_model')->hapusDataUser($id) > 0) {
            Flasher::setFlash('berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/user');
        exit;
    }
}
