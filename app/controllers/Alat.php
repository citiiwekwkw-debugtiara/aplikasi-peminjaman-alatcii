<?php

class Alat extends Controller {
    public function __construct() {
        if(!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Daftar Alat';
        $data['alat'] = $this->model('Alat_model')->getAllAlat();
        
        $db = new Database;
        $db->query("SELECT * FROM kategori");
        $data['kategori'] = $db->resultSet();

        $this->view('templates/header', $data);
        $this->view('alat/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id) {
        $data['judul'] = 'Detail Alat';
        $data['alat'] = $this->model('Alat_model')->getAlatById($id);
        $this->view('templates/header', $data);
        $this->view('alat/detail', $data);
        $this->view('templates/footer');
    }

    public function tambah() {
        $_POST['gambar'] = $this->uploadGambar();
        if($this->model('Alat_model')->tambahDataAlat($_POST) > 0) {
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
            header('Location: ' . BASEURL . '/alat');
            exit;
        } else {
            Flasher::setFlash('gagal', 'ditambahkan', 'danger');
            header('Location: ' . BASEURL . '/alat');
            exit;
        }
    }

    private function uploadGambar() {
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];

        // Cek apakah tidak ada gambar yang diupload
        if($error === 4) {
            return 'default.jpg';
        }

        // Cek apakah yang diupload adalah gambar
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if(!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            Flasher::setFlash('gagal', 'ekstensi tidak valid', 'danger');
            return false;
        }

        // Cek jika ukurannya terlalu besar (maks 2MB)
        if($ukuranFile > 2000000) {
            Flasher::setFlash('gagal', 'ukuran terlalu besar', 'danger');
            return false;
        }

        // Lolos pengecekan, siap upload
        // Generate nama file baru
        $namaFileBaru = uniqid();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensiGambar;

        move_uploaded_file($tmpName, 'assets/img/alat/' . $namaFileBaru);

        return $namaFileBaru;
    }

    public function hapus($id) {
        if($this->model('Alat_model')->hapusDataAlat($id) > 0) {
            Flasher::setFlash('berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/alat');
            exit;
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/alat');
            exit;
        }
    }

    public function ubah() {
        if ($_FILES['gambar']['error'] === 4) {
            $_POST['gambar'] = $_POST['gambarLama'];
        } else {
            $_POST['gambar'] = $this->uploadGambar();
        }

        if($this->model('Alat_model')->ubahDataAlat($_POST) > 0) {
            Flasher::setFlash('berhasil', 'diubah', 'success');
            header('Location: ' . BASEURL . '/alat');
            exit;
        } else {
            Flasher::setFlash('gagal', 'diubah', 'danger');
            header('Location: ' . BASEURL . '/alat');
            exit;
        }
    }
}
