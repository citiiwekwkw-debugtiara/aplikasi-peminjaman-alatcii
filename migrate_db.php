<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database;
try {
    $db->query("ALTER TABLE peminjaman ADD COLUMN nama_peminjam VARCHAR(100) AFTER id_user");
    $db->execute();
    
    // Migrasi data lama: isi nama_peminjam dari tabel users
    $db->query("UPDATE peminjaman p JOIN users u ON p.id_user = u.id_user SET p.nama_peminjam = u.nama");
    $db->execute();
    
    echo "Column 'nama_peminjam' added and populated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
