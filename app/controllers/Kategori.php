<?php

class Kategori extends Controller {
    public function __construct() {
        if(!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        // Hanya admin yang bisa akses kategori
        if($_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Daftar Kategori';
        $data['kategori'] = $this->model('Kategori_model')->getAllKategori();
        $this->view('templates/header', $data);
        $this->view('kategori/index', $data);
        $this->view('templates/footer');
    }

    public function tambah() {
        if($this->model('Kategori_model')->tambahDataKategori($_POST) > 0) {
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
            header('Location: ' . BASEURL . '/kategori');
            exit;
        } else {
            Flasher::setFlash('gagal', 'ditambahkan', 'danger');
            header('Location: ' . BASEURL . '/kategori');
            exit;
        }
    }

    public function hapus($id) {
        if($this->model('Kategori_model')->hapusDataKategori($id) > 0) {
            Flasher::setFlash('berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/kategori');
            exit;
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/kategori');
            exit;
        }
    }
}
