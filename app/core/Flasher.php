<?php

class Flasher {
    public static function setFlash($pesan, $aksi, $tipe) {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe,
            'is_modal' => false
        ];
    }

    public static function setModalFlash($judul, $pesan, $tipe) {
        $_SESSION['flash'] = [
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe'  => $tipe,
            'is_modal' => true
        ];
    }

    public static function flash() {
        if(isset($_SESSION['flash'])) {
            if($_SESSION['flash']['is_modal']) {
                $icon = $_SESSION['flash']['tipe']; // success, error, warning, info
                if($icon == 'danger') $icon = 'error';
                
                echo "<script>
                    Swal.fire({
                        title: '" . $_SESSION['flash']['judul'] . "',
                        text: '" . $_SESSION['flash']['pesan'] . "',
                        icon: '" . $icon . "',
                        confirmButtonColor: '#0d6efd'
                    });
                </script>";
            } else {
                echo '<div class="alert alert-' . $_SESSION['flash']['tipe'] . ' alert-dismissible fade show" role="alert">
                        Data <strong>' . $_SESSION['flash']['pesan'] . '</strong> ' . $_SESSION['flash']['aksi'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
            unset($_SESSION['flash']);
        }
    }
}
