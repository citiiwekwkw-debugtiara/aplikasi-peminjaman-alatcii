<?php

class Peminjaman extends Controller {
    public function __construct() {
        if(!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Daftar Peminjaman';
        $data['pinjam'] = $this->model('Peminjaman_model')->getAllPeminjaman();
        
        $db = new Database;
        $db->query("SELECT * FROM alat WHERE stok > 0");
        $data['alat_tersedia'] = $db->resultSet();

        $db->query("SELECT id_user, nama FROM users ORDER BY nama ASC");
        $data['users'] = $db->resultSet();

        $this->view('templates/header', $data);
        $this->view('peminjaman/index', $data);
        $this->view('templates/footer');
    }

    public function ajukan() {
        if($this->model('Peminjaman_model')->ajukanPeminjaman($_POST) > 0) {
            Flasher::setFlash('berhasil', 'diajukan', 'success');
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        } else {
            Flasher::setFlash('gagal', 'diajukan', 'danger');
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        }
    }

    public function setujui($id) {
        if($this->model('Peminjaman_model')->setujuPeminjaman($id) > 0) {
            Flasher::setFlash('berhasil', 'disetujui', 'success');
        }
        header('Location: ' . BASEURL . '/peminjaman');
        exit;
    }

    public function proses_pinjam($id) {
        if($this->model('Peminjaman_model')->pinjamBarang($id) > 0) {
            Flasher::setFlash('berhasil', 'dikonfirmasi dipinjam', 'success');
        }
        header('Location: ' . BASEURL . '/peminjaman');
        exit;
    }

    public function kembalikan($id) {
        $tgl_kembali = date('Y-m-d');
        if($this->model('Peminjaman_model')->kembalikanBarang($id, $tgl_kembali) > 0) {
            Flasher::setFlash('berhasil', 'dikembalikan', 'success');
        }
        header('Location: ' . BASEURL . '/peminjaman');
        exit;
    }

    public function getubah() {
        echo json_encode($this->model('Peminjaman_model')->getPeminjamanById($_POST['id']));
    }

    public function ubah() {
        if($this->model('Peminjaman_model')->ubahDataPeminjaman($_POST) > 0) {
            Flasher::setFlash('berhasil', 'diubah', 'success');
        } else {
            Flasher::setFlash('gagal', 'diubah', 'danger');
        }
        header('Location: ' . BASEURL . '/peminjaman');
        exit;
    }

    public function laporan() {
        if($_SESSION['user']['role'] == 'siswa') {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
        $data['judul'] = 'Laporan Peminjaman Alat';
        $data['laporan'] = $this->model('Peminjaman_model')->getLaporanPeminjaman();
        $this->view('peminjaman/laporan', $data);
    }
}
