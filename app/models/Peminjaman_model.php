<?php

class Peminjaman_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllPeminjaman() {
        $this->db->query("SELECT p.*, pg.denda, pg.tanggal_dikembalikan 
                          FROM peminjaman p 
                          LEFT JOIN pengembalian pg ON p.id_pinjam = pg.id_pinjam 
                          ORDER BY p.tanggal_pinjam DESC");
        return $this->db->resultSet();
    }

    public function getPeminjamanById($id) {
        $this->db->query("SELECT p.*, dp.id_alat, dp.jumlah, pg.denda 
                          FROM peminjaman p 
                          JOIN detail_peminjaman dp ON p.id_pinjam = dp.id_pinjam 
                          LEFT JOIN pengembalian pg ON p.id_pinjam = pg.id_pinjam
                          WHERE p.id_pinjam = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function ajukanPeminjaman($data) {
        try {
            // Start Transaction
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // Insert into peminjaman
            $this->db->query("INSERT INTO peminjaman (id_user, nama_peminjam, tanggal_pinjam, tanggal_kembali, status) VALUES (:id_user, :nama_peminjam, :tgl_pinjam, :tgl_kembali, 'menunggu')");
            $this->db->bind('id_user', $_SESSION['user']['id_user']);
            $this->db->bind('nama_peminjam', $data['nama_peminjam']);
            $this->db->bind('tgl_pinjam', $data['tanggal_pinjam']);
            $this->db->bind('tgl_kembali', $data['tanggal_kembali']);
            $this->db->execute();
            
            $id_pinjam = $this->db->lastInsertId();

            // Insert into detail_peminjaman
            $this->db->query("INSERT INTO detail_peminjaman (id_pinjam, id_alat, jumlah) VALUES (:id_pinjam, :id_alat, :jumlah)");
            $this->db->bind('id_pinjam', $id_pinjam);
            $this->db->bind('id_alat', $data['id_alat']);
            $this->db->bind('jumlah', $data['jumlah']);
            $this->db->execute();

            // Commit Transaction
            $this->db->query("COMMIT");
            $this->db->execute();
            
            return 1;
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return 0;
        }
    }

    public function setujuPeminjaman($id) {
        $this->db->query("UPDATE peminjaman SET status = 'disetujui' WHERE id_pinjam = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function pinjamBarang($id) {
        // Trigger handle inventory update in DB
        $this->db->query("UPDATE peminjaman SET status = 'dipinjam' WHERE id_pinjam = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function kembalikanBarang($id_pinjam, $tgl_kembali) {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // Get loan data to calculate fine
            $this->db->query("SELECT tanggal_kembali FROM peminjaman WHERE id_pinjam = :id");
            $this->db->bind('id', $id_pinjam);
            $loan = $this->db->single();

            // Use SQL Function to calculate fine
            $this->db->query("SELECT fn_hitung_denda(:tgl_harusnya, :tgl_real) as denda");
            $this->db->bind('tgl_harusnya', $loan['tanggal_kembali']);
            $this->db->bind('tgl_real', $tgl_kembali);
            $fine = $this->db->single()['denda'];

            // Insert into pengembalian
            $this->db->query("INSERT INTO pengembalian (id_pinjam, tanggal_dikembalikan, denda) VALUES (:id_pinjam, :tgl, :denda)");
            $this->db->bind('id_pinjam', $id_pinjam);
            $this->db->bind('tgl', $tgl_kembali);
            $this->db->bind('denda', $fine);
            $this->db->execute();

            // Update status
            $this->db->query("UPDATE peminjaman SET status = 'dikembalikan' WHERE id_pinjam = :id_pinjam");
            $this->db->bind('id_pinjam', $id_pinjam);
            $this->db->execute();

            $this->db->query("COMMIT");
            $this->db->execute();
            return 1;
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return 0;
        }
    }

    public function ubahDataPeminjaman($data) {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            $this->db->query("UPDATE peminjaman SET nama_peminjam = :nama_peminjam, tanggal_pinjam = :tgl_pinjam, tanggal_kembali = :tgl_kembali WHERE id_pinjam = :id_pinjam");
            $this->db->bind('nama_peminjam', $data['nama_peminjam']);
            $this->db->bind('tgl_pinjam', $data['tanggal_pinjam']);
            $this->db->bind('tgl_kembali', $data['tanggal_kembali']);
            $this->db->bind('id_pinjam', $data['id_pinjam']);
            $this->db->execute();

            $this->db->query("UPDATE detail_peminjaman SET id_alat = :id_alat, jumlah = :jumlah WHERE id_pinjam = :id_pinjam");
            $this->db->bind('id_alat', $data['id_alat']);
            $this->db->bind('jumlah', $data['jumlah']);
            $this->db->bind('id_pinjam', $data['id_pinjam']);
            $this->db->execute();

            $this->db->query("COMMIT");
            $this->db->execute();
            return 1;
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return 0;
        }
    }

    public function getLaporanPeminjaman() {
        $this->db->query("SELECT p.*, dp.jumlah, a.nama_alat, pg.denda, pg.tanggal_dikembalikan 
                          FROM peminjaman p 
                          JOIN detail_peminjaman dp ON p.id_pinjam = dp.id_pinjam 
                          JOIN alat a ON dp.id_alat = a.id_alat 
                          LEFT JOIN pengembalian pg ON p.id_pinjam = pg.id_pinjam 
                          ORDER BY p.tanggal_pinjam DESC");
        return $this->db->resultSet();
    }
}
