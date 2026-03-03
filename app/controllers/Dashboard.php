<?php

class Dashboard extends Controller {
    public function __construct() {
        if(!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Dashboard';
        
        $db = new Database;
        
        // Get Statistics
        $db->query("SELECT COUNT(*) as total FROM alat");
        $data['total_alat'] = $db->single()['total'];
        
        $db->query("SELECT COUNT(*) as total FROM users");
        $data['total_users'] = $db->single()['total'];

        $db->query("SELECT COUNT(*) as total FROM kategori");
        $data['total_kategori'] = $db->single()['total'];
        
        $db->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'dipinjam'");
        $data['total_dipinjam'] = $db->single()['total'];
        
        $db->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'menunggu'");
        $data['total_antrean'] = $db->single()['total'];

        $db->query("SELECT SUM(denda) as total FROM pengembalian");
        $data['total_denda'] = $db->single()['total'] ?? 0;

        // Get Recent Logs
        $db->query("SELECT l.*, u.nama FROM log_aktivitas l JOIN users u ON l.id_user = u.id_user ORDER BY l.tanggal DESC LIMIT 5");
        $data['recent_logs'] = $db->resultSet();
        
        // Get Recent Borrowings
        $db->query("SELECT * FROM peminjaman ORDER BY tanggal_pinjam DESC LIMIT 5");
        $data['recent_borrowings'] = $db->resultSet();

        $this->view('templates/header', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/footer');
    }
}
